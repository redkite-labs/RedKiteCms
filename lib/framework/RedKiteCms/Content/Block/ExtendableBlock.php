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
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ExtendableBlock is the object deputed to define an extendable block, which means a block you can extend its
 * properties.
 *
 * For example when you manage a block link, you can extend it adding an arbitrary "class" property to tags parameter
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\Block
 */
abstract class ExtendableBlock extends BaseBlock
{
    /**
     * @Type("string")
     */
    protected $value = "";
    /**
     * @Type("array")
     */
    protected $tags = array();
    /**
     * @Type("string")
     */
    protected $source = null;

    /**
     * Constructor
     *
     * @param string $value
     * @param array $tags
     */
    public function __construct($value = null, array $tags = null)
    {
        parent::__construct();

        if (null === $this->source) {
            if (null !== $value) {
                $this->value = $value;
            }
            if (null !== $tags) {
                $this->tags = $tags;
            }

            $this->updateSource();
        }
    }

    /**
     * Updates the block source
     */
    public function updateSource()
    {
        $source = array(
            "value" => $this->value,
            "tags" => $this->tags,
        );

        $this->source = Yaml::dump($source, 100, 2);
    }

    /**
     * Returns the block's source property
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Sets the block's source property
     *
     * @return string
     */
    public function setSource($value)
    {
        $this->source = $value;
    }

    /**
     * Returns the block's value property
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the block's value property
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Returns the block's tags property
     *
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Returns the block's tags property
     *
     * @param array $tags
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
    }

    /**
     * @inheritdoc
     */
    protected function getTranslatorOptions()
    {
        return array(
            "fields" => array('value'),
        );
    }
} 