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
use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTreePreview;
use AlphaLemon\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocks;
use AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate;

class ThemePreviewController extends AlCmsController
{
    protected $pageTree;
    protected $blocksFactory;

    public function previewThemeAction($languageName, $pageName, $themeName, $templateName)
    {
        $this->kernel = $this->container->get('kernel');
        $themes = $this->container->get('alpha_lemon_theme_engine.themes');
        $theme = $themes->getTheme($themeName);

        // Any templateName is specified
        $template = ($templateName == 'none') ? $theme->getHomeTemplate() : $theme->getTemplate($templateName);

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
        $baseParams = array(
            'template' => $twigTemplate,
            'skin_path' => $this->getSkin(),
            'theme_name' => $themeName,
            'template_name' => $template->getTemplateName(),
            'available_languages' => $this->container->getParameter('alpha_lemon_cms.available_languages'),
            'base_template' => $this->container->getParameter('alpha_lemon_theme_engine.base_template'),
            'internal_stylesheets' => $this->pageTree->getInternalStylesheets(),
            'internal_javascripts' => $this->pageTree->getInternalJavascripts(),
            'templateStylesheets' => $this->pageTree->getExternalStylesheets(),
            'templateJavascripts' => $this->fixAssets($this->pageTree->getExternalJavascripts()),
            'templates' => $theme->getTemplates(),
            'frontController' => $this->getFrontcontroller(),
        );

        $params = array_merge($baseParams, $this->renderActiveThemePanel($languageName));

        return $this->render('AlphaLemonCmsBundle:Preview:index.html.twig', $params);
    }

    public function saveActiveThemeAction()
    {
        $request = $this->container->get('request');

        $pageManager = $this->container->get('alpha_lemon_cms.page_manager');
        $factoryRepository = $this->container->get('alpha_lemon_cms.factory_repository');
        $pagesRepository = $factoryRepository->createRepository('Page');
        
        $message = "";
        $repeatedSlots = array();
        $data = array();
        parse_str($request->get('data'), $data); 
        if (array_key_exists('templates', $data)) {
            $pagesRepository->startTransaction();
            foreach($data['templates'] as $template)
            {
                if ( ! isset($template['slots']) || count($template['slots']) == 0) {
                    continue;
                }

                if ($template["old_template"] == 'repeated_slots') {
                    $repeatedSlots = $template["slots"];

                    continue;
                }
                
                $pages = $pagesRepository->fromTemplateName($template["old_template"]);
                foreach ($pages as $page) {
                    if ($template["new_template"] != $template["old_template"]) {
                        $pageManager->set($page);
                        if (false === $pageManager->save(array('TemplateName' => $template["new_template"]))) {
                            $message = sprintf('An error occoured when saving the template "%s" for the page "%s". Operation aborted', $template["new_template"], $page->getPageName());
                            break;
                        }
                    }

                    if ( ! $this->changeBlockSlots($template["old_template"], $template['slots'], $page->getId(), $factoryRepository))
                    {
                        $message = sprintf('An error occoured when changing a slot on the template "%s". Operation aborted', $template["new_template"], $page->getPageName());
                        break;
                    }
                }
            }
            
            if (count($repeatedSlots) > 0 && ! $this->changeBlockSlots('repeated_slots', $repeatedSlots, 1, $factoryRepository))
            {
                $message = sprintf('An error occoured when changing a repeated slot on the template "%s". Operation aborted', $template["new_template"]);
            }
            
            if (empty($message)) {
                $pagesRepository->commit();

                $activeTheme = $this->container->get('alpha_lemon_theme_engine.active_theme');
                $activeTheme->writeActiveTheme($data['theme']);

                $message = 'The new theme has been activated';
            }
            else {
                $pagesRepository->rollback();
            }
        }
        else {
            $message = 'No mapping has been created. Operation aborted';
        }
        
        $response = new Response();

        return $this->container->get('templating')->renderResponse('AlphaLemonCmsBundle:Dialog:dialog.html.twig', array('message' => $message), $response);
    }

