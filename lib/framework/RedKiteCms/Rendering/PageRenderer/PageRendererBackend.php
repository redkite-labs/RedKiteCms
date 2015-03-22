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

namespace RedKiteCms\Rendering\PageRenderer;

use RedKiteCms\Bridge\Dispatcher\Dispatcher;
use RedKiteCms\Content\Block\BaseBlock;
use RedKiteCms\Content\PageCollection\PagesCollectionParser;
use RedKiteCms\EventSystem\Event\Render\SlotsRenderedEvent;
use RedKiteCms\EventSystem\Event\Render\SlotsRenderingEvent;
use RedKiteCms\EventSystem\RenderEvents;
use RedKiteCms\FilesystemEntity\Page;
use RedKiteCms\FilesystemEntity\Slot;


/**
 * Class PageRendererBackend is the object deputed to render a page for the CMS backend
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\PageRenderer
 */
class PageRendererBackend
{
    /**
     * @type \Twig_Environment
     */
    private $templating;
    /**
     * @type \RedKiteCms\Content\PageCollection\PagesCollectionParser
     */
    private $pagesParser;

    /**
     * Constructor
     *
     * @param \Twig_Environment $templating
     * @param \RedKiteCms\Content\PageCollection\PagesCollectionParser $pagesParser
     */
    public function __construct(\Twig_Environment $templating, PagesCollectionParser $pagesParser)
    {
        $this->templating = $templating;
        $this->pagesParser = $pagesParser;
    }

    /**
     * Renders the page slots from a Page entity
     * @param \RedKiteCms\FilesystemEntity\Page $page
     * @param array $options
     *
     * @return array
     */
    public function renderSlotsFromPage(Page $page, array $options = array())
    {
        $slots = $page->getPageSlots();

        return $this->renderSlots($page, $slots, $options);
    }

    /**
     * Render the cms blocks
     *
     * @param array $blocks
     * @param $username
     * @param array $options
     *
     * @return string
     */
    public function renderCmsBlocks(array $blocks, $username, array $options = array())
    {
        $tmp = array();
        foreach ($blocks as $block) {
            $tmp[] = $this->renderCmsBlock($block, $username, $options);
        }

        return implode("\n", $tmp);
    }

    /**
     * Renders the given block
     *
     * @param \RedKiteCms\Content\Block\BaseBlock $block
     * @param $username
     * @param array $options
     *
     * @return string
     */
    public function renderCmsBlock(BaseBlock $block, $username, array $options = array())
    {
        $blockTemplate = $block->getType() . '/Resources/views/Backend/block.html.twig';
        if ($blockTemplate == "") {
            return "";
        }

        $permalinks = $this->pagesParser
            ->contributor($username)
            ->parse()
            ->permalinksByLanguage();

        $options = array_merge(
            array(
                'block' => $block,
                'permalinks' => $permalinks,
            ),
            $options
        );

        return $this->templating->render($blockTemplate, $options);
    }

    /**
     * Renders the slots
     *
     * @param \RedKiteCms\FilesystemEntity\Page $page
     * @param array $slots
     * @param array $options
     *
     * @return array
     */
    protected function renderSlots(Page $page, array $slots, array $options = array())
    {
        $renderedSlots = array();
        $slots = $this->dispatchSlotsEvent(RenderEvents::SLOTS_RENDERING, $page, $slots);
        foreach ($slots as $slotName => $slot) {
            if (is_string($slot)) {
                $renderedSlots[$slotName] = $slot;
                continue;
            }

            if (!$slot instanceof Slot) {
                continue;
            }

            $blocks = $slot->getEntitiesInUse();
            $renderedSlots[$slotName] = $this->templating->render(
                'RedKiteCms/Resources/views/Slot/slot.html.twig',
                array(
                    'options' => $options,
                    'slotname' => $slotName,
                    'data' => rawurlencode("[" . implode(",", $blocks)) . "]",
                )
            );
        }

        return $slots = $this->dispatchSlotsEvent(RenderEvents::SLOTS_RENDERED, $page, $renderedSlots);
    }

    /**
     * Generates the events based on the current page.
     *
     * RedKite CMS will generate four events:
     *
     * 1. [ Base render name ] This event is used to change a slot content for the entire site
     * 2. [ Base render name ].[ Language ] This event is used to change a slot content for the event language
     * 3. [ Base render name ].[ Page ] This event is used to change a slot content for the event page collection
     * 4. [ Base render name ].[ Language ].[ Page ] This event is used to change a slot content for the event page and
     * language
     *
     * @param $baseEventName
     * @param \RedKiteCms\FilesystemEntity\Page $page
     *
     * @return array
     */
    protected function generateEventNames($baseEventName, Page $page)
    {
        $pageName = $page->getPageName();
        $language = $page->getCurrentLanguage();

        return array(
            $baseEventName,
            $baseEventName . '.' . $language,
            $baseEventName . '.' . $pageName,
            $baseEventName . '.' . $language . '.' . $pageName,
        );
    }

    /**
     * Dispatches the events
     *
     * @param $baseEventName
     * @param \RedKiteCms\FilesystemEntity\Page $page
     * @param array $slots
     *
     * @return array
     */
    protected function dispatchSlotsEvent($baseEventName, Page $page, array $slots)
    {
        $eventNames = $this->generateEventNames($baseEventName, $page);
        $event = new SlotsRenderingEvent($slots);
        foreach($eventNames as $eventName) {
            $event = Dispatcher::dispatch($eventName, $event);
        }

        return $event->getSlots();
    }

    /**
     * Decodes the given blocks
     * @param $values
     *
     * @return array
     */
    protected function decodeBlocks($values)
    {
        $result = array();
        foreach ($values as $value) {
            $block = json_decode($value, true);
            $result[] = $block["name"];
        }

        return $result;
    }
} 