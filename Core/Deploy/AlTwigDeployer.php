<?php

/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 * 
 * @license    GPL LICENSE Version 2.0
 * 
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Deploy;

use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;
use Symfony\Component\Finder\Finder;

class AlTwigDeployer extends AlDeployer {

    /**
     * @inheritDoc
     */
    protected function save(AlPageTree $pageTree) {
        // Extends the assigned template
        $twig = sprintf("{%% extends '%s:Theme:%s.html.twig' %%}\n", $pageTree->getThemeName(), $pageTree->getTemplateName());

        // Writes metatags
        $twig .= $this->writeComment("Metatags section");
        $twig .= $this->writeBlock('title', $pageTree->getMetaTitle());
        $twig .= $this->writeBlock('description', $pageTree->getMetaDescription());
        $twig .= $this->writeBlock('keywords', $pageTree->getMetaKeywords());

        // Writes page contents
        $twigContents = $this->writeComment("Contents section");
        
        $apps = Array();
        $slots = array_keys($pageTree->getSlots());
        foreach ($pageTree->getContents() as $slotName => $contents) {
            if (!in_array($slotName, $slots))
                continue;

            $htmlContents = array();
            foreach ($contents as $content) {
                $htmlContents[] = $content['HtmlContent'];
                if (!in_array($content['Block']['ClassName'], $apps))
                    $apps[] = $content['Block']['ClassName'];
            }

            $twigContents .= $this->writeBlock($slotName, $this->writeContent($slotName, implode("\n\n", $htmlContents)));
        }

        // Writes the external assets
        $twig .= $this->writeComment("Assets section");

        // Retrieves the apps assets
        $appsStylesheets = array();
        $appsJavascripts = array();
        foreach ($apps as $app) {
            $param = strtolower($app) . '.stylesheets';
            if ($this->container->hasParameter($param))
                $appsStylesheets = array_merge($appsStylesheets, $this->container->getParameter($param));

            $param = strtolower($app) . '.javascripts';
            if ($this->container->hasParameter($param))
                $appsJavascripts = array_merge($appsJavascripts, $this->container->getParameter($param));
        }

        $stylesheets = (!empty($appsStylesheets)) ? array_unique(array_merge($pageTree->getExternalTemplateStylesheets(), $appsStylesheets)) : $pageTree->getExternalTemplateStylesheets();
        $stylesheets = $this->normalizeAssetsPath($stylesheets);
        $assets = $this->assetsToString($stylesheets);
        if (!empty($assets)) {
            $content = '<link href="{{ asset_url }}" rel="stylesheet" type="text/css" media="all" />';
            $twig .= $this->writeBlock('external_stylesheets', $this->writeAssetic('stylesheets', $assets, $content, '?yui_css,cssrewrite'));
        }

        // Writes the internal assets
        $javascripts = (!empty($appsJavascripts)) ? array_unique(array_merge($pageTree->getExternalTemplateJavascripts(), $appsJavascripts)) : $pageTree->getExternalTemplateJavascripts();
        $javascripts = $this->normalizeAssetsPath($javascripts);
        $assets = $this->assetsToString($javascripts);
        if (!empty($assets)) {
            $content = '<script src="{{ asset_url }}"></script>';
            $twig .= $this->writeBlock('external_javascripts', $this->writeAssetic('javascripts', $assets, $content, '?yui_js'));
        }

        if ($pageTree->getInternalJavascript() != "")
            $twig .= $this->writeBlock('internal_header_javascripts', '<script>$(document).ready(function(){' . $pageTree->getInternalJavascript() . '});</script>');
        if ($pageTree->getInternalStylesheet() != "")
            $twig .= $this->writeBlock('internal_header_stylesheets', '<style>' . $pageTree->getInternalStylesheet() . '</style>');

        // Writes down the file
        $filePath = $this->dataFolder . '/' . $pageTree->getAlLanguage()->getLanguage();
        if (!is_dir($filePath)) {
            mkdir($filePath);
        }

        file_put_contents($filePath . '/' . $pageTree->getAlPage()->getPageName() . '.html.twig', $twig . $twigContents);
    }

    protected function writeComment($comment)
    {
        $comment = strtoupper($comment);

        return "\n{#################  $comment  #################}\n";
    }

