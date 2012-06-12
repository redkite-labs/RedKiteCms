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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template;

use AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplateAssets as BaseTemplateAssets;

/**
 * AlTemplateAssets
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlTemplateAssets extends BaseTemplateAssets
{
    protected function setUp()
    {
        if ($this->themeName != '' && $this->templateName != '') {
            parent::setUp();

            $this->externalStylesheets = array_merge($this->externalStylesheets, $this->fetchAssets(sprintf('themes.%s_%s.external_stylesheets.cms', $this->assetsThemeName, $this->assetsTemplateName)));
            $this->internalStylesheets = array_merge($this->internalStylesheets, $this->fetchAssets(sprintf('themes.%s_%s.internal_stylesheets.cms', $this->assetsThemeName, $this->assetsTemplateName)));
            $this->externalJavascripts = array_merge($this->externalJavascripts, $this->fetchAssets(sprintf('themes.%s_%s.external_javascripts.cms', $this->assetsThemeName, $this->assetsTemplateName)));
            $this->internalJavascripts = array_merge($this->internalJavascripts, $this->fetchAssets(sprintf('themes.%s_%s.internal_javascripts.cms', $this->assetsThemeName, $this->assetsTemplateName)));
        }
    }
}