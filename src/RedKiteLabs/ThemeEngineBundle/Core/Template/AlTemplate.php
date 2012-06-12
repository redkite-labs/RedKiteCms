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
 * AlTemplate
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlTemplate
{
    protected $assets = null;
    protected $templateName;
    protected $themeName;
    protected $templateSlots = null;
    protected $templateAssets;

    public function __construct(AlTemplateAssets $templateAssets, KernelInterface $kernel, AlTemplateSlotsFactoryInterface $templateSlotsFactory)
    {
        $this->templateAssets = $templateAssets;
        $this->kernel = $kernel;
        $this->templateSlotsFactory = $templateSlotsFactory;
    }


    public function setTemplateName($v)
    {
        $this->templateName = $v;
        $this->setUp();

        return $this;
    }

    public function setThemeName($v)
    {
        $this->themeName = $v;
        $this->setUp();

        return $this;
    }

    public function getTemplateName()
    {
        return $this->templateName;
    }

    public function getThemeName()
    {
        return $this->themeName;
    }

    /**
     * Sets the current AlTemplateSlots object
     *
     * @api
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
     * @api
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
        return (null !== $this->templateSlots) ? $this->templateSlots->getSlots() : array();;
    }

    /**
     * Returns a slot by its name
     *
     * @return array
     */
    public function getSlot($slotName)
    {
        if(null === $this->templateSlots)
        {
            return null;
        }

        $slots = $this->getSlots();
        if(!\array_key_exists($slotName, $slots))
        {
            return null;
        }

        return $slots[$slotName];
    }

    public function __call($name, $params)
    {
        if(preg_match('/^(add)?([Ex|In]+ternal)?([Styleshee|Javascrip]+t)$/', $name, $matches))
        {
            $this->addAsset(strtolower($matches[3]) . 's', strtolower($matches[2]), $params[0]);

            return;
        }

        if(preg_match('/^(add)?([Ex|In]+ternal)?([Styleshee|Javascrip]+ts)?(Range)$/', $name, $matches))
        {
            if(!is_array($params[0]))
            {
                throw new \RuntimeException(sprintf('%s method requires an array as argument, %s given', $name, gettype($params[0])));
            }

            $this->addAssetsRange(strtolower($matches[3]), strtolower($matches[2]), $params[0]);

            return;
        }

        if(preg_match('/^(get)?([Ex|In]+ternal)?([Styleshee|Javascrip]+ts)$/', $name, $matches))
        {
            return $this->getAssets(strtolower($matches[3]), strtolower($matches[2]));
        }

        throw new \RuntimeException('Call to undefined method: ' . $name);
    }

    /**
     * Sets up the page tree object for the current template
     */
    protected function setUp()
    {
        if ($this->themeName != '' && $this->templateName != '') {
            $this->templateSlots = $this->templateSlotsFactory->create($this->themeName, $this->templateName);

            $this->templateAssets
                    ->setThemeName($this->themeName)
                    ->setTemplateName($this->templateName);

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
        if (null === $this->assets) {
            return null;
        }

        return $this->assets->$assetType->$type;
    }
}