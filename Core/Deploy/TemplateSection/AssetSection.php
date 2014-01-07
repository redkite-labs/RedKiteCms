<?php

/*
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Deploy\TemplateSection;

use RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree;
use RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\TemplateSection\TemplateSectionTwig;

/**
 * AssetSection is the object deputated to generate the asset sections of a twig template
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AssetSection extends TemplateSectionTwig
{    
    /**
     * Defines the base method to generate a section
     * 
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree $pageTree
     * @param \RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface $theme
     * @param array $options
     */
    public function generateSection(AlPageTree $pageTree, AlThemeInterface $theme, array $options)
    {
        parent::generateSection($pageTree, $theme, $options);
        
        $yuiEnabled = $options["yuiCompressorEnabled"];
        $assetsSection = $this->writeComment("Assets section");
        $assetsSection .= $this->generateExternalStylesheet($yuiEnabled);
        $assetsSection .= $this->generateExternalJavascripts($yuiEnabled);
        $assetsSection .= $this->generateInternalStylesheet();
        $assetsSection .= $this->generateInternalJavascripts();
        
        return $assetsSection;
    }
    
    private function generateExternalStylesheet($yuiEnabled)
    {
        $externalStylesheets = $this->pageTree->getExternalStylesheets();   
        if (empty($externalStylesheets)) {
            return "";
        }
            
        $sectionContent = '<link href="{{ asset_url }}" rel="stylesheet" type="text/css" media="all" />';
        $filter = $yuiEnabled ? '?yui_css,cssrewrite' : '?cssrewrite';
        
        return $this->writeBlock('external_stylesheets', $this->writeAssetic('stylesheets', implode(' ', array_map(function ($value) { return '"' . $value . '"'; }, $externalStylesheets )), $sectionContent, $filter), true);        
    }
    
    private function generateExternalJavascripts($yuiEnabled)
    {
        $externalJavascripts = $this->pageTree->getExternalJavascripts();
        if (empty($externalJavascripts)) {
            return "";
        }
            
        $sectionContent = '<script src="{{ asset_url }}"></script>';
        $filter = $yuiEnabled ? '?yui_js' : '';
        
        return $this->writeBlock('external_javascripts', $this->writeAssetic('javascripts', implode(' ', array_map(function ($value) { return '"' . $value . '"'; }, $externalJavascripts )), $sectionContent, $filter), true);
    }
    
    private function generateInternalStylesheet()
    {
        $internalStylesheet = $this->pageTree->getInternalStylesheets();
        if (empty($internalStylesheet)) {
            return "";
        }
        
        return $this->writeBlock('internal_header_stylesheets', '<style>' . $internalStylesheet . '</style>', true);;
    }
    
    private function generateInternalJavascripts()
    {
        $internalJavascript = $this->pageTree->getInternalJavascripts();
        if (empty($internalJavascript)) {
            return "";
        }
        
        return $this->writeBlock('internal_header_javascripts', '<script>$(document).ready(function () {' . $this->rewriteImagesPathForProduction($internalJavascript) . '});</script>', true);
    }
}