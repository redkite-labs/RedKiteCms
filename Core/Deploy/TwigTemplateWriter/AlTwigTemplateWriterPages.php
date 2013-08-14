<?php

/*
 * This file is part of the RedKite CMS Application and it is distributed
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
use RedKiteLabs\RedKiteCmsBundle\Core\UrlManager\AlUrlManagerInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\ViewRenderer\AlViewRendererInterface;

/**
 * AlTwigTemplateWriter generates a twig template from a PageTree object
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlTwigTemplateWriterPages extends AlTwigTemplateWriter
{
    protected $deployBundle;
    protected $templatesFolder;

    public function __construct(AlPageTree $pageTree, AlBlockManagerFactoryInterface $blockManagerFactory, AlUrlManagerInterface $urlManager, $deployBundle, $templatesFolder, AlViewRendererInterface $viewRenderer, array $replaceImagesPaths = array())
    {
        $this->deployBundle = $deployBundle;
        $this->templatesFolder = $templatesFolder;

        parent::__construct($pageTree, $blockManagerFactory, $urlManager, $viewRenderer, $replaceImagesPaths);
    }

    /**
     * Generates the template's subsections and the full template itself
     */
    public function generateTemplate()
    {
        $this->generateTemplateSection();
        $this->generateMetaTagsSection();
        $this->generateAssetsSection();
        $this->generateContentsSection(array('page'));
        $this->generateAddictionalMetaTagsSection();

        $this->twigTemplate = $this->templateSection . $this->metatagsSection . $this->metatagsExtraSection . $this->assetsSection . $this->contentsSection;

        return $this;
    }

    /**
     * Generates the template extension section
     */
    protected function generateTemplateSection()
    {
        $this->templateSection = sprintf("{%% extends '%s:%s:%s/base/%s.html.twig' %%}" . PHP_EOL, $this->deployBundle, $this->templatesFolder, $this->pageTree->getAlLanguage()->getLanguageName(), $this->pageTree->getAlPage()->getTemplateName());
    }
}
