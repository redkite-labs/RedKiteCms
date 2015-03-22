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

namespace RedKiteCms\EventSystem;

/**
 * Class PageEvents is the object deputed to define the names for page events
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem
 */
final class PageEvents
{
    /**
     * The page.editing event is raised just before editing the page seo attributes
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Page\PageEditingEvent instance.
     *
     * @type string
     */
    CONST PAGE_EDITING = 'page.editing';

    /**
     * The page.edited event is raised after the page seo attributes were edited
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Page\PageEditedEvent instance.
     *
     * @type string
     */
    CONST PAGE_EDITED = 'page.edited';

    /**
     * The page.slugging_permalink event is raised just before slugging a permalink
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Page\SluggingPermalinkEvent instance.
     *
     * @type string
     */
    CONST SLUGGING_PERMALINK = 'page.slugging_permalink';

    /**
     * The page.permalink_changed event is raised after the current permalink was changed
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Page\PermalinkChangedEvent instance.
     *
     * @type string
     */
    CONST PERMALINK_CHANGED = 'page.permalink_changed';

    /**
     * The page.approving event is raised just before approving the page seo attributes
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Page\PageApprovingEvent instance.
     *
     * @type string
     */
    CONST PAGE_APPROVING = 'page.approving';

    /**
     * The page.approved event is raised after the page seo attributes were approved
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Page\PageApprovedEvent instance.
     *
     * @type string
     */
    CONST PAGE_APPROVED = 'page.approved';

    /**
     * The page.publishing event is raised just before publishing the page in production
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Page\PagePublishingEvent instance.
     *
     * @type string
     */
    CONST PAGE_PUBLISHING = 'page.publishing';

    /**
     * The page.published event is raised after the page seo attributes were published
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Page\PagePublishedEvent instance.
     *
     * @type string
     */
    CONST PAGE_PUBLISHED = 'page.published';

    /**
     * The page.hiding event is raised just before hiding the page in production
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Page\PageHidingEvent instance.
     *
     * @type string
     */
    CONST PAGE_HIDING = 'page.hiding';

    /**
     * The page.hid event is raised after the page was hidden
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Page\PageHidEvent instance.
     *
     * @type string
     */
    CONST PAGE_HID = 'page.hid';

    /**
     * The page.saved event is raised after a page was saved in production
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Page\PageSavedEvent instance.
     *
     * @type string
     */
    CONST PAGE_SAVED = 'page.saved';
}