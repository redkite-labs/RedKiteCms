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

use JMS\Serializer\Serializer;
use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\EventSystem\RenderEvents;
use RedKiteCms\FilesystemEntity\Page;
use RedKiteCms\FilesystemEntity\Slot;
use RedKiteCms\Tools\Utils;

/**
 * Class PageRendererProduction is the object deputed to render a page for the production
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\PageRenderer
 */
class PageRendererProduction extends PageRendererBackend
{
    /**
     * @type \RedKiteCms\Configuration\ConfigurationHandler
     */
    private $configurationHandler;
    /**
     * @type \JMS\Serializer\Serializer
     */
    private $serializer;
    /**
     * @type \Twig_Environment
     */
    private $templating;
    /**
     * @type array
     */
    private $mediaFiles = array();

    /**
     * Constructor
     *
     * @param \RedKiteCms\Configuration\ConfigurationHandler $configurationHandler
     * @param \JMS\Serializer\Serializer $serializer
     * @param \Twig_Environment $templating
     */
    public function __construct(
        ConfigurationHandler $configurationHandler,
        Serializer $serializer,
        \Twig_Environment $templating
    ) {
        $this->configurationHandler = $configurationHandler;
        $this->serializer = $serializer;
        $this->templating = $templating;
    }

    /**
     * @return array
     */
    public function getMediaFiles()
    {
        return $this->mediaFiles;
    }

    /**
     * Renders page slots
     *
     * @param \RedKiteCms\FilesystemEntity\Page $page
     * @param array $slots
     * @param array $options
     *
     * @return array
     */
    public function renderSlots(Page $page, array $slots, array $options = array())
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

            $renderedSlots[$slotName] = implode("", $this->renderSlot($slot));
        }

        $this->mediaFiles = array_unique($this->mediaFiles);

        return $this->dispatchSlotsEvent(RenderEvents::SLOTS_RENDERED, $page, $renderedSlots);
    }

    /**
     * Renders the given slot
     * @param \RedKiteCms\FilesystemEntity\Slot $slot
     *
     * @return array
     */
    public function renderSlot(Slot $slot)
    {
        $blocks = array();
        $blockValues = $slot->getProductionEntities();
        foreach ($blockValues as $blockValue) {
            $blocks[] = $this->renderBlock($blockValue);
        }

        return $blocks;
    }

    /**
     * Renders a block from a encoded json content
     * @param $encodedBlock
     *
     * @return string
     */
    public function renderBlock($encodedBlock)
    {
        $values = json_decode($encodedBlock, true);
        $block = $this->serializer->deserialize($encodedBlock, Utils::blockClassFromType($values["type"]), 'json');
        $content = $this->templating->render($block->getType() . '/Resources/views/Frontend/block.html.twig', array('block' => $block));

        // Looks for images
        $this->updateMediaFiles('src', $content);

        // Looks for files
        $this->updateMediaFiles('href', $content);

        return $content;
    }

    private function updateMediaFiles($tag, $content)
    {
        $pattern = sprintf('/%s[^\"]+\"([^\"]+)\"/is', $tag);
        if (!preg_match_all($pattern, $content, $matches)) {
            return $content;
        }

        foreach ($matches[1] as $file) {
            if (!strpos($file, 'backend')) {
                continue;
            }

            $this->mediaFiles[] = $file;
        }

        return preg_replace('/\/backend\//', '/production/', $content);
    }
} 