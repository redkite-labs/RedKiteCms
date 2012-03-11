<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 * 
 */

namespace AlphaLemon\ThemeEngineBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Util\Filesystem;
use AlphaLemon\CmsBundle\Controller\CmsController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Response;

use AlphaLemon\ThemeEngineBundle\Core\Model\AlThemeQuery;
use AlphaLemon\ThemeEngineBundle\Core\ThemeManager\AlThemeManager;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use Symfony\Component\Config\FileLocator;

use AlphaLemon\AlValumUploaderBundle\Core\Options\AlValumUploaderOptionsBuilder;

class ThemesController extends Controller
{   
    public function showAction()
    {
        $values = $this->retrieveThemeValues();        
        $customOptions = array("panel_title" => "Themes uploader",
                               "panel_info" => "",
                               "allowed_extensions" => "'zip'",
                               "upload_action" => "al_uploadFile",
                               "folder" => $this->locateThemesFolder(),
                               "onComplete" => "location.href = '" . $this->generateUrl('_extract_themes') . "'"); 
        $valumOptionsBuilder = new AlValumUploaderOptionsBuilder($this->container);
        $valumOptionsBuilder->build($customOptions);
        
        $stylesheets = array();
        foreach($this->container->getParameter('althemes.stylesheets') as $stylesheet)
        {
            $stylesheets[] = AlToolkit::retrieveBundleWebFolder($this->container, 'AlphaLemonThemeEngineBundle') . '/' . $stylesheet;
        }
        
        return $this->render($this->container->getParameter('althemes.base_theme_manager_template'), array('base_template' => $this->container->getParameter('althemes.base_template'),
                                                                                         'panel_sections' => $this->container->getParameter('althemes.panel_sections_template'),
                                                                                         'theme_skeleton' => $this->container->getParameter('althemes.theme_skeleton_template'),
                                                                                         'stylesheets' => $stylesheets,
                                                                                         'values' => $values,
                                                                                         'valum' => $valumOptionsBuilder->getOptions()));
    }

    public function activateThemeAction($themeName)
    {
        try
        {
            $themeManager = new AlThemeManager($this->container, $this->locateThemesFolder(), $this->locateThemesFolder());
            $themeManager->activate($themeName);
            
            $request = $this->get('request');
            if(!$request->isXmlHttpRequest())
            {
                return $this->redirect ($this->generateUrl('_themes'));
            }

            return $this->render('AlphaLemonThemeEngineBundle:Themes:theme_panel_sections.html.twig', array('theme_skeleton' => $this->container->getParameter('althemes.theme_skeleton_template'), 'values' => $this->retrieveThemeValues()));
        }
        catch(Exception $e)
        {
            throw new NotFoundHttpException($e->getMessage());
        }
    }
    
    public function extractThemesAction()
    {
        $this->extractTheme();
        
        return $this->render('AlphaLemonCmsBundle:Themes:theme_panel_sections.html.twig', array('theme_skeleton' => $this->container->getParameter('althemes.theme_skeleton_template'), 
                                                                                                        'values' => $this->retrieveThemeValues(),
                                                                                                        'valum' => $valumOptionsBuilder->getOptions()));
    }
    
    protected function extractTheme()
    {
        $request = $this->get('request');
               
        $processedThemes = array();
        $themeManager = new AlThemeManager($this->container);
        $themesBaseFolder = $this->locateThemesFolder(); 
        $finder = new Finder();
        $themes = $finder->depth(0)->files()->name('*.zip')->in($themesBaseFolder);   
        foreach($themes as $theme)
        {
            if (AlToolkit::extractZipFile($theme, $themesBaseFolder))
            {
                try
                {
                    $themeName = basename($theme->getFileName(), '.zip');
                    $themeManager->add(array('name' => $themeName));
                    $processedThemes[] = $themeName;
                }
                catch(\RuntimeException $ex)
                {
                    //Silently catches the exception thrown when a theme already exists
                }
                unlink($theme);
            }
        }
        
        if(count($processedThemes) > 0)
        {
            $fs = new Filesystem();
            foreach($processedThemes as $themeName)
            {
                $sourceDir = $themesBaseFolder . '/' . $themeName . '/Resources/public';
                $targetDir = $this->container->getParameter('kernel.root_dir') . '/../web/bundles/' . preg_replace('/bundle$/', '', strtolower($themeName));
                if(in_array(strtolower(PHP_OS), array('unix', 'linux'))) 
                {
                    $fs->symlink($sourceDir, $targetDir);
                }
                else 
                {
                    $fs->mirror($sourceDir, $targetDir);
                }
            }
            $this->removeCache();
        }
    }
    
    public function importThemeAction()
    {
        $request = $this->get('request');

        $themeManager = new AlThemeManager($this->container);
        $themeManager->add(array('name' => $request->get('themeName')));
        
        if(!$request->isXmlHttpRequest())
        {
            return $this->redirect ($this->generateUrl('_themes'));
        }
        
        return $this->render('AlphaLemonThemeEngineBundle:Themes:theme_panel_sections.html.twig', array('theme_skeleton' => $this->container->getParameter('althemes.theme_skeleton_template'), 'values' => $this->retrieveThemeValues()));
    }
    
    protected function removeTheme()
    {
        $request = $this->get('request');

        $themeManager = new AlThemeManager($this->container);
        $themeManager->remove($request->get('themeName'));

        $fs = new Filesystem(); 
        $fs->remove($this->locateThemesFolder() . "/" . $request->get('themeName')); 
        if (!in_array($this->container->get('kernel')->getEnvironment(), array('test')))
        {
            $this->removeCache();
        }
    }
    
