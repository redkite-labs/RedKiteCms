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

namespace RedKiteCms\EventSystem\Event;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

/**
 * Class Event is the object deputed to handle the base properties for a RedKite CMS event
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Event
 */
abstract class Event extends BaseEvent
{
    /**
     * @type bool
     */
    private $abort = false;
    /**
     * @type string
     */
    private $abortMessage = "exception_abort_message";

    /**
     * Return the event abort status
     *
     * @return bool
     */
    public function getAbort()
    {
        return $this->abort;
    }

    /**
     * Sets the event as aborted
     * @param $value
     *
     * @return $this
     */
    public function setAbort($value)
    {
        $this->abort = $value;

        return $this;
    }

    /**
     * Returns the abort message
     *
     * @return string
     */
    public function getAbortMessage()
    {
        return $this->abortMessage;
    }

    /**
     * Sets the abort message
     * @param $value
     *
     * @return $this
     */
    public function setAbortMessage($value)
    {
        $this->abortMessage = $value;

        return $this;
    }
}