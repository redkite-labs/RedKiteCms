<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * (c) Since 2011 AlphaLemon
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

use ThemeEngineCore\Model\AlThemeQuery;
use ThemeEngineCore\ThemeManager\AlThemeManager;
use PageTreeCore\Tools\AlToolkit;

use AlValumUploaderCore\Options\AlValumUploaderOptionsBuilder;

class ThemesController extends Controller
{   
    public function showAction()
    {
        $values = $this->retrieveThemeValues();        
        $customOptions = array("panel_title" => "Upload new theme",
                               "panel_info" => "",
                               "allowed_extensions" => "'zip'",
                               "upload_action" => "al_uploadFile",
                               "folder" => $this->locateThemesFolder(),
                               "onComplete" => "location.href = '" . $this->generateUrl('_extract_themes') . "'"); //  "extractTheme(fileName);"
        
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

            return $this->render('AlphaLemonThemeEngineBundle:Themes:theme_panel_sections.html.twig', array('values' => $this->retrieveThemeValues()));
        }
        catch(Exception $e)
        {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    public function extractThemesAction()
    {
        $request = $this->get('request');
               
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
                    $themeManager->add(array('name' => basename($theme->getFileName(), '.zip')));
                }
                catch(\RuntimeException $ex)
                {
                    //Silently catches the exception thrown when a theme that altready exists
                }
                unlink($theme);
            }
        }
        
        if(!$request->isXmlHttpRequest())
        {
            if(count($themes) > 0)
            {
                return $this->redirect ($this->generateUrl('_install_assets'));
            }
            else 
            {
                return $this->redirect ($this->generateUrl('_themes'));
            }
        }
        
        return $this->render('AlphaLemonThemeEngineBundle:Themes:theme_panel_sections.html.twig', array('values' => $this->retrieveThemeValues()));
    }
    
    public function installAssetsAction()
    {
        $url = $this->generateUrl('_themes');
        
        AlToolkit::executeCommand($this->container->get('kernel'), 'assets:install ' . AlToolkit::normalizePath($this->container->getParameter('kernel.root_dir') . '/../web'));
        $this->removeCache();
        
        return $this->redirect ($url);
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

        return $this->render('AlphaLemonThemeEngineBundle:Themes:theme_panel_sections.html.twig', array('values' => $this->retrieveThemeValues()));
    }
    
    public function removeThemeAction()
    {
        try
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
            
            if(!$request->isXmlHttpRequest())
            {   
                return $this->redirect($this->generateUrl('_install_assets'));
            }

            return $this->render('AlphaLemonThemeEngineBundle:Themes:theme_panel_sections.html.twig', array('values' => $this->retrieveThemeValues()));
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
        $theme = AlThemeQuery::create()->filterByActive(1)->findOne();
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
            $theme = AlThemeQuery::create()->filterByActive(1)->findOne();
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
                $buttons = (null !== AlThemeQuery::create()->filterByThemeName($themeName)->findOne()) ? 1 : 0;
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
        $fileName = \sprintf('%s/%s/data/info.yml', $dirName, $themeName);
        
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
            return AlToolkit::locateResource($this->container, '@AlphaLemonThemeEngineBundle/Tests/Themes');
        }
        else
        {
            $themesDir = AlToolkit::locateResource($this->container, '@AlphaLemonThemeEngineBundle')  . '/' . $this->container->getParameter('althemes.base_dir');
            if(!is_dir($themesDir))
            {
                mkdir ($themesDir);
            }
            
            if(!is_writable($themesDir))
            {
                throw new \RuntimeException(sprintf('%s folder is not writable. Please check the permissions on that directory', $themesDir));
            }
            
            return $themesDir;
        }
    }
}

