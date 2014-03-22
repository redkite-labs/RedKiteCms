<?php

/*
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\UrlManager\UrlManagerInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree;
use RedKiteLabs\ThemeEngineBundle\Core\Theme\ThemeInterface;

/**
 * TemplateSection defines the section required to generate a template
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
abstract class TemplateSection
{
    /** @var null|UrlManagerInterface */
    protected $urlManager = null;
    /** @var null|PageTree */
    protected $pageTree = null;
    /** @var null|ThemeInterface */
    protected $theme = null;
    /** @var null|string */
    protected $imagesSourcePath = null;
    /** @var null|string */
    protected $imagesTargetPath = null;
    /** @var null|\RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\ThemeSlotsInterface */
    protected $themeSlots = null;

    /**
     * Writes a comment section
     *
     * @param  string $comment
     * @return string
     */
    abstract protected function writeComment($comment);

    /**
     * Writes a block section
     *
     * @param  string  $blockName
     * @param  string  $blockContent
     * @param  boolean $parent
     * @return string
     */
    abstract protected function writeBlock($blockName, $blockContent, $parent = false);

    /**
     * Writes a block section without carriage return
     *
     * @param  string $blockName
     * @param  string $blockContent
     * @return string
     */
    abstract protected function writeInlineBlock($blockName, $blockContent);

    /**
     * Writes an assetc section
     *
     * @param  string $sectionName
     * @param  string $assetsSection
     * @param  string $sectionContent
     * @param  string $filter
     * @param  string $output
     * @return string
     */
    abstract protected function writeAssetic($sectionName, $assetsSection, $sectionContent, $filter = null, $output = null);

    /**
     * Writes a content section
     *
     * @param  string $slotName
     * @param  string $content
     * @return string
     */
    abstract protected function writeContent($slotName, $content);

    /**
     * Indentates the given content
     *
     * @param  string $content
     * @return string
     */
    abstract protected function identateContent($content);

    /**
     * Constructor
     *
     * @param UrlManagerInterface $urlManager
     */
    public function __construct(UrlManagerInterface $urlManager)
    {
        $this->urlManager = $urlManager;
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
        // Writes page contentsSection
        $this->pageTree = $pageTree;
        $this->theme = $theme;
        $this->themeSlots = $theme->getThemeSlots();

        $this->imagesSourcePath = $options["uploadAssetsAbsolutePath"];
        $this->imagesTargetPath = $options["deployBundleAssetsPath"];
    }

    /**
     * Rewrites the images to be correctly displayed in the production environment
     *
     * @param  string $content
     * @return string
     */
    protected function rewriteImagesPathForProduction($content)
    {
        $imagesSourcePath = $this->imagesSourcePath;
        $imagesTargetPath = $this->imagesTargetPath;

        return preg_replace_callback('/([\/]?)(' . str_replace('/', '\/', $imagesSourcePath) . ')/s', function ($matches) use ($imagesTargetPath) {return $matches[1].$imagesTargetPath;}, $content);
    }

    /**
     * Rewrites the website links for the production environment
     *
     * @param  string $content
     * @return string
     */
    protected function rewriteLinksForProduction($content)
    {
        $urlManager = $this->urlManager;

        $content = preg_replace_callback('/(\<a[^\>]+href[="\s]+)([^"\s]+)?([^\>]+\>)/s', function ($matches) use ($urlManager) {
            $url = $matches[2];

            if (preg_match('/route:([^"]+)/i', $url, $route)) {
                $url = sprintf("{{ path(%s) }}", html_entity_decode($route[1], ENT_QUOTES));

                return $matches[1] . $url . $matches[3];
            }

            $route = $urlManager
                ->fromUrl($url)
                ->getProductionRoute();

            if (null !== $route) {
                $url = sprintf("{{ path('%s') }}", $route);
            }

            return $matches[1] . $url . $matches[3];
        }, $content);

        return $content;
    }
}