    protected function writeBlock($blockName, $content) 
    {
        if (empty($content))
            return "";

        $block = "{% block $blockName %}\n";
        $block .= $content . "\n";
        $block .= "{% endblock %}\n\n";

        return $block;
    }

    protected function writeAssetic($sectionName, $assets, $content, $filter = null, $output = null) 
    {
        $section = $sectionName . " " . $assets;
        if (null !== $filter)
            $section .= " filter=\"$filter\"";
        if (null !== $output)
            $section .= " output=\"$output\"";
        $block = "  {% $section %}\n";
        $block .= $this->formatContent($content) . "\n";
        $block .= "  {% end$sectionName %}";

        return $block;
    }

    protected function writeContent($slotName, $content) 
    {
        $block = "  {% if(slots.$slotName is not defined) %}\n";
        $block .= $this->formatContent($content) . "\n";
        $block .= "  {% else %}\n";
        $block .= "    {{ parent() }}\n";
        $block .= "  {% endif %}";

        return $block;
    }

    protected function assetsToString($assets) 
    {
        $res = array();
        foreach ($assets as $asset) {
            $res[] = '"' . $asset . '"';
        }

        return implode(" ", $res);
    }

    protected function formatContent($content) 
    {
        $formattedContents = array();
        $tokens = explode("\n", $content);
        foreach ($tokens as $token) {
            $formattedContents[] = "    " . $token;
        }

        return implode("\n", $formattedContents);
    }

    /**
     * Normalize the assets' path to be used in the assets twig file. The paths which are normalized are:
     * 
     *  @BundleName/Resources/public/[asset]
     *  /full/path/to/resource/Resources/public/[asset]
     * 
     * and converted as
     *  
     *  bundles/bundlenameinlowercase/[asset]
     * 
     * When the rule doesn't match, the given path is returned untouched
     * 
     * @param array     $assets
     * @return array    The normalized paths
     */
    protected function normalizeAssetsPath(array $assets) 
    {
        $assetsFound = array();
        foreach ($assets as $asset) {
            $filename = basename($asset);
            $currentAsset = $asset;

            // Checks if the assets is given with a relative path 
            if (false !== strpos($currentAsset, 'bundles') || false !== strpos($currentAsset, '@')) {
                // Recreates the full path
                if (false === strpos($currentAsset, '@')) {
                    $currentAsset = $this->container->getParameter('kernel.root_dir') . '/../' . $this->container->getParameter('alcms.web_folder_name') . '/' . $currentAsset;
                } else {
                    preg_match('/(@[\w]+)\/([\w\/\*]+)/', $currentAsset, $match);
                    $currentAsset = AlToolkit::locateResource($this->container, $match[1]) . $match[2];
                }
                $currentAsset = AlToolkit::normalizePath($currentAsset);

                // Checks if the asset represents a folder that requires all the files that contains
                $assetLength = strlen($currentAsset);
                if (substr($currentAsset, $assetLength - 1, 1) == '*') {
                    // Checks the files stored into the folder
                    $path = substr($currentAsset, 0, $assetLength - 1);
                    $finder = new Finder();
                    $filesFound = $finder->depth(0)->files()->in($path);
                    foreach ($filesFound as $fileFound) {
                        $fileFound = (string) $fileFound;
                        $assetsFound[basename($fileFound)] = $fileFound;
                    }
                } else {
                    $assetsFound[basename($asset)] = $asset;
                }
            } else {
                $assetsFound[basename($asset)] = $asset;
            }
        }

        $assetsFound = array_unique($assetsFound);

        $normalizedAssets = array();
        foreach ($assetsFound as $asset) {
            if (trim($asset) != "") {
                preg_match('/[@|\/](.*?)\/Resources\/public\/(.*)/', $asset, $matches);
                if (!empty($matches)) {
                    $bundleName = $matches[1];
                    if (strpos($bundleName, '/') !== false) {
                        $bundleName = \str_replace('/', '', \strrchr($bundleName, '/'));
                    }

                    $path = AlToolkit::retrieveBundleWebFolder($this->container, $bundleName) . "/" . $matches[2];
                } else {
                    $path = $asset;
                }

                $normalizedAssets[] = $path;
            }
        }

        return $normalizedAssets;
    }
}