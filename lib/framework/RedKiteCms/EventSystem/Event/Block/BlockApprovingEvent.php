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
 * Class BlockApprovingEvent is the object deputed to implement the event raised before approving a new block
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Event\Block
 */
class BlockApprovingEvent extends BlockApprovingRemovalEvent
{
    /**
     * @type string $sourceFile
     */
    private $sourceFile = null;

    /**
     * Constructor
     *
     * @param SerializerInterface $serializer
     * @param null                $sourceFile
     * @param                     $targetFile
     */
    public function __construct(SerializerInterface $serializer, $sourceFile, $targetFile)
    {
        parent::__construct($serializer, $targetFile);

        $this->sourceFile = $sourceFile;
    }

    /**
     * Returns the source file
     *
     * @return null|string
     */
    public function getSourceFile()
    {
        return $this->sourceFile;
    }

    /**
     * Sets the source file
     *
     * @param $sourceFile
     * @return $this
     */
    public function setSourceFile($sourceFile)
    {
        $this->sourceFile = $sourceFile;

        return $this;
    }
}