    public function removeThemeAction()
    {
        try
        {
            $this->removeTheme();
            
            return $this->render('AlphaLemonThemeEngineBundle:Themes:theme_panel_sections.html.twig', array('theme_skeleton' => $this->container->getParameter('althemes.theme_skeleton_template'), 
                                                                                                        'values' => $this->retrieveThemeValues()));
        }
        catch(Exception $e)
        {
            $response = new Response();
            $response->setStatusCode('404');
            return $this->render('AlphaLemonCmsBundle:Pages:ajax_error.html.twig', array('message' => $e->getMessage()), $response);
        }
    }
   
    protected function removeCache()
    {
        $fs = new Filesystem();
        $fs->remove($this->container->getParameter('kernel.root_dir') . '/cache/' . $this->container->get('kernel')->getEnvironment());
    }
    
    protected function retrieveThemeValues()
    {
        $values = array();
        $values['active_theme'] = $this->retrieveActiveThemeAttributes();
        $values['available_themes'] = $this->retrieveAvailableThemes($values['active_theme']);
        
        return $values;
    }

    protected function retrieveActiveThemeAttributes()
    {
        $theme = AlThemeQuery::create()->activeBackend()->findOne();
        if(null !== $theme)
        {
            $backendValues = $this->retrieveThemeInfo($this->locateThemesFolder(), $theme->getThemeName());
            $backendValues['theme_section_title'] = $this->get('translator')->trans('Active Theme');
            $backendValues['theme_title'] = $theme->getThemeName();
            $fileName = AlToolkit::retrieveBundleWebFolder($this->container, $theme->getThemeName()) . '/images/screenshot.png';
            $backendValues['screenshot'] = is_file($fileName) ? $fileName : AlToolkit::retrieveBundleWebFolder($this->container, 'ThemeEngineBundle') . '/images/screenshot.png';
        }
        else
        {
            $backendValues = array('theme_title' => 'Not available',  'theme_error' => $this->get('translator')->trans('Any theme has been choosen for the backend. Please select one from the available themes in the right panel.'));
        }
        
        return $backendValues;
    }

    protected function retrieveAvailableThemes(array $backendAttributes = null)
    {
        $themesArray = array();
        if(null === $backendAttributes)
        {
            $theme = AlThemeQuery::create()->activeBackend()->findOne();
            $selectedTheme = $theme->getThemeName();
        }
        else
        {
            $selectedTheme = $backendAttributes["theme_title"];
        }

        $themesBaseFolder = $this->locateThemesFolder();
        $finder = new Finder();
        $themesDirectories = $finder->directories()->depth(0)->sortByName()->in($themesBaseFolder);
        if(count($themesDirectories) > 0)
        {
            foreach($themesDirectories as $templateDirectory)
            {
                $themeName = basename($templateDirectory);
                $availableTheme = $this->retrieveThemeInfo($themesBaseFolder, $themeName);

                // Calculates the buttons to display. The possibilities result are:
                //
                //  0: Anything defined
                //  1: The theme has been loaded and it is the active one
                //  2: The theme exists in the themes folder but it has not been loaded
                //  3: The theme has been loaded and it is available
                $buttons = (null !== AlThemeQuery::create()->fromName($themeName)->findOne()) ? 1 : 0;
                $buttons += ($themeName != $selectedTheme) ? 2 : 0;                
                $availableTheme['buttons'] = $buttons;
                $fileName = AlToolkit::retrieveBundleWebFolder($this->container, $themeName) . '/images/screenshot.png';
                $availableTheme['screenshot'] = is_file($fileName) ? $fileName : AlToolkit::retrieveBundleWebFolder($this->container, 'ThemeEngineBundle') . '/images/screenshot.png';
                if(($themeName != $selectedTheme)) $themesArray[] = $availableTheme;
            }
        }
        $availableThemes["themes"] = $themesArray;
        $availableThemes['theme_section_title'] = 'Available Themes';
        
        return $availableThemes;
    }

    protected function retrieveThemeInfo($dirName, $themeName)
    {
        $info = array('theme_title' => $themeName);
        $fileName = \sprintf('%s/%s/Resources/data/info.yml', $dirName, $themeName);
        
        if(file_exists($fileName))
        {
            $t = Yaml::parse($fileName); 
            $info["info"] = \array_intersect_key($t["info"], \array_flip($this->container->getParameter('althemes.info_valid_entries')));
        }

        return $info;
    }

    protected function locateThemesFolder()
    {
        if (in_array($this->container->get('kernel')->getEnvironment(), array('test')))
        {
            // Changes the Themes folder when in test mode
            $themesDir = AlToolkit::locateResource($this->container, '@AlphaLemonThemeEngineBundle/Tests/Themes');
        }
        else
        {
            $themesDir = AlToolkit::locateResource($this->container, '@AlphaLemonThemeEngineBundle')  . $this->container->getParameter('althemes.base_dir');
            if(!is_dir($themesDir))
            {
                mkdir ($themesDir);
            }
            
            if(!is_writable($themesDir))
            {
                throw new \RuntimeException(sprintf('%s folder is not writable. Please check the permissions on that directory', $themesDir));
            }
            
            
        }
		
        return AlToolkit::normalizePath($themesDir);
    }
}

