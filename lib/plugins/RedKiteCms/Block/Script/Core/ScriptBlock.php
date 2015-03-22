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

namespace RedKiteCms\Block\Script\Core;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Exclude;
use RedKiteCms\Content\Block\ExtendableBlock;

/**
 * Class ScriptBlock is the object deputed to handle an html script
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Block\Script\Core
 */
class ScriptBlock extends ExtendableBlock
{
    /**
     * @Type("string")
     */
    protected $type = "Script";
    /**
     * @Type("string")
     */
    protected $customTag = "rkcms-script";
    /**
     * @Type("string")
     */
    private $html = "";

    /**
     * Constructor
     *
     * @param TranslatorInterface $translator
     */
    public function __construct($value = null, $tags = null)
    {
        if (null === $this->source) {
            $this->source = $this->html;
        }
    }

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