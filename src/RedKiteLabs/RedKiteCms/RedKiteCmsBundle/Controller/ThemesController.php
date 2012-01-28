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
            
            return new RedirectResponse($this->generateUrl('_navigation', array('_locale' => $languageName, 'page' => $pageName)));
        }
        catch(Exception $e)
        {
            throw new NotFoundHttpException($e->getMessage());
        }
    }
    
    /*
    public function installAssetsAction()
    {
        $url = $this->generateUrl('_themes');
        
        AlToolkit::executeCommand($this->container->get('kernel'), 'assets:install ' . AlToolkit::normalizePath($this->container->getParameter('kernel.root_dir') . '/../web'));
        $this->removeCache();
        
        $response = new Response();
        return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => 'Ok'), $response);
        
        return $this->redirect($url);
    }*/
}

