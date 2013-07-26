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

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\General\InvalidArgumentException;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\General\RuntimeException;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;
use Symfony\Component\EventDispatcher\Event;

/**
 * A base EventsHandler object to create the events and dispatch them when required
 *
 * When an event already exists, it is recreated
 *
 * @author alphalemon <webmaster@alphalemon.com>
 *
 * @api
 */
abstract class AlEventsHandler implements AlEventsHandlerInterface
{
    private $events = array();
    private $eventDispatcher;
    private $methods;

    /**
     * Configures the methods that will be evaluated and valorized when a new
     * event is created
     *
     * @api
     */
    abstract protected function configureMethods();

    /**
     * Constructor
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     *
     * @api
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->methods = $this->configureMethods();
    }

    /**
     * {@inheritdoc}
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * Returns the handled events
     *
     * @return array
     *
     * @api
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * {@inheritdoc}
     */
    public function getEvent($eventName)
    {
        return $this->fetchEvent($eventName);
    }

    /**
     * {@inheritdoc}
     * 
     * @throws InvalidArgumentException
     * @throws InvalidArgumentTypeException
     */
    public function createEvent($eventName, $class, array $args)
    {
        if ( ! is_string($eventName)) {
            $exception = array(
                'message' => '"%className%" createEvent method requires the eventName argument to be a string',
                'parameters' => array(
                    '%className%' => get_class($this),
                ),
            );
            throw new InvalidArgumentException(json_encode($exception));
        }

        if ( ! class_exists($class)) {
            $exception = array(
                'message' => 'The class "%argumentClass%" passed as argument for the "%className%" createEvent method does not exist',
                'parameters' => array(
                    '%argumentClass%' => get_class($this),                    
                    '%className%' => get_class($this),
                ),
            );
            throw new InvalidArgumentException(json_encode($exception));
        }

        // When the event already exists, it is recreated
        $event = $this->fetchEvent($eventName);
        if (null !== $event) {unset($this->events[$eventName]);}

        $event = new $class();
        if ( ! $event instanceof Event) {
            $exception = array(
                'message' => 'The class "%argumentClass%" passed as argument for the "%className%" createEvent must be an instance of "Symfony\Component\EventDispatcher\Event"',
                'parameters' => array(
                    '%argumentClass%' => get_class($this),                    
                    '%className%' => get_class($this),
                ),
            );
            throw new InvalidArgumentTypeException(json_encode($exception));
        }

        $this->events[$eventName] = $event;

        $numberOfArgs = count($args);
        if ($numberOfArgs == 0) return $this;

        $methods = array_slice($this->methods, 0, $numberOfArgs);
        $callables = array_combine($methods, $args);

        // Valorizes the event's methods
        foreach ($callables as $method => $arg) {
            call_user_func(array($event, $method), $arg);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException
     */
    public function dispatch($eventName = null)
    {
        // Tries to fetch the event by the given eventname
        $event = null;
        if (null !== $eventName) {
            $event = $this->fetchEvent($eventName);
        }

        // Fetches the last saved event
        if (null === $event && !empty($this->events)) {
            $eventNames = array_keys($this->events);
            $events = array_values($this->events);
            $elemens = count($events) - 1;
            $event = $events[$elemens];
            $eventName = $eventNames[$elemens];
        }

        if (null === $event) {
            throw new RuntimeException('Any event has been found to be dispatched');
        }

        $this->eventDispatcher->dispatch($eventName, $event);

        return $this;
    }

    /**
     * Returns the requested event if exists
     *
     * @param  string                                   $eventName
     * @return \Symfony\Component\EventDispatcher\Event
     */
    protected function fetchEvent($eventName)
    {
        return (array_key_exists($eventName, $this->events)) ? $this->events[$eventName] : null;
    }
}
