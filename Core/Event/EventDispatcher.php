<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Event; 

use Symfony\Component\EventDispatcher\Event;

/**
 * EventDispatcher
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class EventDispatcher {
    private $dispatcher;

    public function __construct(\Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher) 
    {
        $this->dispatcher = $dispatcher;
    }
    
    public function dispatch($eventName, Event $event) 
    {
         $this->dispatcher->dispatch($eventName, $event);
         
         return $event;
    }
}