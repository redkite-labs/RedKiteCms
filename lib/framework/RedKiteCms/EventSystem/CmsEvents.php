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
 * Class CmsEvents is the object deputed to define the names for cms events
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem
 */
final class CmsEvents
{
    /**
     * The cms.booting event is thrown each time RedKiteCms is ready to be booted.
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Cms\CmsBootingEvent instance.
     *
     * @type string
     */
    CONST CMS_BOOTING = 'cms.booting';

    /**
     * The cms.booted event is thrown each time RedKiteCms has been booted.
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Cms\CmsBootedEvent instance.
     *
     * @type string
     */
    CONST CMS_BOOTED = 'cms.booted';
}