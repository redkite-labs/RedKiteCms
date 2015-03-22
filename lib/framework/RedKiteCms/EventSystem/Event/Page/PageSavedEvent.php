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

namespace RedKiteCms\EventSystem\Event\Page;

use RedKiteCms\EventSystem\Event\JsonFileEvent;

/**
 * Class PageSavedEvent is the object deputed to implement the event raised when a page is saved
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Event\Page
 */
class PageSavedEvent extends JsonFileEvent
{
    /**
     * @type array
     */
    private $approvedBlocks;

    /**
     * Constructor
     *
     * @param null|string $filePath
     * @param null|string $fileContent
     * @param array $approvedBlocks
     */
    public function __construct($filePath = null, $fileContent = null, array $approvedBlocks = array())
    {
        parent::__construct($filePath, $fileContent);

        $this->approvedBlocks = $approvedBlocks;
    }

    /**
     * Returns the page approved blocks
     *
     * @return array
     */
    public function getApprovedBlocks()
    {
        return $this->approvedBlocks;
    }

    /**
     * Sets the page approved blocks
     *
     * @param array $approvedBlocks
     */
    public function setApprovedBlocks(array $approvedBlocks)
    {
        $this->approvedBlocks = $approvedBlocks;
    }
}