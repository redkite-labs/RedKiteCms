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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;
use ThemeEngineCore\Model\AlThemeQuery;
use ThemeEngineCore\Manager\Theme\AlThemeManager;
use ThemeEngineCore\Event\PageRenderer\BeforePageRenderingEvent;
use ThemeEngineCore\Event\PageRendererEvents;

class TemplateController extends Controller
{   
    /**
     * Sets up the page tree object which contains all the information about the webpage
     * @param   string  $templateName
     * @param   string  $dictionary 
     */
    
    protected function setUpPageTree($templateName, $dictionary = null)
    {
        $request = $this->container->get('request');  
        $pageTree = $this->container->get('al_page_tree');
        
        $theme = AlThemeQuery::create()->filterByActive(1)->findOne();
        if(null !== $theme)
        {
            $pageTree->setThemeName($theme->getThemeName());
            $pageTree->setTemplateName($templateName);       
            $templateStylesheets = sprintf('themes.%s_%s.stylesheets', preg_replace('/bundle$/', '', strtolower($theme->getThemeName())), strtolower($templateName));
            if($this->container->hasParameter($templateStylesheets)) $pageTree->addStylesheets($this->container->getParameter($templateStylesheets));
            $templateJavascripts = sprintf('themes.%s_%s.javascripts', preg_replace('/bundle$/', '', strtolower($theme->getThemeName())), strtolower($templateName));
            if($this->container->hasParameter($templateJavascripts)) $pageTree->addJavascripts($this->container->getParameter($templateJavascripts));

            $slotContentsPath = $this->container->getParameter('kernel.root_dir') . '/../' . $this->container->getParameter('althemes.slot_contents_dir');
            if(!is_dir($slotContentsPath))
            {
                throw new \InvalidArgumentException(sprintf('The directory %s does not exist. Please check that the slot_contents_dir is properly configured', $slotContentsPath));
            }
            
            $locator = new FileLocator($slotContentsPath);
            $locator->locate('slotContents.yml');
            $slots = Yaml::parse($locator->locate('slotContents.yml'));
            
            $locale = ($request->attributes->get('_locale') != '') ? $request->attributes->get('_locale') : "it"; 
            foreach($slots as $slotContents)
            {
                foreach($slotContents as $slotName => $contents)
                {
                    if(null !== $dictionary && strtolower($pageTree->getSlot($slotName)->getRepeated()) != 'site')
                    {
                        foreach($contents as $content)
                        {
                            $content = $this->container->get('translator')->trans($content, array(), $dictionary, $locale); 
                            $pageTree->addContent($slotName, array('HtmlContent' => $content)); 
                        }
                    }
                    else
                    {
                        foreach($contents as $content)
                        {
                            $pageTree->addContent($slotName, array('HtmlContent' => $content)); 
                        }
                    }
                }
            }

            $dispatcher = $this->container->get('event_dispatcher');            
            $event = new BeforePageRenderingEvent($this->container->get('request'), $pageTree);
            $dispatcher->dispatch(PageRendererEvents::BEFORE_RENDER_PAGE, $event);  
            $pageTree = $event->getPageTree();
        }
        else
        {
            throw new \Exception("Any theme has been loaded. Please load at least one theme to use ThemeEngine, at /en/al_showThemes, or disable it from your appKernel configuration file if you don't want to use it.");
        }
    }
}

