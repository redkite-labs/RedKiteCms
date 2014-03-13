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

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\AlPageTree;
use RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface;

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
     * @param \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\AlPageTree     $pageTree
     * @param \RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface $theme
     * @param array                                                      $options
     */
    public function generateSection(AlPageTree $pageTree, AlThemeInterface $theme, array $options)
    {
        parent::generateSection($pageTree, $theme, $options);

        $assetsSection = $this->writeComment("Assets section");
        $assetsSection .= $this->generateExternalStylesheet();
        $assetsSection .= $this->generateExternalJavascripts();
        $assetsSection .= $this->generateInternalStylesheet();
        $assetsSection .= $this->generateInternalJavascripts();

        return $assetsSection;
    }

    private function generateExternalStylesheet()
    {
        $externalStylesheets = $this->pageTree->getExternalStylesheets();
        if (empty($externalStylesheets)) {
            return "";
        }

        $sectionContent = '<link href="{{ asset_url }}" rel="stylesheet" type="text/css" media="all" />';
        $filter = 'cssrewrite';

        return $this->writeBlock('external_stylesheets', $this->writeAssetic('stylesheets', implode(' ', array_map(function ($value) { return '"' . $value . '"'; }, $externalStylesheets )), $sectionContent, $filter), true);
    }

    private function generateExternalJavascripts()
    {
        $externalJavascripts = $this->pageTree->getExternalJavascripts();
        if (empty($externalJavascripts)) {
            return "";
        }

        $sectionContent = '<script src="{{ asset_url }}"></script>';

        return $this->writeBlock('external_javascripts', $this->writeAssetic('javascripts', implode(' ', array_map(function ($value) { return '"' . $value . '"'; }, $externalJavascripts )), $sectionContent), true);
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
