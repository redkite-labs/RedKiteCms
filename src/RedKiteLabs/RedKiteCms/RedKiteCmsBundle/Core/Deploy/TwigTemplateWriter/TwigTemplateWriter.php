<?php
/*
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\TwigTemplateWriter;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree;
use RedKiteLabs\ThemeEngineBundle\Core\Theme\ThemeInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\TemplateSection\TemplateSectionTwig;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\TemplateSection\MetatagSection;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\TemplateSection\AssetSection;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\TemplateSection\ContentSection;

/**
 * TwigTemplateWriter is the object deputed to generate and write a twig template
 * from a PageTree object
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class TwigTemplateWriter extends TemplateSectionTwig
{
    /** @var MetatagSection */
    private $metatagsSection;
    /** @var AssetSection  */
    private $assetsSection;
    /** @var ContentSection */
    private $contentSection;

    /** @var string|null */
    private $twigTemplate = null;
    /** @var null|\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Language */
    private $language = null;
    /** @var null|\RedKiteLabs\ThemeEngineBundle\Core\Template\Template  */
    private $template = null;
    /** @var null|\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Page */
    private $page = null;
    /** @var string|null */
    private $baseFolder = null;
    /** @var string|null */
    private $fileName = null;

    public function __construct(MetatagSection $metatagsSection, AssetSection $assetsSection, ContentSection $contentSection)
    {
        $this->metatagsSection = $metatagsSection;
        $this->assetsSection = $assetsSection;
        $this->contentSection = $contentSection;
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
        $fileDir = $dir . '/' . $this->language->getLanguageName() . $this->baseFolder;
        if (!is_dir($fileDir)) {
            mkdir($fileDir);
        }

        return @file_put_contents($fileDir . '/' . $this->fileName . '.html.twig', $this->twigTemplate);
    }

    /**
     * Generates the template's subsections and the full template itself
     */
    public function generateTemplate(PageTree $pageTree, ThemeInterface $theme, array $options)
    {
        $this->language = $pageTree->getLanguage();
        $this->page = $pageTree->getPage();
        switch ($options["type"]) {
            case 'Base':
                $this->template = $pageTree->getTemplate();
                $this->generateBaseTemplate($pageTree, $theme, $options);
                $this->baseFolder = "/base";
                $this->fileName = $this->template->getTemplateName();
                break;
            case 'Pages':
                $this->generatePageTemplate($pageTree, $theme, $options);
                $this->baseFolder = "";
                $this->fileName = $this->page->getPageName();
                break;
        }

        return $this;
    }

    /**
     * Generates the template's subsections and the full template itself
     */
    protected function generateBaseTemplate(PageTree $pageTree, ThemeInterface $theme, array $options)
    {
        $options["filter"] = array('site', 'language');
        $this->twigTemplate = sprintf("{%% extends '%s:Theme:%s.html.twig' %%}" . PHP_EOL, $theme->getThemeName(), $this->template->getTemplateName());
        $this->twigTemplate .= $this->contentSection->generateSection($pageTree, $theme, $options);

        return $this;
    }

    /**
     * Generates the template's subsections and the full template itself
     */
    protected function generatePageTemplate(PageTree $pageTree, ThemeInterface $theme, array $options)
    {
        if ( ! $this->page->getIsPublished()) {
            $this->twigTemplate = "{% extends 'RedKiteLabsThemeEngineBundle:Frontend:unpublished.html.twig' %}";

            return $this;
        }

        $options["filter"] = array('page');
        $this->twigTemplate = sprintf("{%% extends '%s:%s:%s/base/%s.html.twig' %%}" . PHP_EOL, $options["deployBundle"], $options["templatesDir"], $this->language->getLanguageName(), $this->page->getTemplateName());
        $this->twigTemplate .= $this->metatagsSection->generateSection($pageTree, $theme, $options);
        $this->twigTemplate .= $this->assetsSection->generateSection($pageTree, $theme, $options);
        $this->twigTemplate .= $this->contentSection->generateSection($pageTree, $theme, $options);

        return $this;
    }
}
