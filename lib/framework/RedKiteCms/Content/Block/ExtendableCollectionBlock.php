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
namespace RedKiteCms\Content\Block;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Exclude;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ExtendableCollectionBlock is the object deputed to handle a collection of extendable blocks
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\Block
 */
abstract class ExtendableCollectionBlock extends ExtendableBlock
{
    /**
     * @Type("array")
     */
    protected $children = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        if (null === $this->source) {
            $this->source = Yaml::dump($this->generateSourceFromChildren(), 100, 2);
        }

        parent::__construct();
    }

    /**
     * Generates the source from the block children
     *
     * @return array
     */
    protected function generateSourceFromChildren()
    {
        $i = 1;
        $children = array();
        foreach ($this->children as $child) {
            $childValue = Yaml::parse($child->getSource());
            $childValue["type"] = $child->getType();
            $children['item' . $i] = $childValue;
            $i++;
        }

        $source = array(
            "children" => $children,
        );

        if (!empty($this->tags)) {
            $source["tags"] = $this->tags;
        }

        return $source;
    }

    /**
     * Returns the block's children property
     *
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Sets the block's children property
     *
     * @param array $value
     */
    public function setChildren(array $value)
    {
        $this->children = $value;
    }
} 