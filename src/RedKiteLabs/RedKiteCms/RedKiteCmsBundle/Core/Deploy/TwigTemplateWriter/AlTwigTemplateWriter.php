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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\TwigTemplateWriter;

use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;
use Symfony\Component\Finder\Finder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * AlTwigTemplateWriter generates a twig template from a PageTree object
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlTwigTemplateWriter
{
    protected $pageTree;
    protected $router;
    protected $template;
    protected $twigTemplate;
    protected $templateSection;
    protected $metatagsSection;
    protected $assetsSection;
    protected $contentsSection;

    /**
     * Constructor
     *
     * The $replaceImagesPaths contains the backend images' path and the production images path, as follows:
     *
     *      array(
     *          backendPath => '/path/to/backend/images,
     *          prodPath    => '/path/to/prod/images,
     *      )
     *
     * When the page is saving, the images' path is replaced
     *
     *
     * @param ContainerInterface $container
     * @param array $replaceImagesPaths
     */
    public function  __construct(AlPageTree $pageTree, RouterInterface $router, array $replaceImagesPaths = array())
    {
        $this->pageTree = $pageTree;
        $this->router = $router;
        $this->replaceImagesPaths = $replaceImagesPaths;
        $this->template = $this->pageTree->getTemplate();
        $this->generateTemplate();
    }

    /**
     * Returns the generated template
     *
     * @return string
     */
    public function getTwigTemplate()
    {
        return $this->twigTemplate;
    }

    /**
     * Returns the template extend directive
     *
     * @return string
     */
    public function getTemplateSection()
    {
        return $this->templateSection;
    }

    /**
     * Returns the metatags section
     *
     * @return string
     */
    public function getMetaTagsSection()
    {
        return $this->metatagsSection;
    }

    /**
     * Returns the assets section
     *
     * @return string
     */
    public function getAssetsSection()
    {
        return $this->assetsSection;
    }

    /**
     * Returns the contents section
     *
     * @return string
     */
    public function getContentsSection()
    {
        return $this->contentsSection;
    }

    /**
     * Writes the template
     *
     * @param string $dir
     * @return boolean
     */
    public function writeTemplate($dir)
    {
        // Writes down the file
        $fileDir = $dir . '/' . $this->pageTree->getAlLanguage()->getLanguage();
        if (!is_dir($fileDir)) {
            mkdir($fileDir);
        }

        return @file_put_contents($fileDir . '/' . $this->pageTree->getAlPage()->getPageName() . '.html.twig', $this->twigTemplate);
    }

    /**
     * Generates the template's subsections and the full template itself
     */
    protected function generateTemplate()
    {
        $this->generateTemplateSection();
        $this->generateMetaTagsSection();
        $this->generateAssetsSection();
        $this->generateContentsSection();

        $this->twigTemplate = $this->templateSection . $this->metatagsSection . $this->assetsSection . $this->contentsSection;
    }

    /**
     * Generates the template extension section
     */
    protected function generateTemplateSection()
    {
        $this->templateSection = sprintf("{%% extends '%s:Theme:%s.html.twig' %%}\n", $this->template->getThemeName(), $this->template->getTemplateName());
    }

    /**
     * Generates the metatags section
     */
    protected function generateMetaTagsSection()
    {
        $this->metatagsSection = $this->writeComment("Metatags section");
        $this->metatagsSection .= $this->writeBlock('title', $this->pageTree->getMetaTitle());
        $this->metatagsSection .= $this->writeBlock('description', $this->pageTree->getMetaDescription());
        $this->metatagsSection .= $this->writeBlock('keywords', $this->pageTree->getMetaKeywords());
    }

    /**
     * Generates the assets section
     */
    protected function generateAssetsSection()
    {
        $externalStylesheets = $this->pageTree->getExternalStylesheets();
        $externalJavascripts = $this->pageTree->getExternalJavascripts();
        $internalStylesheet = $this->pageTree->getInternalStylesheets();
        $internalJavascript = $this->pageTree->getInternalJavascripts();
        $this->assetsSection = $this->writeComment("Assets section");
        if (!empty($externalStylesheets)) {
            $sectionContent = '<link href="{{ asset_url }}" rel="stylesheet" type="text/css" media="all" />';
            $this->assetsSection .= $this->writeBlock('external_stylesheets', $this->writeAssetic('stylesheets', implode(" ", $externalStylesheets), $sectionContent, '?yui_css,cssrewrite'));
        }

        if (!empty($externalJavascripts)) {
            $sectionContent = '<script src="{{ asset_url }}"></script>';
            $this->assetsSection .= $this->writeBlock('external_javascripts', $this->writeAssetic('javascripts', implode(" ", $externalJavascripts), $sectionContent, '?yui_js'));
        }

        if (!empty($internalStylesheet)) {
            $this->assetsSection .= $this->writeBlock('internal_header_stylesheets', '<style>' . $internalStylesheet . '</style>');
        }

        if (!empty($internalJavascript)) {
            $this->assetsSection .= $this->writeBlock('internal_header_javascripts', '<script>$(document).ready(function(){' . $internalJavascript . '});</script>');
        }
    }

    /**
     * Generates the contents section
     */
    protected function generateContentsSection()
    {
        // Writes page contentsSection
        $this->contentsSection = $this->writeComment("Contents section");
        $slots = array_keys($this->template->getSlots());
        
        $languageName = $this->pageTree->getAlLanguage()->getLanguage();
        $pageName = $this->pageTree->getAlPage()->getPageName();
        $blocks = $this->pageTree->getPageBlocks()->getBlocks();
        foreach ($blocks as $slotName => $blocks) {
            if (!in_array($slotName, $slots))
                continue;

            $htmlContents = array();
            foreach ($blocks as $block) {
                $content = $block->getHtmlContent();
                $content = $this->rewriteImagesPathForProduction($content);
                $content = $this->rewriteLinksForProduction($languageName, $pageName, $content);
                
                $htmlContents[] = $content;
            }

            $this->contentsSection .= $this->writeBlock($slotName, $this->writeContent($slotName, implode("\n\n", $htmlContents)));
        }
    }

    /**
     * Rewrites the images to be correctly displayed in the production environment
     */
    protected function rewriteImagesPathForProduction($content)
    {
        if(empty($this->replaceImagesPaths) && count(array_diff_key(array('backendPath' => '', 'prodPath' => ''), $this->replaceImagesPaths)) > 0) {
            return $content;
        }

        $cmsAssetsFolder = $this->replaceImagesPaths['backendPath'];
        $deployBundleAssetsFolder = $this->replaceImagesPaths['prodPath'];

        return preg_replace_callback('/(\<img[^\>]+src[="\'\s]+)(' . str_replace('/', '\/', $cmsAssetsFolder) . ')["\']?([^\>]+\>)/s', function($matches) use($deployBundleAssetsFolder){return $matches[1].$deployBundleAssetsFolder.$matches[3];}, $content);
    }
    
    protected function rewriteLinksForProduction($languageName, $pageName, $content)
    {
        $router = $this->router;
        $content = preg_replace_callback('/(\<a[^\>]+href[="\'\s]+)([^"\'\s]+)?([^\>]+\>)/s', function ($matches) use($router, $languageName, $pageName) {

            $url = $matches[2];
            try
            {
                $tmpUrl = (empty($match) && substr($url, 0, 1) != '/') ? '/' . $url : $url;
                $params = $router->match($tmpUrl);

                $url = (!empty($params)) ? sprintf("{{ path('_%s_%s') }}", $languageName, $pageName) : $url;
            }
            catch(ResourceNotFoundException $ex)
            {
                // Not internal route the link remains the same
            }

            return $matches[1] . $url . $matches[3];
        }, $content);
        
        return $content;
    }

    /**
     * Writes a comment section
     *
     * @param string $comment
     * @return string
     */
    protected function writeComment($comment)
    {
        $comment = strtoupper($comment);

        return "\n{#--------------  $comment  --------------#}\n";
    }

    /**
     * Writes a block section
     *
     * @param string $blockName
     * @param string $blockContent
     * @return string
     */
    protected function writeBlock($blockName, $blockContent)
    {
        if (empty($blockContent)) {
            return "";
        }

        $block = "{% block $blockName %}\n";
        $block .= $blockContent . "\n";
        $block .= "{% endblock %}\n\n";

        return $block;
    }

    /**
     * Writes an assetc section
     *
     * @param string $sectionName
     * @param string $assetsSection
     * @param string $sectionContent
     * @param string $filter
     * @param string $output
     * @return string
     */
    protected function writeAssetic($sectionName, $assetsSection, $sectionContent, $filter = null, $output = null)
    {
        if (empty($sectionContent)) {
            return "";
        }

        $section = $sectionName . " " . $assetsSection;
        if (null !== $filter)
            $section .= " filter=\"$filter\"";
        if (null !== $output)
            $section .= " output=\"$output\"";
        $block = "  {% $section %}\n";
        $block .= $this->identateContent($sectionContent) . "\n";
        $block .= "  {% end$sectionName %}";

        return $block;
    }

    /**
     * Writes a content section
     *
     * @param string $slotName
     * @param string $content
     * @return string
     */
    protected function writeContent($slotName, $content)
    {
        if (empty($content)) {
            return "";
        }

        $block = "  {% if(slots.$slotName is not defined) %}\n";
        $block .= $this->identateContent($content) . "\n";
        $block .= "  {% else %}\n";
        $block .= "    {{ parent() }}\n";
        $block .= "  {% endif %}";

        return $block;
    }

    /**
     * Indentates the given content
     *
     * @param string $content
     * @return string
     */
    protected function identateContent($content)
    {
        $formattedContents = array();
        $tokens = explode("\n", $content);
        foreach ($tokens as $token) {
            $formattedContents[] = "    " . $token;
        }

        return implode("\n", $formattedContents);
    }
}