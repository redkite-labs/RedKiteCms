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

namespace AlphaLemon\ThemeEngineBundle\Core\Template;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsFactoryInterface;
use AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplateAssets;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface;
use AlphaLemon\ThemeEngineBundle\Core\Asset\AlAssetCollection;

/**
 * The class deputate to manage a template
 *
 * This object stores all the information about a template:
 *
 * - Slots
 * - Assets
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlTemplate
{
    protected $kernel = null;
    protected $templateAssets = null;
    protected $assets = null;
    protected $templateSlots = null;

    /**
     * Constructor
     *
     * @param KernelInterface $kernel
     * @param AlTemplateAssets $templateAssets
     * @param AlTemplateSlotsInterface $templateSlots
     */
    public function __construct(KernelInterface $kernel, AlTemplateAssets $templateAssets, AlTemplateSlotsInterface $templateSlots)
    {
        $this->kernel = $kernel;
        $this->templateAssets = $templateAssets;
        $this->templateSlots = $templateSlots;

        $this->setUp();
    }

    /**
     * Clones the holden objects, when the object is cloned
     */
    function __clone()
    {
        if (null !== $this->templateAssets) $this->templateAssets = clone($this->templateAssets);
        if (null !== $this->templateSlots) $this->templateSlots = clone($this->templateSlots);
    }

    /**
     * Sets the theme name for the associated AlTemplateAssets object
     *
     * @param string $v
     * @return \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate
     */
    public function setThemeName($v)
    {
        $this->templateAssets->setThemeName($v);
        $this->setUp();

        return $this;
    }

    /**
     * Sets the template name for the associated AlTemplateAssets object
     *
     * @param string $v
     * @return \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate
     */
    public function setTemplateName($v)
    {
        $this->templateAssets->setTemplateName($v);
        $this->setUp();

        return $this;
    }

    /**
     * Returns the theme name from the associated AlTemplateAssets object
     *
     * @return string
     */
    public function getThemeName()
    {
        return $this->templateAssets->getThemeName();
    }

    /**
     * Returns the theme name from the associated AlTemplateAssets object
     *
     * @return string
     */
    public function getTemplateName()
    {
        return $this->templateAssets->getTemplateName();
    }

    /**
     * Sets the current AlTemplateSlots object
     *
     * @param AlTemplateSlotsInterface $templateSlots
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager
     */
    public function setTemplateSlots(AlTemplateSlotsInterface $templateSlots)
    {
        $this->templateSlots = $templateSlots;

        return $this;
    }

    /**
     * Returns the current AlTemplateSlots object
     *
     * @return \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlots
     */
    public function getTemplateSlots()
    {
        return $this->templateSlots;
    }

    /**
     * Returns the template's slots
     *
     * @return array
     */
    public function getSlots()
    {
        return (null !== $this->templateSlots) ? $this->templateSlots->getSlots() : array();
    }

    /**
     * Returns a slot by its name
     *
     * @return array
     */
    public function getSlot($slotName)
    {
        return (null !== $this->templateSlots) ? $this->templateSlots->getSlot($slotName) : array();
    }

    /**
     * Catches the methods to manage template assets
     *
     * @param string $name the method name
     * @param mixed $params the values to pass to the called method
     * @return mixed Depends on method called
     * @throws \RuntimeException
     */
    public function __call($name, $params)
    {
        if(preg_match('/^(add)?([Ex|In]+ternal)?([Styleshee|Javascrip]+t)$/', $name, $matches))
        {
            $this->addAsset(strtolower($matches[3]) . 's', strtolower($matches[2]), $params[0]);

            return $this;
        }

        if(preg_match('/^(add)?([Ex|In]+ternal)?([Styleshee|Javascrip]+ts)?(Range)$/', $name, $matches))
        {
            if(!is_array($params[0]))
            {
                throw new \RuntimeException(sprintf('%s method requires an array as argument, %s given', $name, gettype($params[0])));
            }

            $this->addAssetsRange(strtolower($matches[3]), strtolower($matches[2]), $params[0]);

            return $this;
        }

        if(preg_match('/^(get)?([Ex|In]+ternal)?([Styleshee|Javascrip]+ts)$/', $name, $matches))
        {
            return $this->getAssets(strtolower($matches[3]), strtolower($matches[2]));
        }

        throw new \RuntimeException('Call to undefined method: ' . $name);
    }

    /**
     * Sets up the template slots object and the template's assets
     */
    protected function setUp()
    {
        $themeName = $this->getThemeName();
        $templateName = $this->getTemplateName();
        if ($themeName != '' && $templateName != '') {
            $this->assets = new \ArrayObject(array());
            $this->assets->stylesheets = new \ArrayObject(array());
            $this->assets->javascripts = new \ArrayObject(array());
            $this->assets->stylesheets->external = new AlAssetCollection($this->kernel, $this->templateAssets->getExternalStylesheets());
            $this->assets->stylesheets->internal = new AlAssetCollection($this->kernel, $this->templateAssets->getInternalStylesheets());
            $this->assets->javascripts->external = new AlAssetCollection($this->kernel, $this->templateAssets->getExternalJavascripts());
            $this->assets->javascripts->internal = new AlAssetCollection($this->kernel, $this->templateAssets->getInternalJavascripts());
        }
    }



    private function addAssetsRange($assetType, $type, $asset)
    {
        $assetsCollection = $this->assets->$assetType->$type;
        $assetsCollection->addRange($asset);
    }

    private function addAsset($assetType, $type, array $assets)
    {
        $assetsCollection = $this->assets->$assetType->$type;
        $assetsCollection->add($assets);
    }

    private function getAssets($assetType, $type)
    {
        return (null !== $this->assets) ? $this->assets->$assetType->$type : null;
    }
}