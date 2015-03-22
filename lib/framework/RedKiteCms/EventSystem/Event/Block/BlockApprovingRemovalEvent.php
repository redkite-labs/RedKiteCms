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
 * Class BlockApprovingRemovalEvent is the object deputed to implement the event raised before approving a new block removal
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Event\Block
 */
class BlockApprovingRemovalEvent extends BlockEventBase
{
    /**
     * @type string
     */
    private $targetFile = null;

    /**
     * Constructor
     *
     * @param \JMS\Serializer\SerializerInterface $serializer
     * @param null $targetFile
     */
    public function __construct(SerializerInterface $serializer, $targetFile)
    {
        parent::__construct($serializer);

        $this->targetFile = $targetFile;
    }

    /**
     * Returns the target file
     *
     * @return null|string
     */
    public function getTargetFile()
    {
        return $this->targetFile;
    }

    /**
     * Sets the target file
     * @param $targetFile
     *
     * @return $this
     */
    public function setTargetFile($targetFile)
    {
        $this->targetFile = $targetFile;

        return $this;
    }
}