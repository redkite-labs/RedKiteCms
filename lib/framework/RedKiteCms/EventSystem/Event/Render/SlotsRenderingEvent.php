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

namespace RedKiteCms\EventSystem\Event\Render;

use RedKiteCms\EventSystem\Event\Event;
use RedKiteCms\FilesystemEntity\Page;

/**
 * Class SlotsRenderingEvent is the object deputed to implement the event raised before rendering page slots.
 *
 * Connect to this event when you need to replace dynamically a slot content.
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Event\Render
 */
class SlotsRenderingEvent extends Event
{
    protected $slots;

    public function __construct(array $slots)
    {
        $this->slots = $slots;
    }

    /**
     * @return array
     */
    public function getSlots()
    {
        return $this->slots;
    }

    /**
     * @param array $slots
     */
    public function setSlots($slots)
    {
        $this->slots = $slots;

        return $this;
    }
}