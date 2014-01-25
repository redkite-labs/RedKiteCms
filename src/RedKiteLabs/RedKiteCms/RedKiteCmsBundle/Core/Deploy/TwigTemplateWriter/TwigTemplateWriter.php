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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Deploy\TwigTemplateWriter;

use RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree;
use RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\TemplateSection\TemplateSectionTwig;
use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\TemplateSection\MetatagSection;
use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\TemplateSection\AssetSection;
use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\TemplateSection\ContentSection;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\Deprecated\RedKiteDeprecatedException;

/**
 * AlTwigTemplateWriter is the object deputated to generate and write a twig template
 * from a PageTree object
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class TwigTemplateWriter extends TemplateSectionTwig
{
    private $metatagsSection;
    private $assetsSection;
    private $contentSection;

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
    public function generateTemplate(AlPageTree $pageTree, AlThemeInterface $theme, array $options)
    {
        $this->language = $pageTree->getAlLanguage();
        $this->page = $pageTree->getAlPage();
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
    protected function generateBaseTemplate(AlPageTree $pageTree, AlThemeInterface $theme, array $options)
    {
        $options["filter"] = array('site', 'language');
        $this->twigTemplate = sprintf("{%% extends '%s:Theme:%s.html.twig' %%}" . PHP_EOL, $theme->getThemeName(), $this->template->getTemplateName());
        $this->twigTemplate .= $this->contentSection->generateSection($pageTree, $theme, $options);

        return $this;
    }

    /**
     * Generates the template's subsections and the full template itself
     */
    protected function generatePageTemplate(AlPageTree $pageTree, AlThemeInterface $theme, array $options)
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
