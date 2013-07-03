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

use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;
use AlphaLemon\AlphaLemonCmsBundle\Core\UrlManager\AlUrlManagerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\ViewRenderer\AlViewRendererInterface;

/**
 * AlTwigTemplateWriter generates a twig template from a PageTree object
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 *
 * @api
 */
abstract class AlTwigTemplateWriter
{
    protected $pageTree;
    protected $urlManager;
    protected $template;
    protected $twigTemplate = null;
    protected $templateSection;
    protected $metatagsExtraSection;
    protected $metatagsSection;
    protected $assetsSection;
    protected $contentsSection;
    protected $blockManagerFactory;
    protected $metatagsExtraContents = "";
    protected $viewRenderer;
    protected $credits = true;

    /**
     * Generates the template's subsections and the full template itself
     */
    abstract public function generateTemplate();

    /**
     * Constructor
     *
     * The replaceImagesPaths contains the backend images' path and the production images path, as follows:
     *
     *      array(
     *          backendPath => '/path/to/backend/images,
     *          prodPath    => '/path/to/prod/images,
     *      )
     *
     * When the page is saving, the images' path is replaced
     *
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree                          $pageTree
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface $blockManagerFactory
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\UrlManager\AlUrlManagerInterface             $urlManager
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\ViewRenderer\AlViewRendererInterface         $viewRenderer
     * @param array                                                                             $replaceImagesPaths
     */
    public function __construct(AlPageTree $pageTree, AlBlockManagerFactoryInterface $blockManagerFactory, AlUrlManagerInterface $urlManager, AlViewRendererInterface $viewRenderer, array $replaceImagesPaths = array())
    {
        $this->pageTree = $pageTree;
        $this->blockManagerFactory = $blockManagerFactory;
        $this->urlManager = $urlManager;
        $this->viewRenderer = $viewRenderer;
        $this->replaceImagesPaths = $replaceImagesPaths;
        $this->template = $this->pageTree->getTemplate();
    }

    /**
     * Returns the generated template
     *
     * @return string
     *
     * @api
     */
    public function getTwigTemplate()
    {
        return $this->twigTemplate;
    }

    /**
     * Returns the template extend directive
     *
     * @return string
     *
     * @api
     */
    public function getTemplateSection()
    {
        return $this->templateSection;
    }

    /**
     * Returns the metatags section
     *
     * @return string
     *
     * @api
     */
    public function getMetaTagsSection()
    {
        return $this->metatagsSection;
    }

    /**
     * Returns the assets section
     *
     * @return string
     *
     * @api
     */
    public function getAssetsSection()
    {
        return $this->assetsSection;
    }

    /**
     * Returns the contents section
     *
     * @return string
     *
     * @api
     */
    public function getContentsSection()
    {
        return $this->contentsSection;
    }

    /**
     * Forces the CMS to render the credits or not
     *
     * @param  boolean                                                                             $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\TwigTemplateWriter\AlTwigTemplateWriter
     */
    public function setCredits($v)
    {
        $this->credits = $v;

        return $this;
    }

    /**
     * Writes the template
     *
     * @param  string  $dir
     * @return boolean
     *
     * @api
     */
    public function writeTemplate($dir)
    {
        // Writes down the file
        $fileDir = $dir . '/' . $this->pageTree->getAlLanguage()->getLanguageName();
        if (!is_dir($fileDir)) {
            mkdir($fileDir);
        }

        return @file_put_contents($fileDir . '/' . $this->pageTree->getAlPage()->getPageName() . '.html.twig', $this->twigTemplate);
    }

    /**
     * Generates the template extension section
     */
    protected function generateTemplateSection()
    {
        $this->templateSection = sprintf("{%% extends '%s:Theme:%s.html.twig' %%}" . PHP_EOL, $this->template->getThemeName(), $this->pageTree->getAlPage()->getTemplateName());
    }

