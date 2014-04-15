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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\TemplateSection;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree;
use RedKiteLabs\ThemeEngineBundle\Core\Theme\ThemeInterface;

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
     * @param \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree     $pageTree
     * @param \RedKiteLabs\ThemeEngineBundle\Core\Theme\ThemeInterface $theme
     * @param array                                                      $options
     */
    public function generateSection(PageTree $pageTree, ThemeInterface $theme, array $options)
    {
        parent::generateSection($pageTree, $theme, $options);

        $metatagsSection = $this->writeComment("Metatags section");
        $metatagsSection .= $this->writeInlineBlock('title', $this->pageTree->getMetaTitle());
        $metatagsSection .= $this->writeInlineBlock('description', $this->pageTree->getMetaDescription());
        $metatagsSection .= $this->writeInlineBlock('keywords', $this->pageTree->getMetaKeywords());

        return $metatagsSection;
    }
}
