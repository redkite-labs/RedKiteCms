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

/**
 * AlTemplateAssets
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlTemplateAssets
{
    protected $themeName;
    protected $templateName;
    protected $assetsTemplateName;
    protected $assetsThemeName;
    protected $externalStylesheets;
    protected $internalStylesheets;
    protected $externalJavascripts;
    protected $internalJavascripts;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    public function setTemplateName($v)
    {
        $this->templateName = $v;
        $this->assetsTemplateName = strtolower($this->templateName);

        $this->setUp();

        return $this;
    }

    public function setThemeName($v)
    {
        $this->themeName = $v;
        $this->assetsThemeName = strtolower(str_replace('Bundle', '', $this->themeName));

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

    public function __call($name, $params)
    {
        if(preg_match('/^(set)?([Ex|In]+ternal)?([Styleshee|Javascrip]+ts)?(Range)$/', $name, $matches))
        {
            $property = strtolower($matches[2]) . $matches[3];
            $this->$property = $params[0];

            return;
        }

        if(preg_match('/^(get)?([Ex|In]+ternal)?([Styleshee|Javascrip]+ts)$/', $name, $matches))
        {
            $property = strtolower($matches[2]) . $matches[3];

            return $this->$property;
        }

        throw new \RuntimeException('Call to undefined method: ' . $name);
    }
    
    protected function setUp()
    {
        if ($this->assetsThemeName != '' && $this->assetsTemplateName != '') {
            $this->externalStylesheets = $this->fetchAssets(sprintf('themes.%s_%s.external_stylesheets', $this->assetsThemeName, $this->assetsTemplateName));
            $this->internalStylesheets = $this->fetchAssets(sprintf('themes.%s_%s.internal_stylesheets', $this->assetsThemeName, $this->assetsTemplateName));
            $this->externalJavascripts = $this->fetchAssets(sprintf('themes.%s_%s.external_javascripts', $this->assetsThemeName, $this->assetsTemplateName));
            $this->internalJavascripts = $this->fetchAssets(sprintf('themes.%s_%s.internal_javascripts', $this->assetsThemeName, $this->assetsTemplateName));
        }
    }

    protected function fetchAssets($param)
    {
        if(null !== $this->container && $this->container->hasParameter($param)) {
            return $this->container->getParameter($param);
        }

        return array();
    }
}