    /**
     * Generates the metatags section
     */
    protected function generateMetaTagsSection()
    {
        $this->metatagsSection = $this->writeComment("Metatags section");
        $this->metatagsSection .= $this->writeInlineBlock('title', $this->pageTree->getMetaTitle());
        $this->metatagsSection .= $this->writeInlineBlock('description', $this->pageTree->getMetaDescription());
        $this->metatagsSection .= $this->writeInlineBlock('keywords', $this->pageTree->getMetaKeywords());
    }

    /**
     * Adds extra metatags than the default ones
     */
    protected function generateAddictionalMetaTagsSection()
    {
        if ( ! empty($this->metatagsExtraContents)) {
            $this->metatagsExtraSection = $this->writeComment("Metatags extra section");
            $this->metatagsExtraSection .= $this->writeBlock('metatags', $this->metatagsExtraContents);
        }
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
        $container = $this->pageTree->getContainer();
        $yuiEnabled = $container->getParameter('alpha_lemon_cms.enable_yui_compressor');
        if (!empty($externalStylesheets)) {
            $sectionContent = '<link href="{{ asset_url }}" rel="stylesheet" type="text/css" media="all" />';
            $filter = $yuiEnabled ? '?yui_css,cssrewrite' : '?cssrewrite';
            $this->assetsSection .= $this->writeBlock('external_stylesheets', $this->writeAssetic('stylesheets', implode(' ', array_map(function($value){ return '"' . $value . '"'; }, $externalStylesheets )), $sectionContent, $filter));
        }

        if (!empty($externalJavascripts)) {
            $sectionContent = '<script src="{{ asset_url }}"></script>';
            $filter = $yuiEnabled ? '?yui_js' : '';
            $this->assetsSection .= $this->writeBlock('external_javascripts', $this->writeAssetic('javascripts', implode(' ', array_map(function($value){ return '"' . $value . '"'; }, $externalJavascripts )), $sectionContent, $filter));
        }

        if (!empty($internalStylesheet)) {
            $this->assetsSection .= $this->writeBlock('internal_header_stylesheets', '<style>' . $internalStylesheet . '</style>');
        }

        if (!empty($internalJavascript)) {
            $this->assetsSection .= $this->writeBlock('internal_header_javascripts', '<script>$(document).ready(function(){' . $this->rewriteImagesPathForProduction($internalJavascript) . '});</script>');
        }
    }

