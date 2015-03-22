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

namespace RedKiteCms\Bridge\Dispatcher;


use RedKiteCms\Bridge\Monolog\DataLogger;
use RedKiteCms\EventSystem\Event\Event;
use RedKiteCms\Exception\Event\EventAbortedException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * This object statically handles the Symfony events dispatcher
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Bridge\Assetic
 */
class Dispatcher
{
    /**
     * @type EventDispatcherInterface
     */
    private static $dispatcher = null;

    /**
     * Sets the dispatcher
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public static function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        self::$dispatcher = $dispatcher;
    }

    /**
     * Dispatches the event
     *
     * @param       $eventName
     * @param Event $event
     * @return Event
     */
    public static function dispatch($eventName, Event $event)
    {
        if (null === self::$dispatcher) {
            return $event;
        }

        DataLogger::log(sprintf('The "%s" event was dispatched', $eventName));
        self::$dispatcher->dispatch($eventName, $event);
        if ($event->getAbort()) {
            DataLogger::log(sprintf('The "%s" event was aborted', $eventName), DataLogger::ERROR);

            throw new EventAbortedException($event->getAbortMessage());
        }

        return $event;
    }
}