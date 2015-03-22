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

namespace RedKiteCms\EventSystem\Listener\Block;

use RedKiteCms\Content\PageCollection\PermalinkManager;
use RedKiteCms\EventSystem\Event\Block\BlockEditingEvent;
use RedKiteCms\Rendering\PageRenderer\PageRendererProduction;

/**
 * Class BlockEditingListener listens to BlockEditingEvent to add the edited content to PermalinkManager object
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Listener\Block
 */
class BlockEditingListener
{
    /**
     * @type \RedKiteCms\Rendering\PageRenderer\PageRendererProduction
     */
    private $pageProductionRenderer;
    /**
     * @type \RedKiteCms\Content\PageCollection\PermalinkManager
     */
    private $permalinkManager;

    /**
     * Constructor
     *
     * @param \RedKiteCms\Rendering\PageRenderer\PageRendererProduction $pageProductionRenderer
     * @param \RedKiteCms\Content\PageCollection\PermalinkManager $permalinkManager
     */
    public function __construct(PageRendererProduction $pageProductionRenderer, PermalinkManager $permalinkManager)
    {
        $this->pageProductionRenderer = $pageProductionRenderer;
        $this->permalinkManager = $permalinkManager;
    }

    /**
     * Adds the edited block to PermalinkManager object
     *
     * @param \RedKiteCms\EventSystem\Event\Block\BlockEditingEvent $event
     */
    public function onBlockEditing(BlockEditingEvent $event)
    {
        $encodedBlock = $event->getFileContent();
        $htmlBlock = $this->pageProductionRenderer->renderBlock($encodedBlock);

        $this->permalinkManager
            ->add($event->getFilePath(), $htmlBlock)
            ->save();
    }
}