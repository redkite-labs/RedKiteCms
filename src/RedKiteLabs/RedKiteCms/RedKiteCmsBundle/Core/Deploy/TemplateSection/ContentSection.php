<?php

/*
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\TemplateSection;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManagerFactoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ViewRenderer\ViewRendererInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\UrlManager\UrlManagerInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree;
use RedKiteLabs\ThemeEngineBundle\Core\Theme\ThemeInterface;

/**
 * ContentSection is the object deputed to generate the content sections of a twig template
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class ContentSection extends TemplateSectionTwig
{
    private $credits;
    private $metatags = array();
    private $contents = array();

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\UrlManager\UrlManagerInterface             $urlManager
     * @param \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ViewRenderer\ViewRendererInterface         $viewRenderer
     * @param \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManagerFactoryInterface $blockManagerFactory
     */
    public function __construct(UrlManagerInterface $urlManager, ViewRendererInterface $viewRenderer, BlockManagerFactoryInterface $blockManagerFactory)
    {
        parent::__construct($urlManager, $viewRenderer);

        $this->blockManagerFactory = $blockManagerFactory;
        $this->viewRenderer = $viewRenderer;
    }

    /**
     * Defines the base method to generate a section
     *
     * @param \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree     $pageTree
     * @param \RedKiteLabs\ThemeEngineBundle\Core\Theme\ThemeInterface $theme
     * @param array                                                      $options
     */
    public function generateSection(PageTree $pageTree, ThemeInterface $theme, array $options)
    {
        $this->contents = array();

        parent::generateSection($pageTree, $theme, $options);

        $this->credits = $options["credits"] == "no" ? true : false;
        $this->parseSlots($options["filter"]);

        return $this->createSections();
    }

    private function createSections()
    {
        $section = $this->writeComment("Contents section");
        foreach ($this->contents as $slotName => $contents) {
            $section .= $this->writeBlock($slotName, $this->writeContent($slotName, implode("\n" . PHP_EOL, $contents)));
        }

        $section .= $this->writeComment("Metatags extra section");
        foreach ($this->metatags as $metatags) {
            $section .= $this->writeBlock('metatags', $metatags, true);
        }

        if ($this->credits) {
            $section .= '{% block internal_header_stylesheets %}' . PHP_EOL;
            $section .= '  {{ parent() }}' . PHP_EOL. PHP_EOL;
            $section .= '  <style>.al-credits{width:100%;background-color:#fff;text-align:center;padding:6px;border-top:1px solid #000;margin-top:1px;}.al-credits a{color:#333;}.al-credits a:hover{color:#C20000;}</style>' . PHP_EOL;
            $section .= '{% endblock %}' . PHP_EOL. PHP_EOL;
            $section .= '{% block body %}' . PHP_EOL;
            $section .= '  {{ parent() }}' . PHP_EOL. PHP_EOL;
            $section .= '  <div class="al-credits"><a href="http://redkite-labs.com">Powered by RedKiteCms</div>' . PHP_EOL;
            $section .= '{% endblock %}' . PHP_EOL;
        }

        return $section;
    }

    private function parseSlots(array $filter)
    {
        $slots = array_keys($this->themeSlots->getSlots());
        $pageBlocks = $this->pageTree->getPageBlocks()->getBlocks();

        $blocks = (null !== $filter) ? $this->filterBlocks($pageBlocks, $filter) : $pageBlocks;
        foreach ($blocks as $slotName => $slotBlocks) {
            if ( ! in_array($slotName, $slots)) {
                // @codeCoverageIgnoreStart
                continue;
                // @codeCoverageIgnoreEnd
            }

            $this->parseBlocks($slotName, $slotBlocks);
        }
    }

    private function parseBlocks($slotName, $blocks)
    {
        $contents = array();
        foreach ($blocks as $block) {

            $blockManager = $this->blockManagerFactory->createBlockManager($block);
            if (null === $blockManager) {
                // @codeCoverageIgnoreStart
                continue;
                // @codeCoverageIgnoreEnd
            }

            $blockManager->setPageTree($this->pageTree);
            $blockManager->setEditorDisabled(true);
            $content = $this->renderContent($blockManager);

            // @codeCoverageIgnoreStart
            if ($this->credits && preg_match('/\<a[^\>]+href="http:\/\/redkite\-labs\.com[^\>]+\>powered by redkite cms\<\/a\>/is', strtolower($content))) {
                $this->credits = false;
            }
            // @codeCoverageIgnoreEnd

            $metatags = $blockManager->getMetaTags();
            if (null !== $metatags) {
                 $metatag = (is_array($metatags)) ? $this->viewRenderer->render($metatags['RenderView']) : $metatags;
                 if ( ! empty($metatags) && ! in_array($metatag, $this->metatags)) {
                    $this->metatags[] = $metatag;
                 }
            }

            $contents[] = $content;
        }

        $this->contents[$slotName] = $contents;
    }

    private function renderContent($blockManager)
    {
        $content = $blockManager->getHtml();
        if (is_array($content)) {
            $content = $this->viewRenderer->render($content['RenderView']);
        }

        $content = $this->rewriteImagesPathForProduction($content);
        $content = $this->rewriteLinksForProduction($content);

        return $content;
    }

    protected function filterBlocks(array $blocks, array $filter)
    {
        $themeSlots = $this->themeSlots;

        return array_filter($blocks, function ($slotBlocks) use ($themeSlots, $filter) {

            if (count($slotBlocks) == 0) {
                // @codeCoverageIgnoreStart
                return false;
                // @codeCoverageIgnoreEnd
            }

            $slotName = $slotBlocks[0]->getSlotName();
            $slot = $themeSlots->getSlot($slotName);

            if (null === $slot) {
                return true;
            }

            $forcedRepeated = $slot->getForceRepeatedDuringDeploying();
            $repeated = (null !== $forcedRepeated) ? $forcedRepeated : $slot->getRepeated();

            return in_array($repeated, $filter);
        });
    }
}
