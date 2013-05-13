<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use AlphaLemon\ThemeEngineBundle\Controller\ThemesController as BaseController;

class ThemesController extends BaseController
{
    public function showThemeChangerAction()
    {
        return $this->renderThemeChanger();
    }
    
    public function changeSlotAction()
    {
        $request = $this->container->get('request');
        $sourceSlotName = $request->get('sourceSlotName');        
        $targetSlotName = $request->get('targetSlotName');
        
        $themeChanger = $this->container->get('alpha_lemon_cms.theme_changer');
        $message = $themeChanger->changeSlot($sourceSlotName, $targetSlotName);
        
        $templateSlots = new \AlphaLemon\AlphaLemonCmsBundle\Core\ThemeChanger\AlTemplateSlots($this->container);
        $slots = $templateSlots
            ->run($request->get('languageId'), $request->get('pageId'))
            ->getSlots()
        ;
        
        $values = array(
            array(
                'key' => 'slots',
                'value' => $this->container->get('templating')->render('AlphaLemonCmsBundle:Cms:template_slots_panel.html.twig', array('slots' => $slots)),            
            ),            
            array(
                'key' => 'message',
                'value' => $message,
            ),
        );
        
        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
    public function changeThemeAction()
    {
        try {
            
            $request = $this->container->get('request');

            $map = array();
            $data = explode('&', $request->get('data'));
            
            $c = 0;
            while($c < count($data)) {
                $template = preg_split('/=/', $data[$c]);
                $templateName = $template[1];
                $mappedTemplate = preg_split('/=/', $data[$c+1]);
                $mappedTemplateName = $mappedTemplate[1];
                if (empty($mappedTemplateName)) {
                    throw new \InvalidArgumentException("It seems you haven't mapped the \"$templateName\" template. To change a theme each template must be mapped with a template from the new theme");
                }
                
                $map[$templateName] = $mappedTemplateName;
                $c += 2;
            }
            
            $themeName = $request->get('themeName');
            
            $currentTheme = $this->getActiveTheme();
            
            $themeChanger = $this->container->get('alpha_lemon_cms.theme_changer');
            $themes = $this->container->get('alpha_lemon_theme_engine.themes');            
            $previousTheme = $themes->getTheme($currentTheme->getActiveTheme());
            $theme = $themes->getTheme($themeName);
            $themeChanger->change($previousTheme, $theme, $this->container->getParameter('alpha_lemon_cms.theme_structure_file'), $map);
            $currentTheme->writeActiveTheme($themeName);
            
            return new Response('The theme has been changed. Please wait while your site is reloading', 200);            
        } catch (\Exception $e) {            
            return $this->renderThemeChanger($e->getMessage());
        }
    }
    
    public function startFromThemeAction()
    {
        $request = $this->container->get('request');
        $themeName = $request->get('themeName');
        
        $themes = $this->container->get('alpha_lemon_theme_engine.themes');
        $theme = $themes->getTheme($themeName);
        $template = $theme->getHomeTemplate();

        $templateManager = $this->container->get('alpha_lemon_cms.template_manager');
        $templateManager
            ->setTemplate($template)
            ->refresh();
        
        $siteBootstrap = $this->container->get('alpha_lemon_cms.site_bootstrap');        
        $result = $siteBootstrap
                    ->setTemplateManager($templateManager)
                    ->bootstrap();
        
        if ($result) {
            $message = "The site has been bootstrapped with the new theme. This page is reloading";
            $statusCode = 200;
        }
        else {
            $message = $siteBootstrap->getErrorMessage();
            $statusCode = 404;
        }
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->setStatusCode($statusCode);

        return $this->container->get('templating')->renderResponse('AlphaLemonCmsBundle:Dialog:dialog.html.twig', array('message' => $message), $response);        
    }

    protected function renderThemeChanger($error = null)
    {
        $request = $this->container->get('request');
        $themeName = $request->get('themeName'); 

        $themes = $this->container->get('alpha_lemon_theme_engine.themes');
        
        $factoryRepository = $this->container->get('alpha_lemon_cms.factory_repository');
        $pagesRepository = $factoryRepository->createRepository('Page');
        
        $currentTemplates = array();
        $pages = $pagesRepository->templatesInUse();
        foreach ($pages as $page) {
            $currentTemplates[] = $page->getTemplateName();
        }
        
        $theme = $themes->getTheme($themeName);
        $templates = array_keys($theme->getTemplates());

        $status = null === $error ? 200 : 404;
        $output = $this->container->get('templating')->render('AlphaLemonCmsBundle:Themes:show_theme_changer.html.twig', array('templates' => $templates, 'current_templates' => $currentTemplates, 'themeName' => $themeName, 'error' => $error));

        return new Response($output, $status);
    }
}