    /**
     * Generates the contents section
     */
    protected function generateContentsSection($filter = null)
    {
        // Writes page contentsSection
        $this->contentsSection = $this->writeComment("Contents section");
        $slots = array_keys($this->template->getSlots());

        $needsCredits = $this->credits;
        $pageBlocks = $this->pageTree->getPageBlocks()->getBlocks();

        $blocks = (null !== $filter) ? $this->filterBlocks($pageBlocks, $filter) : $pageBlocks;
        foreach ($blocks as $slotName => $slotBlocks) {
            if ( ! in_array($slotName, $slots)) {
                // @codeCoverageIgnoreStart
                continue;
                // @codeCoverageIgnoreEnd
            }

            $contentMetatags = array();
            $htmlContents = array();
            foreach ($slotBlocks as $block) {
                $content = "";
                $blockManager = $this->blockManagerFactory->createBlockManager($block);
                if (null !== $blockManager) {
                    $blockManager->setPageTree($this->pageTree);
                    $blockManager->setEditorDisabled(true);
                    $content = $blockManager->getHtml();
                    if (is_array($content)) {
                        $content = $this->viewRenderer->render($content['RenderView']);
                    }

                    $content = $this->rewriteImagesPathForProduction($content);
                    $content = $this->rewriteLinksForProduction($content);

                    // @codeCoverageIgnoreStart
                    if ($needsCredits && $slotName == 'alphalemon_love' && preg_match('/\<a[^\>]+href="http:\/\/alphalemon\.com[^\>]+\>powered by alphalemon cms\<\/a\>/is', strtolower($content))) {
                        $needsCredits = false;
                    }
                    // @codeCoverageIgnoreEnd

                    $metatags = $blockManager->getMetaTags();
                    if (null !== $metatags) {
                        $contentMetatags[] = (is_array($metatags)) ? $this->viewRenderer->render($metatags['RenderView']) : $metatags;
                    }
                }

                $htmlContents[] = $content;
            }

            if ( ! empty($contentMetatags)) {
                $this->metatagsExtraContents = implode("\n", $contentMetatags);
            }
            $this->contentsSection .= $this->writeBlock($slotName, $this->writeContent($slotName, implode("\n" . PHP_EOL, $htmlContents)));
        }

        $template = $this->pageTree->getTemplate();
        if (null === $template) {
            // @codeCoverageIgnoreStart
            return;
            // @codeCoverageIgnoreEnd
        }

        $templateSlots = $template->getTemplateSlots();
        $slots = $templateSlots->getSlots();
        if (null !== $slots) {
            $orphanSlots = array_diff_key($slots, $pageBlocks);
            foreach ($orphanSlots as $slot) {
                $slotName = $slot->getSlotName();
                $this->contentsSection .= $this->writeBlock($slotName, $this->writeContent($slotName, ""));
            }
        }

        if ($needsCredits) {
            $this->contentsSection .= '{% block internal_header_stylesheets %}' . PHP_EOL;
            $this->contentsSection .= '  {{ parent() }}' . PHP_EOL. PHP_EOL;
            $this->contentsSection .= '  <style>.al-credits{width:100%;background-color:#fff;text-align:center;padding:6px;border-top:1px solid #000;margin-top:1px;}.al-credits a{color:#333;}.al-credits a:hover{color:#C20000;}</style>' . PHP_EOL;
            $this->contentsSection .= '{% endblock %}' . PHP_EOL. PHP_EOL;
            $this->contentsSection .= '{% block body %}' . PHP_EOL;
            $this->contentsSection .= '  {{ parent() }}' . PHP_EOL. PHP_EOL;
            $this->contentsSection .= '  <div class="al-credits"><a href="http://alphalemon.com">Powered by AlphaLemon CMS</div>' . PHP_EOL;
            $this->contentsSection .= '{% endblock %}' . PHP_EOL;
        }
    }

    /**
     * Rewrites the images to be correctly displayed in the production environment
     */
    protected function rewriteImagesPathForProduction($content)
    {
        if (empty($this->replaceImagesPaths) && count(array_diff_key(array('backendPath' => '', 'prodPath' => ''), $this->replaceImagesPaths)) > 0) {
            return $content;
        }

        $cmsAssetsFolder = $this->replaceImagesPaths['backendPath'];
        $deployBundleAssetsFolder = $this->replaceImagesPaths['prodPath'];

        return preg_replace_callback('/([\/]?)(' . str_replace('/', '\/', $cmsAssetsFolder) . ')/s', function($matches) use ($deployBundleAssetsFolder) {return $matches[1].$deployBundleAssetsFolder;}, $content);
    }

    protected function rewriteLinksForProduction($content)
    {
        $urlManager = $this->urlManager;

        return preg_replace_callback('/(\<a[^\>]+href[="\'\s]+)([^"\'\s]+)?([^\>]+\>)/s', function ($matches) use ($urlManager) {
            $url = $matches[2];
            
            if (preg_match('/route:(.*)/i', $url, $route)) {
                $url = sprintf("{{ path(%s) }}", html_entity_decode($route[1], ENT_QUOTES));
                
                return $matches[1] . $url . $matches[3];
            }
            
            $route = $urlManager
                ->fromUrl($url)
                ->getProductionRoute();
            
            if (null !== $route) {
                $url = sprintf("{{ path('%s') }}", $route);
            }

            return $matches[1] . $url . $matches[3];
        }, $content);

        return $content;
    }

    /**
     * Writes a comment section
     *
     * @param  string $comment
     * @return string
     */
    protected function writeComment($comment)
    {
        $comment = strtoupper($comment);

        return "\n{#--------------  $comment  --------------#}" . PHP_EOL;
    }

