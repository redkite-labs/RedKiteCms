<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\EventsHandler;

/**
 * Defines the interface to implement a EventDispatcher wrapper to handle and dispatch several
 * events
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
interface EventsHandlerInterface
{
    /**
     * Returns the event by its name
     *
     * @param  string                                   $eventName
     * @return \Symfony\Component\EventDispatcher\Event
     *
     * @api
     */
    public function getEvent($eventName);

    /**
     * Creates an event from the event class
     *
     * @param  string                                                           $eventName
     * @param  string                                                           $class
     * @param  array                                                            $args
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\EventsHandler\EventsHandler
     *
     * @api
     */
    public function createEvent($eventName, $class, array $args);

    /**
     * Dispatches the event
     *
     * @param  string                                                           $eventName
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\EventsHandler\EventsHandler
     *
     * @api
     */
    public function dispatch($eventName = null);

    /**
     * Returns the event dispatcher associated with the object
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     *
     * @api
     */
    public function getEventDispatcher();
}
