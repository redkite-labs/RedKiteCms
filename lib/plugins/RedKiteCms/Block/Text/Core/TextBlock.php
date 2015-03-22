<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\Block\Text\Core;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Exclude;
use RedKiteCms\Content\Block\BaseBlock;

/**
 * Class TextBlock is the object deputed to handle an hypertext content
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Block\Text\Core
 */
class TextBlock extends BaseBlock
{
    /**
     * @Type("string")
     */
    protected $type = "Text";
    /**
     * @Type("string")
     */
    protected $editorConfiguration = "standard";
    /**
     * @Type("string")
     */
    protected $customTag = "rkcms-text";
    /**
     * @Type("string")
     */
    private $html = "hypertext_block";

    /**
     * Returns the html code
     *
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Sets the html code
     *
     * @return string
     */
    public function setHtml($v)
    {
        $this->html = $v;
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