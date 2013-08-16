<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 *
 * @license    MIT License
 */

namespace RedKiteLabs\ThemeEngineBundle\Core\PageTree;

use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate;
use RedKiteLabs\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocksInterface;

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
 * @method     AlPageTree addExternalStylesheet() Returns the external stylesheets
 * @method     AlPageTree addInternalStylesheet() Returns the internal stylesheets
 * @method     AlPageTree addExternalStylesheets() Returns the external stylesheets
 * @method     AlPageTree addInternalStylesheets() Returns the internal stylesheets
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
    protected $activeTheme;

    /**
     * Constructor
     *
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param AlPageBlocksInterface $pageBlocks
     */
    public function __construct(ContainerInterface $container, AlPageBlocksInterface $pageBlocks = null)
    {
        $this->container = $container;
        $this->pageBlocks = $pageBlocks;
        $this->activeTheme = $this->container->get('red_kite_labs_theme_engine.active_theme');
    }
    
    /**
     * Returns the container 
     * 
     * @return Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Sets the template object
     *
     * @param AlTemplate $v
     * @return \RedKiteLabs\ThemeEngineBundle\Core\PageTree\AlPageTree
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
     * @return \RedKiteLabs\ThemeEngineBundle\Core\PageTree\AlPageTree
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

        return $this;
    }

    /**
     * Catches the methods to manage assets and metatags
     *
     * @param string $name the method name
     * @param mixed $params the values to pass to the called method
     * @return mixed Depends on method called
     */
    public function __call($name, $params)
    {
        if(preg_match('/^(add)?(External)?([Styleshee|Javascrip]+t)$/', $name, $matches))
        {
            $method = $matches[0];
            $this->getTemplate()->$method($params[0]);
            
            return $this;
        }
        
        if(preg_match('/^(add)?(External)?([Styleshee|Javascrip]+ts)$/', $name, $matches))
        {
            $method = $matches[0];
            $this->getTemplate()->$method($params);
            
            return $this;
        }
        
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
            $property = strtolower($matches[2]) . $matches[3];
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
     * 
     * @codeCoverageIgnore
     */
    protected function mergeAssets($method, $assetType, $type)
    {
        $assetsCollection = $this->getTemplate()->$method();
        if(null !== $assetsCollection) {
            // Collects the blocks when parsed
            $assetsCollection = clone($assetsCollection);
            $blocks = $this->pageBlocks->getBlocks();
            foreach ($blocks as $slotBlocks) {
                foreach ($slotBlocks as $block) {
                    $key = ucfirst($type) . ucfirst($assetType);
                    $key = substr($key, 0, - 1);
                    if (array_key_exists($key, $block)) {
                        $assetsCollection->addRange(explode(',', $block[$key]));
                    }
                }
            }

            return $assetsCollection;
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
        $assetsCollection = $this->mergeAssets($method, $assetType, $type);
        if(null === $assetsCollection) {
            return array();
        }

        $assets = array();
        foreach($assetsCollection as $asset)
        {
            $absolutePath = $asset->getAbsolutePath();
            $originalAsset = $asset->getAsset();
            $assets[] = ($type == 'external') ? (empty($absolutePath)) ? $originalAsset : $absolutePath : $originalAsset;
        }
        
        return $assets;
    }
}
