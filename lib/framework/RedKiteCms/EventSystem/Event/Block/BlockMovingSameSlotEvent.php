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

/**
 * Class BlockMovingSameSlotEvent is the object deputed to implement the event raised before moving a block to the same slot
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Event\Block
 */
class BlockMovingSameSlotEvent extends BlockEventBase
{
    /**
     * @type array
     */
    private $blocks;
    /**
     * @type string
     */
    private $position;

    /**
     * Construct
     *
     * @param \JMS\Serializer\SerializerInterface $serializer
     * @param array $blocks
     * @param string $position
     * @param string $filePath
     * @param string $fileContent
     * @param string $blockClass
     */
    public function __construct(
        SerializerInterface $serializer,
        array $blocks,
        $position,
        $filePath = null,
        $fileContent = null,
        $blockClass = null
    ) {
        parent::__construct($serializer, $filePath, $fileContent, $blockClass);

        $this->blocks = $blocks;
        $this->position = $position;
        $this->filePath = $filePath;
        $this->fileContent = $fileContent;
    }

    /**
     * Returns the block position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Sets the block position
     * @param $position
     *
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Returns the handled blocks
     *
     * @return array
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * Sets the handled blocks
     * @param array $blocks
     *
     * @return $this
     */
    public function setBlocks(array $blocks)
    {
        $this->blocks = $blocks;

        return $this;
    }
}