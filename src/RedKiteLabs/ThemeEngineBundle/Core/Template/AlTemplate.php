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
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 */

namespace RedKiteLabs\ThemeEngineBundle\Core\Template;

use Symfony\Component\HttpKernel\KernelInterface;
use RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplateAssets;
use RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\AlThemeSlotsInterface;
use RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAssetCollection;

/**
 * The class deputate to manage a template
 *
 * This object stores all the information about a template:
 *
 * - Slots
 * - Assets
 * @method     AlTemplate addExternalStylesheet() Returns the external stylesheets
 * @method     AlTemplate addInternalStylesheet() Returns the internal stylesheets
 * @method     AlTemplate addExternalStylesheets() Returns the external stylesheets
 * @method     AlTemplate addInternalStylesheets() Returns the internal stylesheets
 * @method     AlTemplate getExternalJavascripts() Returns the external javascripts
 * @method     AlTemplate getInternalJavascripts() Returns the internal javascripts
 * @method     AlTemplate getExternalStylesheets() Returns the external javascripts
 * @method     AlTemplate getInternalStylesheets() Returns the internal javascripts
 * @method     AlTemplate setExternalJavascripts() Returns the external javascripts
 * @method     AlTemplate setInternalJavascripts() Returns the internal javascripts
 * @method     AlTemplate setExternalStylesheets() Returns the external javascripts
 * @method     AlTemplate setInternalStylesheets() Returns the internal javascripts
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlTemplate
{
    protected $kernel = null;
    protected $templateAssets = null;
    protected $assets = null;

    /**
     * Constructor
     *
     * @param KernelInterface          $kernel
     * @param AlTemplateAssets         $templateAssets
     */
    public function __construct(KernelInterface $kernel, AlTemplateAssets $templateAssets)
    {
        $this->kernel = $kernel;
        $this->templateAssets = $templateAssets;

        $this->assets = new \ArrayObject(array());
        $this->assets->stylesheets = new \ArrayObject(array());
        $this->assets->javascripts = new \ArrayObject(array());
        $this->assets->stylesheets->external = new AlAssetCollection($this->kernel, $this->templateAssets->getExternalStylesheets());
        $this->assets->stylesheets->internal = new AlAssetCollection($this->kernel, $this->templateAssets->getInternalStylesheets());
        $this->assets->javascripts->external = new AlAssetCollection($this->kernel, $this->templateAssets->getExternalJavascripts());
        $this->assets->javascripts->internal = new AlAssetCollection($this->kernel, $this->templateAssets->getInternalJavascripts());
    }

    /**
     * Clones the holden objects, when the object is cloned
     *
     * @codeCoverageIgnore
     */
    public function __clone()
    {
        if (null !== $this->templateAssets) {
            $this->templateAssets = clone($this->templateAssets);
        }
    }

    /**
     * Sets the theme name for the associated AlTemplateAssets object
     *
     * @param  string                                                  $v
     * @return \RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate
     */
    public function setThemeName($v)
    {
        $this->templateAssets->setThemeName($v);

        return $this;
    }

    /**
     * Sets the template name for the associated AlTemplateAssets object
     *
     * @param  string                                                  $v
     * @return \RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate
     */
    public function setTemplateName($v)
    {
        $this->templateAssets->setTemplateName($v);

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
     * Returns the slots that belong the template. Repeated slots are not included.
     *
     * @return array
     */
    public function setSlots(array $slots)
    {
        $this->slots = $slots;
    }
    
    /**
     * Returns the slots that belong the template. Repeated slots are not included
     *
     * @return array
     */
    public function getSlots()
    {
        return $this->slots;
    }

    /**
     * Catches the methods to manage template assets
     *
     * @param  string            $name   the method name
     * @param  mixed             $params the values to pass to the called method
     * @return mixed             Depends on method called
     * @throws \RuntimeException
     */
    public function __call($name, $params)
    {
        if (preg_match('/^(add)?([Ex|In]+ternal)?([Styleshee|Javascrip]+t)$/', $name, $matches)) {
            $this->addAsset(strtolower($matches[3]) . 's', strtolower($matches[2]), $params[0]);

            return $this;
        }

        if (preg_match('/^(add)?([Ex|In]+ternal)?([Styleshee|Javascrip]+ts)$/', $name, $matches)) {
            if (!is_array($params[0])) {
                throw new \RuntimeException(sprintf('%s method requires an array as argument, %s given', $name, gettype($params[0])));
            }

            $this->addAssetsRange(strtolower($matches[3]), strtolower($matches[2]), $params[0]);

            return $this;
        }

        if (preg_match('/^(get)?([Ex|In]+ternal)?([Styleshee|Javascrip]+ts)$/', $name, $matches)) {
            return $this->getAssets(strtolower($matches[3]), strtolower($matches[2]));
        }

        if (preg_match('/^(set)?([Ex|In]+ternal)?([Styleshee|Javascrip]+ts)$/', $name, $matches)) {
            $a = strtolower($matches[2]);
            $b = strtolower($matches[3]);
            $this->assets->$b->$a = $params[0];

            return;
        }

        throw new \RuntimeException('Call to undefined method: ' . $name);
    }

    /*
    public function setAC($x)
    {
        $this->assets->javascripts->external = $x;
    }*/

    private function addAssetsRange($assetType, $type, array $assets)
    {
        $assetsCollection = $this->assets->$assetType->$type;
        $assetsCollection->addRange($assets);
    }

    private function addAsset($assetType, $type, $asset)
    {
        $assetsCollection = $this->assets->$assetType->$type;
        $assetsCollection->add($asset);
    }

    private function getAssets($assetType, $type)
    {
        return (null !== $this->assets) ? $this->assets->$assetType->$type : null;
    }
    
    /**
     * Returns a slot by its name
     *
     * @return array
     * @deprecated since version number
     */
    public function getSlot($slotName)
    {
        return (null !== $this->templateSlots) ? $this->templateSlots->getSlot($slotName) : array();
    }
}
