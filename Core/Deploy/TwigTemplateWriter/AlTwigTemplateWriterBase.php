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

/**
 * AlTwigTemplateWriter generates a twig template from a PageTree object
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlTwigTemplateWriterBase extends AlTwigTemplateWriter
{

    public function writeTemplate($dir)
    {
        // Writes down the file
        $fileDir = $dir . '/' . $this->pageTree->getAlLanguage()->getLanguageName() . '/base';
        if (!is_dir($fileDir)) {
            mkdir($fileDir);
        }

        return @file_put_contents($fileDir . '/' . $this->template->getTemplateName() . '.html.twig', $this->twigTemplate);
    }

    /**
     * Generates the template's subsections and the full template itself
     */
    public function generateTemplate()
    {
        $this->generateTemplateSection();
        $this->generateContentsSection(array('site', 'language'));
        $this->generateAddictionalMetaTagsSection();

        $this->twigTemplate = $this->templateSection . $this->metatagsExtraSection . $this->contentsSection;

        return $this;
    }

    /**
     * Generates the template extension section
     */
    protected function generateTemplateSection()
    {
        $this->templateSection = sprintf("{%% extends '%s:Theme:%s.html.twig' %%}" . PHP_EOL, $this->template->getThemeName(), $this->template->getTemplateName());
    }
}
