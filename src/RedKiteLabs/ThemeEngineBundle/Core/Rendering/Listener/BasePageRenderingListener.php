<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestListener
 *
 * @author giansimon
 */

namespace AlphaLemon\ThemeEngineBundle\Core\Rendering\Listener;


use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\ThemeEngineBundle\Core\Rendering\Event\PageRenderer\BeforePageRenderingEvent;
use AlphaLemon\ThemeEngineBundle\Core\Rendering\SlotContent\AlSlotContent;

abstract class BasePageRenderingListener
{
    protected $container;

    abstract protected function renderSlotContent();
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    
    /**
    * Handles the event when notified or filtered.
    *
    * @param Event $event
    */
    public function onPageRendering(BeforePageRenderingEvent $event)
    {
        $slotContent = $this->renderSlotContent();
        if (!$slotContent instanceof AlSlotContent) {
           throw new \RuntimeException('"renderSlotContent" method must return a "AlphaLemon\ThemeEngineBundle\Core\Rendering\SlotContent\AlSlotContent" object');
        }
        
        if (null === $slotContent->getSlotName()) {
           throw new \RuntimeException('Any slot defined for the event ' . class_name($this));
        }
        
        if (null === $slotContent->isReplacing()) {
           throw new \RuntimeException('Any action has been specified for the event ' . class_name($this));
        }
        
        if (null === $slotContent->getContent()) {
           return;
        }
        
        $response = $event->getResponse();
        $content = $response->getContent(); 
        $content = ($slotContent->isReplacing()) ? $this->replaceContent($slotContent, $content) : $this->injectContent($slotContent, $content);
        if (null !== $content) {
            $response->setContent($content);
            $event->setResponse($response);
        }
    }
    
    protected function replaceContent(AlSlotContent $slotContent, $content)
    {
        $regex = $this->fetchPattern($slotContent->getSlotName());
        
        return preg_replace($regex, $slotContent->getContent(), $content);
    }
    
    protected function injectContent(AlSlotContent $slotContent, $content)
    {
        $regex = $this->fetchPattern($slotContent->getSlotName());
        preg_match($regex, $content, $match); 
        if (empty($match)) return;
        
        $newContent = $match[1] . PHP_EOL . $slotContent->getContent();
        
        return preg_replace($regex, $newContent, $content);
    }
    
    private function fetchPattern($slotName)
    {
        return sprintf('/\<!-- BEGIN %s BLOCK --\>(.*?)\<!-- END %s BLOCK --\>/s', strtoupper($slotName), strtoupper($slotName));
    }
}
