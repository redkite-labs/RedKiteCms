<?php
/*
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 */

namespace RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots;

/**
 * Defines the theme slots methods
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
interface ThemeSlotsInterface
{
    /**
     * Adds a slot object
     *
     * @param \RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\Slot
     */
    public function addSlot(Slot $slot);

    /**
     * Return the template's slots
     *
     * @return array
     */
    public function getSlot($slotName);

    /**
     * Return the template's slots
     *
     * @return null|array
     */
    public function getSlots();

    /**
     * Returns all the slots by repeated status
     *
     * @param boolean
     * @return array
     */
    public function toArray($fullSlot = false);
}