    /**
     * Writes a block section
     *
     * @param  string $blockName
     * @param  string $blockContent
     * @return string
     */
    protected function writeBlock($blockName, $blockContent)
    {
        if (empty($blockContent)) {
            // @codeCoverageIgnoreStart
            return "";
            // @codeCoverageIgnoreEnd
        }

        $block = "{% block $blockName %}" . PHP_EOL;
        $block .= $blockContent . PHP_EOL;
        $block .= "{% endblock %}\n" . PHP_EOL;

        return $block;
    }

    /**
     * Writes a block section without carriage return
     *
     * @param  string $blockName
     * @param  string $blockContent
     * @return string
     */
    protected function writeInlineBlock($blockName, $blockContent)
    {
        if (empty($blockContent)) {
            return "";
        }

        $block = "{% block $blockName %}" . $blockContent . "{% endblock %}" . PHP_EOL;

        return $block;
    }

    /**
     * Writes an assetc section
     *
     * @param  string $sectionName
     * @param  string $assetsSection
     * @param  string $sectionContent
     * @param  string $filter
     * @param  string $output
     * @return string
     */
    protected function writeAssetic($sectionName, $assetsSection, $sectionContent, $filter = null, $output = null)
    {
        $section = $sectionName . " " . $assetsSection;
        if (null !== $filter)
            $section .= " filter=\"$filter\"";
        if (null !== $output)
            $section .= " output=\"$output\"";
        $block = "  {% $section %}" . PHP_EOL;
        $block .= $this->identateContent($sectionContent) . "" . PHP_EOL;
        $block .= "  {% end$sectionName %}";

        return $block;
    }

    /**
     * Writes a content section
     *
     * @param  string $slotName
     * @param  string $content
     * @return string
     */
    protected function writeContent($slotName, $content)
    {
        $formattedContent = $this->MarkSlotContents($slotName, $content);

        if (!empty($content)) {
            $formattedContent = $this->identateContent($formattedContent) . PHP_EOL;
            $formattedContent .= "  {% else %}" . PHP_EOL;
            $formattedContent .= "    {{ parent() }}" . PHP_EOL;
        }

        $block = "  {% if(slots.$slotName is not defined) %}" . PHP_EOL;
        $block .= $formattedContent;
        $block .= "  {% endif %}";

        return $block;
    }

    /**
     * Marks the contents of the given slot with a Begin/End comment
     *
     * @param  string $slotName
     * @param  string $content
     * @return string
     */
    public static function MarkSlotContents($slotName, $content)
    {
        $commentSkeleton = '<!-- %s %s BLOCK -->';
        $slotName = strtoupper($slotName);

        return PHP_EOL . sprintf($commentSkeleton, "BEGIN", $slotName) . PHP_EOL . $content . PHP_EOL . sprintf($commentSkeleton, "END", $slotName) . PHP_EOL;
    }

    /**
     * Indentates the given content
     *
     * @param  string $content
     * @return string
     */
    protected function identateContent($content)
    {
        $formattedContents = array();
        $tokens = explode(PHP_EOL, $content);
        foreach ($tokens as $token) {
            $token = trim($token);
            if(!empty($token)) $formattedContents[] = "    " . $token;
        }

        return implode(PHP_EOL, $formattedContents);
    }

    protected function filterBlocks(array $blocks, array $filter)
    {
        $template = $this->template;

        return array_filter($blocks, function($slotBlocks) use ($template, $filter) {

            if (count($slotBlocks) == 0) {
                // @codeCoverageIgnoreStart
                return false;
                // @codeCoverageIgnoreEnd
            }

            $slotName = $slotBlocks[0]->getSlotName();
            $slot = $template->getSlot($slotName);

            if (null === $slot) {
                return false;
            }
            
            $repeated = (null !== $slot->getForceRepeatedDuringDeploying()) ? $slot->getForceRepeatedDuringDeploying() : $slot->getRepeated(); 
            
            return in_array($repeated, $filter);
        });
    }
}
