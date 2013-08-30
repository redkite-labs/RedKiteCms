<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Event\Content;

/**
 * Defines the names for the language events
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
final class LanguageEvents
{
    // rkcms.event_listener

    const BEFORE_ADD_LANGUAGE = 'languages.before_language_adding';
    const BEFORE_ADD_LANGUAGE_COMMIT = 'pages.before_add_language_commit';
    const AFTER_ADD_LANGUAGE = 'languages.after_language_added';

    const BEFORE_EDIT_LANGUAGE = 'languages.before_language_editing';
    const BEFORE_EDIT_LANGUAGE_COMMIT = 'pages.before_edit_language_commit';
    const AFTER_EDIT_LANGUAGE = 'languages.after_language_edited';

    const BEFORE_DELETE_LANGUAGE = 'languages.before_language_deleting';
    const BEFORE_DELETE_LANGUAGE_COMMIT = 'pages.before_delete_language_commit';
    const AFTER_DELETE_LANGUAGE = 'languages.after_language_deleted';
}
