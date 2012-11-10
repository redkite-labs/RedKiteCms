<?php
/**
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
use AlphaLemon\ThemeEngineBundle\Core\Exception\InvalidArgumentException;

/**
 * AlTemplateAssets
 *
 * @method     AlTemplateAssets getExternalStylesheets() Returns the external stylesheets
 * @method     AlTemplateAssets getInternalStylesheets() Returns the internal stylesheets
 * @method     AlTemplateAssets getExternalJavascripts() Returns the external javascripts
 * @method     AlTemplateAssets getInternalJavascripts() Returns the internal javascripts
 * @method     AlTemplateAssets setExternalStylesheets() Sets the external stylesheets. When the property is valorized, the saved value is replaced
 * @method     AlTemplateAssets setInternalStylesheets() Sets the internal stylesheets. When the property is valorized, the saved value is replaced
 * @method     AlTemplateAssets setExternalJavascripts() Sets the external javascripts. When the property is valorized, the saved value is replaced
 * @method     AlTemplateAssets setInternalJavascripts() Sets the internal javascripts. When the property is valorized, the saved value is replaced
 * @method     AlTemplateAssets addExternalStylesheets() Adds some assets to the external stylesheets collection
 * @method     AlTemplateAssets addInternalStylesheets() Adds some assets to the internal stylesheets collection
 * @method     AlTemplateAssets addExternalJavascripts() Adds some assets to the external javascripts collection
 * @method     AlTemplateAssets addInternalJavascripts() Adds some assets to the internal javascripts collection
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlTemplateAssets
{
    protected $themeName;
    protected $templateName;
    protected $assetsTemplateName;
    protected $assetsThemeName;
    protected $externalStylesheets = array();
    protected $internalStylesheets = array();
    protected $externalJavascripts = array();
    protected $internalJavascripts = array();

    /**
     * Sets the template name
     *
     * @param string $v
     * @return \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplateAssets
     */
    public function setTemplateName($v)
    {
        $this->validateString($v);
        $this->templateName = strtolower($v);
        $this->assetsTemplateName = strtolower($this->templateName);

        return $this;
    }

    /**
     * Sets the theme name
     *
     * @param string $v
     * @return \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplateAssets
     */
    public function setThemeName($v)
    {
        $this->validateString($v);
        $this->themeName = $v;
        $this->assetsThemeName = strtolower(str_replace('Bundle', '', $this->themeName));

        return $this;
    }

    /**
     * Returns the temmplate name
     *
     * @return string
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

    /**
     * Returns the theme name
     *
     * @return string
     */
    public function getThemeName()
    {
        return $this->themeName;
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
        if (preg_match('/^(get)?([Ex|In]+ternal)?([Styleshee|Javascrip]+ts)$/', $name, $matches))
        {
            $property = strtolower($matches[2]) . $matches[3];
            $assets = $this->$property;

            return $assets;
        }

        if (preg_match('/^(set)?([Ex|In]+ternal)?([Styleshee|Javascrip]+ts)/', $name, $matches))
        {
            $values = $params[0];
            if ( ! is_array($values)) {
                $values = array($values);
            }
        
            $property = strtolower($matches[2]) . $matches[3];
            $this->$property = $values;

            return $this;
        }

        throw new \RuntimeException('Call to undefined method: ' . $name);
    }

    private function validateString($v)
    {
        if(!is_string($v)) {
            throw new InvalidArgumentException('The called method expects a string value as param.');
        }
    }
}