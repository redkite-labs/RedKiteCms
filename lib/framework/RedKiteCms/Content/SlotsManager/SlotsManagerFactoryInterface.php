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

namespace RedKiteCms\Content\SlotsManager;

/**
 * Interface SlotsManagerFactoryInterface represents a class that configures an object able to create a SlotManager
 * object
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\SlotsManager
 */
interface SlotsManagerFactoryInterface
{
    /**
     * Creates the slot manager
     * @param string $repeat
     *
     * @return \RedKiteCms\Content\SlotsManager\SlotsManager
     */
    function createSlotManager($repeat);
}
