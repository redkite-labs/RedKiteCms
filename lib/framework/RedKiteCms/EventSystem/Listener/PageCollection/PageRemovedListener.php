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


use RedKiteCms\Content\PageCollection\PagesCollectionParser;
use RedKiteCms\Content\PageCollection\PermalinkManager;
use RedKiteCms\EventSystem\Event\PageCollection\PageCollectionRemovedEvent;

/**
 * Class PageRemovedListener listens to PageCollectionRemovedEvent to remove the permalinks from PermalinkManager object
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Listener\Page
 */
class PageRemovedListener
{
    /**
     * @type \RedKiteCms\Content\PageCollection\PagesCollectionParser
     */
    private $pagesParser;
    /**
     * @type \RedKiteCms\Content\PageCollection\PermalinkManager
     */
    private $permalinkManager;

    /**
     * Constructor
     *
     * @param \RedKiteCms\Content\PageCollection\PagesCollectionParser $pagesParser
     * @param \RedKiteCms\Content\PageCollection\PermalinkManager $permalinkManager
     */
    public function __construct(PagesCollectionParser $pagesParser, PermalinkManager $permalinkManager)
    {
        $this->pagesParser = $pagesParser;
        $this->permalinkManager = $permalinkManager;
    }

    /**
     * Removes the page from PermalinkManager object
     *
     * @param \RedKiteCms\EventSystem\Event\PageCollection\PageCollectionRemovedEvent $event
     */
    public function onPageRemoved(PageCollectionRemovedEvent $event)
    {
        $pageName = basename($event->getFilePath());
        $page = $this->pagesParser
            ->contributor($event->getUsername())
            ->parse()
            ->page($pageName);
        if (null === $page) {
            return;
        }

        foreach ($page["seo"] as $seo) {
            $permalink = $seo["permalink"];
            $this->permalinkManager->remove($permalink);
        }

        $this->permalinkManager->save();
    }
}