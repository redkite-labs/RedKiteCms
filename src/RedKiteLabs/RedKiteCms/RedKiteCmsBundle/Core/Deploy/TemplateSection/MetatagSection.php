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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\TemplateSection;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\AlPageTree;
use RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface;

/**
 * AssetSection is the object deputated to generate the metatag sections of a twig template
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class MetatagSection extends TemplateSectionTwig
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

        $metatagsSection = $this->writeComment("Metatags section");
        $metatagsSection .= $this->writeInlineBlock('title', $this->pageTree->getMetaTitle());
        $metatagsSection .= $this->writeInlineBlock('description', $this->pageTree->getMetaDescription());
        $metatagsSection .= $this->writeInlineBlock('keywords', $this->pageTree->getMetaKeywords());

        return $metatagsSection;
    }
}
