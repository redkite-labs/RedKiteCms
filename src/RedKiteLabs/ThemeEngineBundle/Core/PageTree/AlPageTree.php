<?php
/*
 * This file is part of the AlphaLemonPageTreeBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 *
 * @license    MIT License
 */

namespace AlphaLemon\ThemeEngineBundle\Core\PageTree;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate;
use AlphaLemon\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocksInterface;

/**
 * The AlPageTree object is responsibile to store and collect all the information required to
 * display a web page
 *
 * The collected information are:
 *   - Theme Name
 *   - Template Name
 *   - SEO metatags
 *   - Slots
 *   - Contents
 *   - Assets
 *
 * @method     AlPageTree getExternalStylesheets() Returns the external stylesheets
 * @method     AlPageTree getInternalStylesheets() Returns the internal stylesheets
 * @method     AlPageTree getExternalJavascripts() Returns the external javascripts
 * @method     AlPageTree getInternalJavascripts() Returns the internal javascripts
 * @method     AlPageTree getMetaTitle() Returns the seo meta title
 * @method     AlPageTree getMetaDescription() Returns the seo meta title
 * @method     AlPageTree getMetaKeywords() Returns Returns the seo meta title
 * @method     AlPageTree setMetaTitle() Sets the seo meta title
 * @method     AlPageTree setMetaDescription() Sets the seo meta title
 * @method     AlPageTree setMetaKeywords() Sets Returns the seo meta title
 *
 * @author AlphaLemon
 */
class AlPageTree
{
    protected $container = null;
    protected $template;
    protected $pageBlocks;
    protected $metaTitle = "";
    protected $metaDescription = "";
    protected $metaKeywords = "";
    protected $parameterSchema = array('%s.%s_%s');
    protected $activeTheme;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     * @param AlTemplate $template
     * @param AlPageBlocksInterface $pageBlocks
     */
    public function __construct(ContainerInterface $container, AlPageBlocksInterface $pageBlocks = null)
    {
        $this->container = $container;
        $this->pageBlocks = $pageBlocks;
        $this->activeTheme = $this->container->get('alphalemon_theme_engine.active_theme');
    }

    /**
     * Sets the template object
     *
     * @param AlTemplate $v
     * @return \AlphaLemon\ThemeEngineBundle\Core\PageTree\AlPageTree
     */
    public function setTemplate(AlTemplate $v)
    {
        $this->template = $v;

        return $this;
    }

    /**
     * Sets the pageBlocks object
     *
     * @param AlPageBlocksInterface $v
     * @return \AlphaLemon\ThemeEngineBundle\Core\PageTree\AlPageTree
     */
    public function setPageBlocks(AlPageBlocksInterface $v)
    {
        $this->pageBlocks = $v;

        return $this;
    }

    /**
     * Returns the current template object
     *
     * @return AlTemplate
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Returns the current pageBlocks object
     *
     * @return AlPageBlocksInterface
     */
    public function getPageBlocks()
    {
        return $this->pageBlocks;
    }


    /**
     * Sets the page metatags
     *
     *
     * The metatags array could have the following keys:
     *
     *      - title
     *      - description
     *      - keywords
     *
     * @param array $metatags
     */
    public function setMetatags(array $metatags)
    {
        if(array_key_exists('title', $metatags)) $this->metaTitle = $metatags['title'];
        if(array_key_exists('description', $metatags)) $this->metaDescription = $metatags['description'];
        if(array_key_exists('keywords', $metatags)) $this->metaKeywords = $metatags['keywords'];
    }

    /**
     * Catches the methods to manage assets and metatags
     *
     * @param string $name the method name
     * @param mixed $params the values to pass to the called method
     *
     * @return mixed Depends on method called
     */
    public function __call($name, $params)
    {
        if(preg_match('/^(get)?(External)?([Styleshee|Javascrip]+ts)$/', $name, $matches))
        {
            return $this->getAssets($matches[0], strtolower($matches[3]), strtolower($matches[2]));
        }

        if(preg_match('/^(get)?(Internal)?([Styleshee|Javascrip]+ts)$/', $name, $matches))
        {
            return implode("", $this->getAssets($matches[0], strtolower($matches[3]), strtolower($matches[2])));
        }

        if(preg_match('/^(get)?(Meta)?([Title|Description|Keywords]+)$/', $name, $matches))
        {
            $property = strtolower($matches[2]) . $matches[3];

            return $this->$property;
        }

        if(preg_match('/^(set)?(Meta)?([Title|Description|Keywords]+)$/', $name, $matches))
        {
            $this->$property = $params[0];

            return $this;
        }

        throw new \RuntimeException('Call to undefined method: ' . $name);
    }

    /**
     * Merges the assets for the given method
     *
     * @param string $method The method to retrieve the current ArrayObject tha stores the requiredassets
     * @param string $assetType The assets type (stylesheet/favascript)
     * @param string $type The required type (internal/external)
     * @return type
     */
    protected function mergeAssets($method, $assetType, $type)
    {
        $templateAssets = $this->getTemplate()->$method();
        if(null !== $templateAssets) {
            // Collects the blocks when parsed
            $templateAssets = clone($templateAssets);
            $blocks = $this->pageBlocks->getBlocks();
            foreach ($blocks as $slotBlocks) {
                foreach ($slotBlocks as $block) {
                    $method = 'get'. ucfirst($type) . ucfirst($assetType);
                    $method = substr($method, 0, strlen($method) - 1);
                    $templateAssets->addRange(explode(',', $block->$method()));
                }
            }

            return $templateAssets;
        }
    }

    /**
     * Returns an array that contains the absolute path of each asset
     *
     * @param string $method The method to retrieve the current ArrayObject tha stores the requiredassets
     * @param string $assetType The assets type (stylesheet/javascript)
     * @param string $type The required type (internal/external)
     * @return array
     */
    protected function getAssets($method, $assetType, $type)
    {
        $templateAssets = $this->mergeAssets($method, $assetType, $type);
        if(null === $templateAssets) {
            return array();
        }

        $assets = array();
        foreach($templateAssets as $asset)
        {
            $absolutePath = $asset->getAbsolutePath();
            $originalAsset = $asset->getAsset();
            $assets[] = ($type == 'external') ? (empty($absolutePath)) ? $originalAsset : $absolutePath : $originalAsset;
        }

        return $assets;
    }
}
