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
use Symfony\Component\DependencyInjection\ContainerAware;

class ThemePreviewController extends AlCmsController
{
    public function previewThemeAction()
    {
        $request = $this->container->get('request');
        $this->kernel = $this->container->get('kernel');
        //$themeName = $request->getParameter('themeName');
        //$templateName = $request->getParameter('templateName');

        $themeName = 'BusinessWebsiteThemeBundle';
        $templateName = 'home';

        $themes = $this->container->get('alpha_lemon_theme_engine.themes');
        $theme = $themes->getTheme($themeName);
        $template = $theme->getTemplate($templateName);
        $slots = $template->getSlots();


        $pageTree = new \AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTreePreview($this->container, $this->container->get('alpha_lemon_cms.factory_repository'));

        $slotContents = array();
        $blocksFactory = $this->container->get('alpha_lemon_cms.block_manager_factory');
        foreach ($slots as $slot) {
            $blockManager = $blocksFactory->createBlockManager($slot->getBlockType());
            $block = new \AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock();
            $block->setType($slot->getBlockType());
            $block->setSlotName($slot->getSlotName());
            $content = $slot->getContent();
            $internalJavascript = "";
            if (null === $content) {
                $defaultValue = $blockManager->getDefaultValue();
                $content = $defaultValue['Content'];
                $internalJavascript = $defaultValue['InternalJavascript'];
            }

            $block->setContent($content);
            $block->setInternalJavascript($internalJavascript);
            $blockManager->set($block);

            $slotContents[$slot->getSlotName()] = array($block);
            $pageTree->addBlockManager($slot->getSlotName(), $blockManager);
        }

        $pageBlocks = new \AlphaLemon\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocks();
        $pageBlocks->addRange($slotContents);

        $pageTree
            ->setTemplate($template)
            ->setPageBlocks($pageBlocks)
        ;
        $this->container->set('alpha_lemon_cms.page_tree', $pageTree);

        $twigTemplate = $this->findTemplate($pageTree);
        $params = array(
            'template' => $twigTemplate,
            'base_template' => $this->container->getParameter('alpha_lemon_theme_engine.base_template'),
            'internal_stylesheets' => $pageTree->getInternalStylesheets(),
            'internal_javascripts' => $pageTree->getInternalJavascripts(),
            'templateStylesheets' => $pageTree->getExternalStylesheets(),
            'templateJavascripts' => $pageTree->getExternalJavascripts(),
        );

        return $this->render('AlphaLemonCmsBundle:Preview:index.html.twig', $params);
    }
}
