<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler;

/**
 * Defines the interface to implement a EventDispatcher wrapper to handle and dispatch several 
 * events
 *
 * @author alphalemon <webmaster@alphalemon.com>
 * 
 * @api
 */
interface AlEventsHandlerInterface
{
    /**
     * Returns the event by its name
     * 
     * @param string $eventName
     * @return \Symfony\Component\EventDispatcher\Event
     * 
     * @api
     */
    public function getEvent($eventName);

    /**
     * Creates an event from the event class
     * 
     * @param string $eventName
     * @param string $class
     * @param array $args
     * @param boolean $overrideIfExists
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandler
     * 
     * @api
     */
    public function createEvent($eventName, $class, array $args);

    /**
     * Dispatches the event
     * 
     * @param string $eventName
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandler
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