    protected function changeBlockSlots($template, $slots, $pageId, $factoryRepository)
    {
        $result = true;
        $blocksFactory = $this->container->get('alpha_lemon_cms.block_manager_factory');
        $blocksRepository = $factoryRepository->createRepository('Block');
        foreach($slots as $slot)
        {
            $prevSlotName = str_replace('al_slot_' . $template . '_', '', $slot['slot_placeholder']);
            $newSlotName = str_replace('al_map_', '', $slot['slot']);
            if ($prevSlotName != $newSlotName) {
                $blocks = $blocksRepository->retrieveContents(null, $pageId, $prevSlotName);
                foreach($blocks as $block)
                {
                    $blockManager = $blocksFactory->createBlockManager($block);
                    if (false === $blockManager->save(array('SlotName' => $newSlotName))) {
                        $result = false;
                        break;
                    }
                }
            }

            if ( ! $result) {
                break;
            }
        }

        return $result;
    }

    protected function renderActiveThemePanel($languageName)
    {
        $factoryRepository = $this->container->get('alpha_lemon_cms.factory_repository');
        $blocksRepository = $factoryRepository->createRepository('Block');
        $languagesRepository = $factoryRepository->createRepository('Language');
        $pagesRepository = $factoryRepository->createRepository('Page');

        $language = $languagesRepository->fromLanguageName($languageName);
        $languageId = $language->getId();

        $newThemeTemplates = array();
        $newBlockManagers = array();
        $activeTheme = $this->container->get('alpha_lemon_theme_engine.active_theme');
        $themes = $this->container->get('alpha_lemon_theme_engine.themes');
        $theme = $themes->getTheme($activeTheme->getActiveTheme());
        foreach ($theme->getTemplates() as $template) {
            $templateName = $template->getTemplateName();
            $page = $pagesRepository->fromTemplateName($templateName, true);
            if (null !== $page) {
                $blocks = $blocksRepository->retrieveContents($languageId, $page->getId());
                $values = $this->fetchActiveThemeElements($templateName, $blocks);
                $newThemeTemplates = array_merge($newThemeTemplates, $values['templates']);
                $newBlockManagers = array_merge($newBlockManagers, $values['block_managers']);
            }
        }

        $blocks = $blocksRepository->retrieveContents(null, 1);
        $values = $this->fetchActiveThemeElements('repeated_slots', $blocks);
        $templates = array_merge($newThemeTemplates, $values['templates']);
        $blockManagers = array_merge($newBlockManagers, $values['block_managers']);

        return array(
            'active_theme_templates' => $templates,
            'block_managers' => $blockManagers,
        );
    }

    protected function fetchActiveThemeElements($templateName, $blocks)
    {
        $blocksFactory = $this->container->get('alpha_lemon_cms.block_manager_factory');
        $blockManagers = array();
        $templates = array();
        $templates[$templateName] = array();

        // Aggregates the blocks by slot name
        $blocksAggregateBySlotName = array();
        foreach ($blocks as $block) {
            $blocksAggregateBySlotName[$block->getSlotName()][] = $block;
        }

        foreach($blocksAggregateBySlotName as $slotName => $blocks) {
            $block = clone($blocks[0]);
            $content = implode("", array_map(function($b){ return $b->getContent(); }, $blocks));
            if (preg_match('/\<script/s', $content)) {
                $content = "This block contains a script block and it is not renderable in preview mode";
            }
            $block->setContent($content);

            $blockManager = $blocksFactory->createBlockManager($block);
            $key = $templateName . '_' . $slotName;
            $blockManagers[$key] = $blockManager;
            $templates[$templateName][] = $slotName;
        }

        return array(
            'templates' => $templates,
            'block_managers' => $blockManagers,
        );
    }

    protected function fetchSlotContents(AlTemplate $template)
    {
        $blocksFactory = $this->container->get('alpha_lemon_cms.block_manager_factory');
        $factoryRepository = $this->container->get('alpha_lemon_cms.factory_repository');
        $blocksRepository = $factoryRepository->createRepository('Block');
        $slots = $template->getSlots();
        $slotContents = array();
        foreach ($slots as $slot) {
            $blockType = $slot->getBlockType();
            $blockManager = $blocksFactory->createBlockManager($blockType);

            $blockClass = $blocksRepository->getRepositoryObjectClassName();
            $block = new $blockClass();
            $block->setType($blockType);
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