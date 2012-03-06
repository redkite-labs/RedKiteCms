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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;

use AlRequestCore\PageTree\AlPageTree;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlContentQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageAttributeQuery;
use AlphaLemon\ThemeEngineBundle\Core\Model\AlThemeQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlLanguageQuery;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;

use AlphaLemon\AlphaLemonCmsBundle\Core\Assetic\AlAsseticDynamicFileManager\AlAsseticDynamicFileManagerJs;
use AlphaLemon\AlphaLemonCmsBundle\Core\Assetic\AlAsseticDynamicFileManager\AlAsseticDynamicFileManagerCss;


use AlphaLemon\ThemeEngineBundle\Core\Event\PageRenderer\BeforePageRenderingEvent;
use AlphaLemon\ThemeEngineBundle\Core\Event\PageRendererEvents;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Implements the controller to load AlphaLemon CMS
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class AlCmsController extends Controller
{
    public function showAction()
    {
        $request = $this->container->get('request'); 
        $pageTree = $this->container->get('al_page_tree');
        $isSecure = (null !== $this->get('security.context')->getToken()) ? true : false;
        $skin = AlToolkit::retrieveBundleWebFolder($this->container, 'AlphaLemonCmsBundle') . '/css/skins/' . $this->container->getParameter('alcms.skin');
        if(null !== $pageTree)
        {
            $kernel = $this->container->get('kernel');
            $frontController = sprintf('/%s.php/', $kernel->getEnvironment());
            
            $dispatcher = $this->container->get('event_dispatcher');
                        
            $event = new BeforePageRenderingEvent($this->container->get('request'), $pageTree);
            $dispatcher->dispatch(PageRendererEvents::BEFORE_RENDER_PAGE, $event);  
            $pageTree = $event->getPageTree();
            
            $eventName = sprintf('page_renderer.before_%s_rendering', $request->attributes->get('_locale'));             
            $dispatcher->dispatch($eventName, $event);
            $pageTree = $event->getPageTree();
            
            $eventName = sprintf('page_renderer.before_%s_rendering', $request->get('page'));             
            $dispatcher->dispatch($eventName, $event);
            $pageTree = $event->getPageTree();
            
            if($pageTree != $event->getPageTree())
            {
                $pageTree = $event->getPageTree();
            }
            
            $dynamicStylesheets = $this->locateAssets($pageTree->getExternalStylesheets());
            $dynamicJavascripts = $this->locateAssets($pageTree->getExternalJavascripts());
                        
            $template = 'AlphaLemonCmsBundle:Cms:welcome.html.twig';
            if($pageTree->getThemeName() != "" && $pageTree->getTemplateName() != "")
            {
                $kernelPath = $this->container->getParameter('kernel.root_dir');
                $template = (is_file(sprintf('%s/Resources/views/%s/%s.html.twig', $kernelPath, $pageTree->getThemeName(), $pageTree->getTemplateName()))) ? sprintf('::%s/%s.html.twig', $pageTree->getThemeName(), $pageTree->getTemplateName()) : sprintf('%s:Theme:%s.html.twig', $pageTree->getThemeName(), $pageTree->getTemplateName());
            }
            
            $themesDir = AlToolkit::locateResource($this->container, '@AlphaLemonThemeEngineBundle')  . $this->container->getParameter('althemes.base_dir');
            if(!is_file($themesDir . '/' . $pageTree->getThemeName() .'/Resources/views/Theme/' . $pageTree->getTemplateName() . '.html.twig'))
            {
                $this->get('session')->setFlash('message', 'The template assigned to this page does not exist. This appens when you change a theme with a different number of templates from the active one. To fix this issue you shoud activate the previous theme again and change the pages which cannot be rendered by this theme');
                $template = 'AlphaLemonCmsBundle:Cms:welcome.html.twig';
            }
            
            
           
            $languageId = (null != $pageTree->getAlLanguage()) ? $pageTree->getAlLanguage()->getId() : 0;
            $pageId = (null != $pageTree->getAlPage()) ? $pageTree->getAlPage()->getId() : 0;
            
            return $this->render('AlphaLemonCmsBundle:Cms:index.html.twig', array(
                                'metatitle' => $pageTree->getMetatitle(),
                                'metadescription' => $pageTree->getMetaDescription(),
                                'metakeywords' => $pageTree->getMetaKeywords(),
                                'internal_stylesheets' => $pageTree->getInternalStylesheet(),
                                'internal_javascripts' => '',
                                'values' => $pageTree->getContents(),
                                'template' => $template,
                                'page' => $pageId,
                                'language' => $languageId,
                                'available_contents' => $this->container->getParameter('al_cms.page_blocks'),
                                'skin_path' => $skin,
                                'pages' => ChoiceValues::getPages($this->container),
                                'languages' => ChoiceValues::getLanguages($this->container),
                                'available_languages' => $this->container->getParameter('alcms.available_languages'),
                                'base_template' => $this->container->getParameter('althemes.base_template'),
                                'frontController' => $frontController,
                                'dynamicStylesheets' => $dynamicStylesheets,
                                'dynamicJavascripts' => $dynamicJavascripts,
                                'is_secure' => $isSecure,
                                ));
        } 
        else
        {
            return $this->render('AlphaLemonCmsBundle:Cms:index.html.twig', array(
                                'internal_stylesheets' => $pageTree->getInternalStylesheet(),
                                'internal_javascripts' => $pageTree->getInternalJavascript(),
                                'values' => $pageTree->getContents(),
                                'template' => $template,
                                'page' => $pageTree->getAlPage()->getId(),
                                'language' => $languageId,
                                'available_contents' => $this->container->getParameter('al_cms.page_blocks'),
                                'skin_path' => $skin,
                                'pages' => ChoiceValues::getPages($this->container),
                                'languages' => ChoiceValues::getLanguages($this->container),
                                'available_languages' => $this->container->getParameter('alcms.available_languages'),
                                'base_template' => $this->container->getParameter('althemes.base_template'),
                                'frontController' => $frontController,
                                'is_secure' => $isSecure,
                                ));
        }
    }
	
    private function locateAssets(array $assets)
    {
        $located = array();
        foreach($assets as $asset)
        {
            $filename = basename($asset);     
            $currentAsset = $asset;

            // Checks if the assets is given with a relative path 
            if(false !== strpos($currentAsset, 'bundles') || false !== strpos($currentAsset, '@'))
            {    
                preg_match('/^@([\w]+Bundle)\/Resources\/public\/([\w\/\.]+)/', $currentAsset, $match);
                if(!empty($match))
                {
                        $currentAsset = AlToolkit::retrieveBundleWebFolder($this->container, $match[1]) . '/' . $match[2];
                }

                $currentAsset = AlToolkit::normalizePath($currentAsset);
                $located[] =  $currentAsset;
            }
        }

        return $located;
    }
}

