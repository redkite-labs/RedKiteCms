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

namespace RedKiteCms\EventSystem\Listener\PageCollection;


use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\EventSystem\Event\Page\PageSavedEvent;
use RedKiteCms\Rendering\PageRenderer\PageRendererProduction;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class PageSavedListener listens to PageSavedEvent to copy page assets from the backend to production
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Listener\Page
 */
class PageSavedListener
{
    /**
     * @type \RedKiteCms\Configuration\ConfigurationHandler
     */
    private $configurationHandler;
    /**
     * @type \RedKiteCms\Rendering\PageRenderer\PageRendererProduction
     */
    private $pageProductionRenderer;

    /**
     * Constructor
     *
     * @param \RedKiteCms\Configuration\ConfigurationHandler $configurationHandler
     * @param \RedKiteCms\Rendering\PageRenderer\PageRendererProduction $pageProductionRenderer
     */
    public function __construct(
        ConfigurationHandler $configurationHandler,
        PageRendererProduction $pageProductionRenderer
    ) {
        $this->configurationHandler = $configurationHandler;
        $this->pageProductionRenderer = $pageProductionRenderer;
    }

    /**
     * Copies the assets from the backend to production
     *
     * @param \RedKiteCms\EventSystem\Event\Page\PageSavedEvent $event
     */
    public function onPageSaved(PageSavedEvent $event)
    {
        $blocks = $event->getApprovedBlocks();
        foreach ($blocks as $blockk) {
            foreach ($blockk as $block) {
                $this->pageProductionRenderer->renderBlock(json_encode($block));
            }
        }
        $mediaFiles = array_unique($this->pageProductionRenderer->getMediaFiles());

        $webDir = $this->configurationHandler->webDir();
        $fs = new Filesystem();
        foreach ($mediaFiles as $mediaFile) {
            $targetMediaFile = str_replace('/backend/', '/production/', $mediaFile);
            $fs->copy($webDir . $mediaFile, $webDir . $targetMediaFile);
        }
    }
}