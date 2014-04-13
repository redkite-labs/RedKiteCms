<?php
/*
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this bundle you must leave
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
 */

namespace RedKiteLabs\ThemeEngineBundle\Core\Rendering\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\ThemeEngineBundle\Core\Rendering\Event\PageRenderer\BeforePageRenderingEvent;
use RedKiteLabs\ThemeEngineBundle\Core\Rendering\SlotContent\SlotContent;
use Symfony\Component\HttpFoundation\Response;

/**
 * Implements a basec listener to replace a content when the page is rendered and ready
 * to be returned with the response.
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class BasePageRenderingListener
{
    protected $container;

    /**
     * Returns an array of \RedKiteLabs\ThemeEngineBundle\Core\Rendering\SlotContent\SlotContent
     * objects.
     *
     * @return array
     */
    abstract protected function renderSlotContents();

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Handles the event when notified or filtered.
     *
     * @param  BeforePageRenderingEvent  $event
     * @throws \InvalidArgumentException
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

    /**
     * Renders the current slot
     *
     * @param  Response                                   $response
     * @param  SlotContent                              $slotContent
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \RuntimeException
     */
    protected function renderSlot(Response $response, SlotContent $slotContent)
    {
        if (null === $slotContent->getSlotName()) {
           throw new \RuntimeException('No slot has been defined for the event ' . get_class($this));
        }

        $isReplacing = $slotContent->isReplacing();
        if (null === $isReplacing) {
           throw new \RuntimeException('No action has been specified for the event ' . get_class($this));
        }

        if (null === $slotContent->getContent()) {
           return null;
        }

        $content = $response->getContent();
        $content = ($isReplacing) ? $this->replaceContent($slotContent, $content) : $this->injectContent($slotContent, $content);
        if (null !== $content) {
            $response->setContent($content);
        }

        return $response;
    }

    /**
     * Replaces rhe content on the current slot with the new one
     *
     * @param  SlotContent $slotContent
     * @param  string        $content     The content to replace
     * @return string
     */
    protected function replaceContent(SlotContent $slotContent, $content)
    {
        $regex = $this->getPattern($slotContent->getSlotName());

        return preg_replace($regex, $slotContent->getContent(), $content);
    }

    /**
     * Injects the content at the end of the given content
     *
     * @param  SlotContent $slotContent
     * @param  string        $content     The content to inject
     * @return string
     */
    protected function injectContent(SlotContent $slotContent, $content)
    {
        $regex = $this->getPattern($slotContent->getSlotName());
        if (false == preg_match($regex, $content, $match)) {
            return;
        }
        $newContent = $match[1] . PHP_EOL . $slotContent->getContent();

        return preg_replace($regex, $newContent, $content);
    }

    /**
     * Defines the pattern to use for contents replacement
     */
    protected function getPattern($slotName)
    {
        return sprintf('/\<!-- BEGIN %s BLOCK --\>(.*?)\<!-- END %s BLOCK --\>/s', strtoupper($slotName), strtoupper($slotName));
    }
}
