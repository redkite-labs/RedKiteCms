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
namespace RedKiteCms\Block\IconStacked\Core;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Exclude;
use RedKiteCms\Block\Icon\Core\IconBlock;
use RedKiteCms\Content\Block\ExtendableCollectionBlock;

/**
 * Class IconStackedBlock is the object deputed to handle a FontAwesome stacked icon
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Block\IconStacked\Core
 */
class IconStackedBlock extends ExtendableCollectionBlock
{
    /**
     * @Type("string")
     */
    protected $value = "stacked_icon_default_value";
    /**
     * @Type("array")
     */
    protected $tags = array(
        'class' => 'fa-stack fa-lg',
    );
    /**
     * @Type("string")
     */
    protected $type = "IconStacked";
    /**
     * @Type("string")
     */
    protected $customTag = "rkcms-icon-stacked";
    /**
     * Contructor
     */
    public function __construct()
    {
        $this->children = array(
            new IconBlock("", array('class' => "fa fa-circle-o fa-stack-2x")),
            new IconBlock("", array('class' => "fa fa-cog fa-stack-1x")),
        );

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function generateSourceFromChildren()
    {
        $this->translate();
        $source = parent::generateSourceFromChildren();
        $source["value"] = $this->value;

        return $source;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTranslatorOptions()
    {
        return array(
            "fields" => array('value'),
        );
    }
} 