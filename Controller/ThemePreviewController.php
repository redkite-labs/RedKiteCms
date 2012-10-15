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
use Symfony\Component\HttpFoundation\Response;
use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTreePreview;
use AlphaLemon\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocks;

class ThemePreviewController extends AlCmsController
{
    protected $pageTree;

    public function previewThemeAction($themeName, $templateName)
    {
        //$request = $this->container->get('request');
        $this->kernel = $this->container->get('kernel');
        //$themeName = $request->getParameter('themeName');
        //$templateName = $request->getParameter('templateName');

        //$themeName = 'BusinessWebsiteThemeBundle';
        //$templateName = 'home';
        
        

        $themes = $this->container->get('alpha_lemon_theme_engine.themes');
        $theme = $themes->getTheme($themeName);
        
        // Any templateName is specified
        if ($templateName == 'none') { 
            
            // Tries to look for the home template
            $templateName = 'home';
            if ( ! $theme->hasTemplate($templateName)) {
                
                // Returns the first one in alphabetic order
                $templates = array_keys($theme->getTemplates());                
                sort($templates);
                $templateName = $templates[0]; 
            }
        }        
        $template = $theme->getTemplate($templateName);

        $this->pageTree = new AlPageTreePreview($this->container, $this->container->get('alpha_lemon_cms.factory_repository'));
        $slotContents = $this->fetchSlotContents($template);
        $pageBlocks = new AlPageBlocks();
        $pageBlocks->addRange($slotContents);

        $this->pageTree
            ->setTemplate($template)
            ->setPageBlocks($pageBlocks)
        ;
        $this->container->set('alpha_lemon_cms.page_tree', $this->pageTree);

        $twigTemplate = $this->findTemplate($this->pageTree);
        $params = array(
            'template' => $twigTemplate,
            'skin_path' => $this->getSkin(),
            'available_languages' => $this->container->getParameter('alpha_lemon_cms.available_languages'),
            'base_template' => $this->container->getParameter('alpha_lemon_theme_engine.base_template'),
            'internal_stylesheets' => $this->pageTree->getInternalStylesheets(),
            'internal_javascripts' => $this->pageTree->getInternalJavascripts(),
            'templateStylesheets' => $this->pageTree->getExternalStylesheets(),
            'templateJavascripts' => $this->fixAssets($this->pageTree->getExternalJavascripts()),
            'templates' => $theme->getTemplates(),
            'frontController' => $this->getFrontcontroller(),
        );

        return $this->render('AlphaLemonCmsBundle:Preview:index.html.twig', $params);
    }

    protected function fetchSlotContents($template)
    {
        $slots = $template->getSlots();
        $slotContents = array();
        $blocksFactory = $this->container->get('alpha_lemon_cms.block_manager_factory');
        foreach ($slots as $slot) {
            $blockManager = $blocksFactory->createBlockManager($slot->getBlockType());

            // TODO
            $block = new \AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock();
            $block->setType($slot->getBlockType());
            $block->setSlotName($slot->getSlotName());
            $content = $slot->getContent();
            $internalJavascript = "";
            $internalStylesheet = "";
            $externalJavascript = "";
            $externalStylesheet = "";
            if (null === $content) {
                $defaultValue = $blockManager->getDefaultValue();
                $content = $defaultValue['Content'];
                if (array_key_exists('InternalJavascript', $defaultValue)) $internalJavascript = $defaultValue['InternalJavascript'];                
                if (array_key_exists('InternalStylesheet', $defaultValue)) $internalStylesheet = $defaultValue['InternalStylesheet'];                
                if (array_key_exists('ExternalJavascript', $defaultValue)) $externalJavascript = $defaultValue['ExternalJavascript'];
                if (array_key_exists('ExternalStylesheet', $defaultValue)) $externalStylesheet = $defaultValue['ExternalStylesheet'];
            }

            $block->setContent($content);
            $block->setInternalJavascript($internalJavascript);            
            $block->setInternalStylesheet($internalStylesheet);            
            $block->setExternalJavascript($externalJavascript);
            $block->setExternalStylesheet($externalStylesheet);
            $blockManager->set($block);

            $slotContents[$slot->getSlotName()] = array($block);
            $this->pageTree->addBlockManager($slot->getSlotName(), $blockManager);
        }

        return $slotContents;
    }
}
