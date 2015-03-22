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

namespace RedKiteCms\EventSystem\Event\Block;

use JMS\Serializer\SerializerInterface;
use RedKiteCms\EventSystem\Event\JsonFileEvent;

/**
 * Class BlockEventBase is the object deputed to handle the base properties to generate a new block event
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Event\Block
 */
abstract class BlockEventBase extends JsonFileEvent
{
    /**
     * @type \JMS\Serializer\SerializerInterface
     */
    protected $serializer;
    /**
     * @type string
     */
    protected $blockClass;

    /**
     * Constructor
     *
     * @param \JMS\Serializer\SerializerInterface $serializer
     * @param string $filePath
     * @param string $fileContent
     * @param string $blockClass
     */
    public function __construct(SerializerInterface $serializer, $filePath = null, $fileContent = null, $blockClass = null)
    {
        parent::__construct($filePath, $fileContent);

        $this->serializer = $serializer;
        $this->blockClass = $blockClass;
    }

    /**
     * Returns the block class
     *
     * @return null|string
     */
    public function getBlockClass()
    {
        return $this->blockClass;
    }

    /**
     * Sets the block class
     * @param $blockClass
     *
     * @return $this
     */
    public function setBlockClass($blockClass)
    {
        $this->blockClass = $blockClass;

        return $this;
    }
}