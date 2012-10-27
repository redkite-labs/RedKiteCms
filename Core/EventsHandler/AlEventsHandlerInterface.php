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
 * 
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface AlEventsHandlerInterface
{
    /**
     * Returns the required event
     * 
     * @param string $eventName
     * @return \Symfony\Component\EventDispatcher\Event
     */
    public function getEvent($eventName);

    /**
     * Creates an event from the event class
     * 
     * @param string $eventName
     * @param string $class
     * @param array $args
     * @param Boolean $overrideIfExists
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandler 
     */
    public function createEvent($eventName, $class, array $args);

    /**
     * Dispatches the event
     * 
     * @param string $eventName
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandler
     * @throws type 
     */
    public function dispatch($eventName = null);
}
