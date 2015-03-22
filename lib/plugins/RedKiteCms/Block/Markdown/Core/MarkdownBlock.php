<?php
/**
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

namespace RedKiteCms\Block\Markdown\Core;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Exclude;
use RedKiteCms\Content\Block\ExtendableBlock;

/**
 * Class MarkdownBlock is the object deputed to handle a markdown content
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Block\Markdown\Core
 */
class MarkdownBlock extends ExtendableBlock
{
    /**
     * @Type("string")
     */
    protected $type = "Markdown";
    /**
     * @Type("string")
     */
    protected $customTag = "rkcms-markdown";
    /**
     * @Type("string")
     */
    private $html = "Markdown content";
    /**
     * @Type("string")
     */
    private $markdown = "Markdown content";

    /**
     * Constructor
     *
     * @param null $value
     * @param null $tags
     */
    public function __construct($value = null, $tags = null)
    {
        if (null === $this->source) {
            $this->source = $this->html;
        }
    }

    /**
     * Returns the generated html
     *
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Sets the html
     *
     * @return string
     */
    public function setHtml($v)
    {
        $this->html = $v;
    }

    /**
     * Returns the markdown content
     *
     * @return string
     */
    public function getMarkdown()
    {
        return $this->markdown;
    }

    /**
     * Sets the markdown content
     *
     * @param string $markdown
     */
    public function setMarkdown($markdown)
    {
        $this->markdown = $markdown;
    }

    /**
     * @inheritdoc
     */
    protected function getTranslatorOptions()
    {
        return array(
            "fields" => array('html'),
        );
    }
} 