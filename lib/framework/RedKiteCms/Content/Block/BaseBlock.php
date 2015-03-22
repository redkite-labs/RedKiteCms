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
use RedKiteCms\Exception\General\LogicException;

/**
 * Class BaseBlock is the object deputed to define a base block class. Every time you need to create a new
 * Block you must derive it from this base class
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\Block
 */
abstract class BaseBlock
{
    /**
     * @Type("string")
     */
    protected $slotName;

    /**
     * @Type("string")
     */
    protected $name = "";

    /**
     * @Type("string")
     */
    protected $type;

    /**
     * @Exclude
     */
    protected $translator;

    /**
     * @Type("string")
     */
    protected $customTag = null;

    /**
     * @Type("string")
     */
    protected $historyName = "";

    /**
     * @Type("array")
     */
    protected $history = array();

    /**
     * @Type("integer")
     */
    protected $revision = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translate();
    }

    /**
     * Translates the block
     */
    protected function translate()
    {
        $translatorOptions = $this->getTranslatorOptions();
        if (empty($translatorOptions) && !array_key_exists("fields", $translatorOptions)) {
            return;
        }

        $params = array();
        if (array_key_exists("params", $translatorOptions)) {
            $params = $translatorOptions["params"];
        }

        $domain = "RedKiteCms";
        if (array_key_exists("domain", $translatorOptions)) {
            $domain = $translatorOptions["domain"];
        }

        foreach ($translatorOptions["fields"] as $field) {
            $field = ucfirst($field);
            $method = 'get' . $field;
            $v = \RedKiteCms\Bridge\Translation\Translator::translate($this->$method(), $params, $domain);
            $method = 'set' . $field;
            $this->$method($v);
        }
    }

    /**
     * Override this method to define the options to set up the translator.
     * Valid options can be domain and fields.
     *
     * The domain is the Symfony2 message domain where the block translation live
     * while fields is an array of fields which will be translated by the translator
     *
     * @return array
     */
    protected function getTranslatorOptions()
    {
        return array();
    }

    /**
     * @return mixed
     */
    public function getSlotName()
    {
        return $this->slotName;
    }

    /**
     * @param $slotName
     */
    public function setSlotName($slotName)
    {
        $this->slotName = $slotName;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        if (null === $this->type) {
            throw new LogicException(
                'A derived class must always define the block type. Please define a protected property $type to set up the block type.'
            );
        }

        return $this->type;
    }

    /**
     * @param $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return null
     */
    public function getCustomTag()
    {
        if (null === $this->customTag) {
            throw new \LogicException(
                'A derived class must always define the block custom tag property. Please define a protected property $customTag to set up the custom tag which will be used to render your block.'
            );
        }

        return $this->customTag;
    }

    /**
     * @param $customTag
     */
    public function setCustomTag($customTag)
    {
        $this->customTag = $customTag;
    }

    /**
     * @return array
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * @param array $history
     */
    public function setHistory(array $history)
    {
        $this->history = $history;
    }

    /**
     * @return string
     */
    public function getHistoryName()
    {
        return $this->historyName;
    }

    /**
     * @param $historyName
     */
    public function setHistoryName($historyName)
    {
        $this->historyName = $historyName;
    }
} 