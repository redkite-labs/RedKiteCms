<?php
/*
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

use AlphaLemon\ThemeEngineBundle\Core\ThemeManager\AlThemeManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlLanguageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageQuery;
use AlphaLemon\ThemeEngineBundle\Controller\ThemesController as BaseController;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\Finder;
use AlphaLemon\AlValumUploaderBundle\Core\Options\AlValumUploaderOptionsBuilder;

class ThemesController extends BaseController
{   
    public function showAction()
    {
        $frontcontroller = $this->container->get('kernel')->getEnvironment() . '.php';
        $values = $this->retrieveThemeValues();        
        $customOptions = array("panel_title" => "Themes uploader",
                               "panel_info" => "",
                               "allowed_extensions" => "'zip'",
                               "upload_action" => $frontcontroller . "/al_uploadFile",
                               "folder" => $this->locateThemesFolder(),
                               "onComplete" => "extractTheme()"); 
        $valumOptionsBuilder = new AlValumUploaderOptionsBuilder($this->container);
        $valumOptionsBuilder->build($customOptions);
        
        $stylesheets = array();
        foreach($this->container->getParameter('althemes.stylesheets') as $stylesheet)
        {
            $stylesheets[] = AlToolkit::retrieveBundleWebFolder($this->container, 'AlphaLemonThemeEngineBundle') . '/' . $stylesheet;
        }
        /*
        $activeThemeTemplatesCount = iterator_count($this->retrieveTemplates($values['active_theme']['theme_title']));
        
        $c = 0;
        $availableThemes = $values["available_themes"]["themes"];
        foreach($availableThemes as $availableTheme)
        {
            $r = $activeThemeTemplatesCount - iterator_count($this->retrieveTemplates($availableTheme['theme_title']));
            echo $activeThemeTemplatesCount - iterator_count($this->retrieveTemplates($availableTheme['theme_title']));exit;
            $values["available_themes"]["themes"][$c]["compatible"] = ($activeThemeTemplatesCount - iterator_count($this->retrieveTemplates($availableTheme['theme_title'])) >= 0) ? "0" : "1";
            $c++;
        }  */
        
        $isWindows = (PHP_OS == "WINNT") ? true : false;
        return $this->render($this->container->getParameter('althemes.base_theme_manager_template'), array('base_template' => $this->container->getParameter('althemes.base_template'),
                                                                                         'panel_sections' => $this->container->getParameter('althemes.panel_sections_template'),
                                                                                         'theme_skeleton' => $this->container->getParameter('althemes.theme_skeleton_template'),
                                                                                         'stylesheets' => $stylesheets,
                                                                                         'values' => $values,
                                                                                         'is_windows' => $isWindows,
                                                                                         'valum' => $valumOptionsBuilder->getOptions()));
    }
    
    public function activateThemeAction($themeName, $activeLanguage = null, $activePage = null)
    {
        try
        {
            $themeManager = new AlThemeManager($this->container, $this->locateThemesFolder(), $this->locateThemesFolder());
            $themeManager->activate($themeName);

            $language = AlLanguageQuery::create()->findPk($activeLanguage);
            $languageName = (null !== $language) ? $language->getLanguage() : 'en';
            
            $page = AlPageQuery::create()->findPk($activePage);
            $pageName = (null !== $page) ? $page->getPageName() : 'index';
            
            $this->installAssetsAction();
            
            return new RedirectResponse($this->generateUrl('_navigation', array('_locale' => $languageName, 'page' => $pageName)));
        }
        catch(Exception $e)
        {
            throw new NotFoundHttpException($e->getMessage());
        }
    }
    
    public function showThemeFixerAction()
    {
        /**/
        $templates = array();
        $request = $this->getRequest();
        $finder = $this->retrieveTemplates($request->get('themeName'));
        foreach($finder as $templateFile)
        {
            $templates[] = preg_replace_callback('/([\w]+Bundle)([\w]+)(Slots.php)/', function($matches) { return strtolower($matches[2]); }, $templateFile->getFileName());
        }
        
        $pages = AlPageQuery::create('a')->
                    //where('a.TemplateName NOT IN (\'' . implode('\', \'', $templates) . '\')')->
                    where('a.Id > 1') ->
                    filterByToDelete(0)->
                    orderByPageName()->
                    find();
        
        return $this->render('AlphaLemonCmsBundle:Themes:show_theme_fixer.html.twig', array('templates' => $templates, 'pages' => $pages, 'themeName' => $request->get('themeName')));
    }
    
    public function fixThemeAction()
    {
        $request = $this->getRequest();
        
        $params = array();
        $data = explode('&', $request->get('data'));
        foreach($data as $value) {
            $tmp = preg_split('/=/', $value);
            if($tmp[0] == 'al_page_to_fix') {
                $params[$tmp[0]][] = $tmp[1];
            }
            else {
                $params[$tmp[0]] = $tmp[1];
            }
        }
        
        if(empty($params['al_page_to_fix'])) {
            $response = new Response();
            $response->setStatusCode('404');
            return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => 'Any page has been choosen'), $response);
        }
        
        foreach($params['al_page_to_fix'] as $pageId) {
            $alPage = AlPageQuery::create()->findPk($pageId);            
            $pageManager = $this->container->get('al_page_manager');
            $pageManager->set($alPage);
            if(!$pageManager->save(array('template' => $params['al_template'])))
            {
                $response = new Response();
                $response->setStatusCode('404');
                return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => 'Err'), $response);
            }
        }
        
        $response = new Response(json_encode($params['al_page_to_fix']));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
    private function retrieveTemplates($themeName)
    {
        $themesFolder = $this->locateThemesFolder();
        
        $finder = new Finder();
        $templatesDir = sprintf('%s/%s/Core/Slots', $themesFolder, $themeName);
        $finder->files()->depth(0)->name('*Slots.php')->in($templatesDir);
        
        return $finder; 
    }
}

