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
 * Class RenderEvents is the object deputed to define the names for slots render events
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem
 */
final class RenderEvents
{
    /**
     * The slots.rendering event is raised just before rendering the page's slots
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Render\SlotsRenderingEvent instance.
     *
     * @type string
     */
    CONST SLOTS_RENDERING = 'slots.rendering';

    /**
     * The slots.rendered event is raised after the page slots were rendered
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Render\SlotsRenderedEvent instance.
     *
     * @type string
     */
    CONST SLOTS_RENDERED = 'slots.rendered';
}