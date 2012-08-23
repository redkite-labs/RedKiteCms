<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 *
 * @license    MIT License
 */

namespace AlphaLemon\ThemeEngineBundle\Core\Rendering\Listener;


use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\ThemeEngineBundle\Core\Rendering\Event\PageRenderer\BeforePageRenderingEvent;
use AlphaLemon\ThemeEngineBundle\Core\Rendering\SlotContent\AlSlotContent;
use Symfony\Component\HttpFoundation\Response;

/**
 * BasePageRenderingListener
 *
 * @author AlphaLemon
 */
abstract class BasePageRenderingListener
{
    protected $container;

    abstract protected function renderSlotContents();

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
        $response = $event->getResponse();

        $slotContents = $this->renderSlotContents();
        if (!is_array($slotContents)) {
            throw new \InvalidArgumentException('"renderSlotContents" method must return an array');
        }

        foreach ($slotContents as $slotContent) {
            $this->renderSlot($response, $slotContent);
        }

        $event->setResponse($response);
    }

    protected function renderSlot(Response $response, AlSlotContent $slotContent)
    {
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

        $content = $response->getContent();
        $content = ($slotContent->isReplacing()) ? $this->replaceContent($slotContent, $content) : $this->injectContent($slotContent, $content);
        if (null !== $content) {
            $response->setContent($content);
        }

        return $response;
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