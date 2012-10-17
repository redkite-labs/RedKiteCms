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
    protected $blocksFactory;

    public function previewThemeAction($languageName, $pageName, $themeName, $templateName)
    {
        //$request = $this->container->get('request');
        $this->kernel = $this->container->get('kernel');
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
            'theme_name' => $themeName,
            'template_name' => $templateName,
            'available_languages' => $this->container->getParameter('alpha_lemon_cms.available_languages'),
            'base_template' => $this->container->getParameter('alpha_lemon_theme_engine.base_template'),
            'internal_stylesheets' => $this->pageTree->getInternalStylesheets(),
            'internal_javascripts' => $this->pageTree->getInternalJavascripts(),
            'templateStylesheets' => $this->pageTree->getExternalStylesheets(),
            'templateJavascripts' => $this->fixAssets($this->pageTree->getExternalJavascripts()),
            'templates' => $theme->getTemplates(),
            'frontController' => $this->getFrontcontroller(),
        );

        $params = array_merge($params, $this->loadActiveTheme($languageName));

        return $this->render('AlphaLemonCmsBundle:Preview:index.html.twig', $params);
    }

    public function saveActiveThemeAction()
    {
        $request = $this->container->get('request');

        $activeTheme = $this->container->get('alpha_lemon_theme_engine.active_theme');
        $pageManager = $this->container->get('alpha_lemon_cms.page_manager');
        $factoryRepository = $this->container->get('alpha_lemon_cms.factory_repository');
        $pagesRepository = $factoryRepository->createRepository('Page');
        $blocksRepository = $factoryRepository->createRepository('Block');

        $error = "";
        $data = array();
        parse_str($request->get('data'), $data);//print_R($data);exit;
        $pagesRepository->startTransaction();
        foreach($data['templates'] as $template)
        {
            if ( ! isset($template['slots']) || ($template['slots']) == 0) {
                break;
            }

            $pages = $pagesRepository->fromTemplateName($template["old_template"]);

            foreach ($pages as $page) {
                $pageManager->set($page);
                if (false === $pageManager->save(array('TemplateName' => $template["new_template"]))) {
                    $error = sprintf('An error occoured when saving the template "%s" for the page "%s". Operation aborted', $template, $page->getPageName());
                    break;
                }

                foreach($template['slots'] as $slot)
                {
                    $prevSlotName = str_replace('al_slot_' . $template["old_template"] . '_', '', $slot['slot_placeholder']);
                    $newSlotName = str_replace('al_map_', '', $slot['slot']);
                    if ($prevSlotName != $newSlotName) {
                        $blocks = $blocksRepository->retrieveContents(null, array(1, $page->getId()), $prevSlotName);
                        foreach($blocks as $block)
                        {
                            $blocksFactory = $this->container->get('alpha_lemon_cms.block_manager_factory');
                            $blockManager = $blocksFactory->createBlockManager($block);
                            if (false === $blockManager->save(array('SlotName' => $newSlotName))) {
                                $error = sprintf('An error occoured when changing the slot "%s" to the new one "%s" on the template "%s" for the page "%s". Operation aborted', $newSlotName, $prevSlotName, $template, $page->getPageName());
                                break;
                            }
                        }
                    }

                    if ( ! empty($error)) {
                        break;
                    }
                }
                if ( ! empty($error)) {
                    break;
                }
            }

        }



        if (empty($error)) {
            $pagesRepository->commit();
        }
        else {
            $pagesRepository->rollback();
        }

        $response = new Response();

        return $this->container->get('templating')->renderResponse('AlphaLemonCmsBundle:Dialog:dialog.html.twig', array('message' => $error), $response);
    }

    public function loadActiveTheme($languageName)
    {
        //$request = $this->container->get('request');
        //$languageName = $request->get('language');

        $factoryRepository = $this->container->get('alpha_lemon_cms.factory_repository');
        $blocksRepository = $factoryRepository->createRepository('Block');
        $languagesRepository = $factoryRepository->createRepository('Language');
        $pagesRepository = $factoryRepository->createRepository('Page');

        $language = $languagesRepository->fromLanguageName($languageName);
        $languageId = $language->getId();

        $templates = array();
        $blockManagers = array();
        $blocksFactory = $this->container->get('alpha_lemon_cms.block_manager_factory');
        $activeTheme = $this->container->get('alpha_lemon_theme_engine.active_theme');
        $themes = $this->container->get('alpha_lemon_theme_engine.themes');
        $theme = $themes->getTheme($activeTheme->getActiveTheme());
        foreach ($theme->getTemplates() as $template) {
            $templateName = $template->getTemplateName();
            $page = $pagesRepository->fromTemplateName($templateName, true);
            $blocks = $blocksRepository->retrieveContents(array(1, $languageId), array(1, $page->getId()));
            $templates[$templateName] = array();
            foreach($blocks as $block) {
                $slotName = $block->getSlotName();
                $blockManager = $blocksFactory->createBlockManager($block);
                $key = $templateName . '_' . $slotName;
                $blockManagers[$key] = $blockManager;
                $templates[$templateName][] = $slotName;
            }
        }

        return array(
            'active_theme_templates' => $templates,
            'block_managers' => $blockManagers,
        );


//$this->render('AlphaLemonCmsBundle:Preview:active_theme.html.twig', array('templates' => $templates, 'block_managers' => $blockManagers));
    }

    protected function fetchSlotContents($template)
    {
        $blocksFactory = $this->container->get('alpha_lemon_cms.block_manager_factory');
        $slots = $template->getSlots();
        $slotContents = array();
        foreach ($slots as $slot) {
            $blockType = $slot->getBlockType();
            $blockManager = $blocksFactory->createBlockManager($blockType);

            // TODO
            $block = new \AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock();
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
