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

/**
 * TemplateSectionTwig is the object deputated to implement the base methods to write
 * the sections for a  twig template
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
abstract class TemplateSectionTwig extends TemplateSection
{
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
     * {@inheritdoc}
     */
    protected function writeComment($comment)
    {
        $comment = strtoupper($comment);

        return "\n{#--------------  $comment  --------------#}" . PHP_EOL;
    }

    /**
     * {@inheritdoc}
     */
    protected function writeBlock($blockName, $blockContent, $parent = false)
    {
        if (empty($blockContent)) {
            return "";
        }

        $block = "{% block $blockName %}" . PHP_EOL;
        if ($parent) {
            $block .= '{{ parent() }}' . PHP_EOL;
        }
        $block .= $blockContent . PHP_EOL;
        $block .= "{% endblock %}\n" . PHP_EOL;

        return $block;
    }

    /**
     * {@inheritdoc}
     */
    protected function writeInlineBlock($blockName, $blockContent, $parent = false)
    {
        if (empty($blockContent)) {
            return "";
        }

        $parentToken = "";
        if ($parent) {
            $parentToken = '{{ parent() }} ';
        }

        $block = "{% block $blockName %} " . $parentToken . $blockContent . " {% endblock %}" . PHP_EOL;

        return $block;
    }

    /**
     * {@inheritdoc}
     */
    protected function writeAssetic($sectionName, $assetsSection, $sectionContent, $filter = null, $output = null)
    {
        $section = $sectionName . " " . $assetsSection;
        if (null !== $filter) {
            $section .= " filter=\"$filter\"";
        }

        if (null !== $output) {
            $section .= " output=\"$output\"";
        }

        $block = "  {% $section %}" . PHP_EOL;
        $block .= $this->identateContent($sectionContent) . "" . PHP_EOL;
        $block .= "  {% end$sectionName %}";

        return $block;
    }

    /**
     * {@inheritdoc}
     */
    protected function writeContent($slotName, $content)
    {
        $formattedContent = $this->MarkSlotContents($slotName, $content);

        if ( ! empty($content)) {
            $formattedContent = $this->identateContent($formattedContent);
        }

        return $formattedContent;
    }

    /**
     * {@inheritdoc}
     */
    protected function identateContent($content)
    {
        $formattedContents = array();
        $tokens = explode(PHP_EOL, $content);
        foreach ($tokens as $token) {
            $token = trim($token);
            if ( ! empty($token)) $formattedContents[] = "    " . $token;
        }

        return implode(PHP_EOL, $formattedContents);
    }
}
