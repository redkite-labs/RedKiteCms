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
 * Class BlockMovingAnotherSlotEvent is the object deputed to implement the event raised before moving a block to another slot
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Event\Block
 */
class BlockMovingAnotherSlotEvent extends BlockEventBase
{
    /**
     * @type string
     */
    private $sourceSlot;
    /**
     * @type string
     */
    private $targetSlot;

    /**
     * Constructor
     *
     * @param \JMS\Serializer\SerializerInterface $serializer
     * @param string $sourceSlot
     * @param string $targetSlot
     */
    public function __construct(SerializerInterface $serializer, $sourceSlot, $targetSlot)
    {
        parent::__construct($serializer);

        $this->sourceSlot = $sourceSlot;
        $this->targetSlot = $targetSlot;
    }

    /**
     * Returns the source slot
     *
     * @return string
     */
    public function getSourceSlot()
    {
        return $this->sourceSlot;
    }

    /**
     * Sets the source slot
     *
     * @return string
     */
    public function setSourceSlot($sourceSlot)
    {
        $this->sourceSlot = $sourceSlot;

        return $this;
    }

    /**
     * Returns the target slot
     *
     * @return string
     */
    public function getTargetSlot()
    {
        return $this->targetSlot;
    }

    /**
     * Sets the target slot
     *
     * @return string
     */
    public function setTargetSlot($targetSlot)
    {
        $this->targetSlot = $targetSlot;

        return $this;
    }
}