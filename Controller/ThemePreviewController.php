<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Controller;

use RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTreePreview;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocks;
use RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate;

class ThemePreviewController extends AlCmsController
{
    protected $pageTree;
    protected $themes;
    protected $blocksFactory;
    protected $factoryRepository;
    protected $activeTheme;

    public function previewThemeAction($languageName, $pageName, $themeName, $templateName)
    {
        $this->kernel = $this->container->get('kernel');
        $this->themes = $this->container->get('red_kite_labs_theme_engine.themes');
        $this->factoryRepository = $this->container->get('red_kite_cms.factory_repository');
        $this->blocksFactory = $this->container->get('red_kite_cms.block_manager_factory');
        $this->activeTheme = $this->container->get('red_kite_labs_theme_engine.active_theme');
        $this->blocksRepository = $this->factoryRepository->createRepository('Block');
        
        $theme = $this->themes->getTheme($themeName);
        $template = ($templateName == 'none') ? $theme->getHomeTemplate() : $theme->getTemplate($templateName);
        
        $this->pageTree = new AlPageTreePreview($this->container, $this->factoryRepository);
        $slotContents = $this->fetchSlotContents($template);
        $pageBlocks = new AlPageBlocks($this->factoryRepository);
        $pageBlocks->addRange($slotContents);
        $this->pageTree
            ->setTemplate($template)
            ->setPageBlocks($pageBlocks)
        ;
        $this->container->set('red_kite_cms.page_tree', $this->pageTree);

        $twigTemplate = $this->findTemplate($this->pageTree);
        $baseParams = array(
            'template' => $twigTemplate,
            'skin_path' => $this->getSkin(),
            'theme_name' => $themeName,
            'template_name' => $template->getTemplateName(),
            'available_languages' => $this->container->getParameter('red_kite_cms.available_languages'),
            'base_template' => $this->container->getParameter('red_kite_labs_theme_engine.base_template'),
            'internal_stylesheets' => $this->pageTree->getInternalStylesheets(),
            'internal_javascripts' => $this->pageTree->getInternalJavascripts(),
            'templateStylesheets' => $this->pageTree->getExternalStylesheets(),
            'templateJavascripts' => $this->fixAssets($this->pageTree->getExternalJavascripts()),
            'templates' => array_keys($theme->getTemplates()),
            'frontController' => $this->getFrontcontroller(),
            'enable_yui_compressor' => $this->container->getParameter('red_kite_cms.enable_yui_compressor'),
            'language_name' => $languageName,
            'page_name' => $pageName,
            'configuration' => $this->container->get('red_kite_cms.configuration'),
        );
        
        return $this->render('RedKiteCmsBundle:Preview:index.html.twig', $baseParams);
    }

    protected function fetchSlotContents(AlTemplate $template)
    {
        $slots = $template->getSlots();
        $slotContents = array();
        foreach ($slots as $slot) {
            $slotName = $slot->getSlotName();
            $blockType = $slot->getBlockType();
            $content = $slot->getContent();
            $blockManager = $this->blocksFactory->createBlockManager($blockType);

            $blockClass = $this->blocksRepository->getRepositoryObjectClassName();
            $block = new $blockClass();
            $block->setType($blockType);
            $block->setSlotName($slotName);
            if (null === $content) {
                $defaultValue = $blockManager->getDefaultValue();
                $content = $defaultValue['Content'];
            }

            $block->setContent($content);
            $blockManager->set($block);

            $slotContents[$slotName] = array($block);
            $this->pageTree->addBlockManager($slotName, $blockManager);
        }

        return $slotContents;
    }
}
