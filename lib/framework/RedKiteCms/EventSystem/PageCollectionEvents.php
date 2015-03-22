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
 * Class PageCollectionEvents is the object deputed to define the names for page collections events
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem
 */
final class PageCollectionEvents
{
    /**
     * The page.collection.adding event is raised just before adding a new page
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\PageCollection\PageCollectionAddingEvent instance.
     *
     * @type string
     */
    CONST PAGE_COLLECTION_ADDING = 'page.collection.adding';

    /**
     * The page.collection.added event is raised after a new page was added
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\PageCollection\PageCollectionAddedEvent instance.
     *
     * @type string
     */
    CONST PAGE_COLLECTION_ADDED = 'page.collection.added';

    /**
     * The page.collection.editing event is raised just before editing a page
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\PageCollection\PageCollectionEditingEvent instance.
     *
     * @type string
     */
    CONST PAGE_COLLECTION_EDITING = 'page.collection.editing';

    /**
     * The page.collection.edited event is raised after a page was edited
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\PageCollection\PageCollectionEditedEvent instance.
     *
     * @type string
     */
    CONST PAGE_COLLECTION_EDITED = 'page.collection.edited';

    /**
     * The page.slugging_page_collection_name event is raised just before slugging the page name
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\PageCollection\SluggingPageNameEvent instance.
     *
     * @type string
     */
    CONST SLUGGING_PAGE_COLLECTION_NAME = 'page.slugging_page_collection_name';

    /**
     * The page.collection.removing event is raised just before removing a page
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\PageCollection\PageCollectionRemovingEvent instance.
     *
     * @type string
     */
    CONST PAGE_COLLECTION_REMOVING = 'page.collection.removing';

    /**
     * The page.collection.removed event is raised after a page was removed
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\PageCollection\PageCollectionRemovedEvent instance.
     *
     * @type string
     */
    CONST PAGE_COLLECTION_REMOVED = 'page.collection.removed';

    /**
     * The page.collection.site_saved event is raised after all pages were saved in production
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\PageCollection\SiteSavedEvent instance.
     *
     * @type string
     */
    CONST SITE_SAVED = 'page.collection.site_saved';

    /**
     * The page.collection.template_changed event is raised after the page template has been changed
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\PageCollection\TemplateChangedEvent instance.
     *
     * @type string
     */
    CONST TEMPLATE_CHANGED = 'page.collection.template_changed';
}