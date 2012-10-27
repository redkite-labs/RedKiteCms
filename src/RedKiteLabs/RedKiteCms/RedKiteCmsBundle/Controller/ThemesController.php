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

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AlphaLemon\ThemeEngineBundle\Controller\ThemesController as BaseController;
//use AlphaLemon\AlphaLemonCmsBundle\Core\SiteBootstrap\AlSiteBootstrap;

class ThemesController extends BaseController
{
    public function activateCmsThemeAction($themeName, $languageName, $pageName)
    {
        try {
            $this->getActiveTheme()->writeActiveTheme($themeName);
            $url = $this->container->get('router')->generate('_navigation', array('_locale' => $languageName, 'page' => $pageName));

            // Url must contain all parts otherwise errors could occour
            if (!preg_match('/backend\/[\w]+\/[\w]+/', $url)) {
                $url .= sprintf('/%s/%s', $languageName, $pageName);
            }

            return new RedirectResponse($url);
        } catch (Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    public function showThemeFixerAction()
    {
        return $this->renderThemeFixer();
    }

    public function fixThemeAction()
    {
        try {
            $error = null;
            $request = $this->container->get('request');

            $params = array();
            $data = explode('&', $request->get('data'));
            foreach ($data as $value) {
                $tmp = preg_split('/=/', $value);
                if ($tmp[0] == 'al_page_to_fix') {
                    $params[$tmp[0]][] = $tmp[1];
                } else {
                    $params[$tmp[0]] = $tmp[1];
                }
            }

            if (empty($params['al_page_to_fix'])) {
                $error = 'Any page has been selected';

                return $this->renderThemeFixer($error);
            }

            $pageManager = $this->container->get('alpha_lemon_cms.page_manager');
            $factoryRepository = $this->container->get('alpha_lemon_cms.factory_repository');
            $pagesRepository = $factoryRepository->createRepository('Page');
            foreach ($params['al_page_to_fix'] as $pageId) {
                $alPage = $pagesRepository->fromPK($pageId);
                $pageManager->set($alPage);
                if (false === $pageManager->save(array('TemplateName' => $params['al_template']))) {
                    $error = sprintf('An error occoured when saving the new template for the page %s. Operation aborted', $alPage->getPageName());

                    return $this->renderThemeFixer($error);
                }
            }

            return $this->renderThemeFixer($error);
        } catch (\Exception $e) {
            $error = 'An error occourced: ' . $e->getMessage();

            return $this->renderThemeFixer($error);
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

    protected function renderThemeFixer($error = null)
    {
        $request = $this->container->get('request');
        $themeName = $request->get('themeName');

        $themes = $this->container->get('alpha_lemon_theme_engine.themes');
        $theme = $themes->getTheme($themeName);
        $templates = array_keys($theme->getTemplates());

        $factoryRepository = $this->container->get('alpha_lemon_cms.factory_repository');
        $pagesRepository = $factoryRepository->createRepository('Page');
        $pages = $pagesRepository->activePages();

        return $this->container->get('templating')->renderResponse('AlphaLemonCmsBundle:Themes:show_theme_fixer.html.twig', array('templates' => $templates, 'pages' => $pages, 'themeName' => $themeName, 'error' => $error));
    }
}
