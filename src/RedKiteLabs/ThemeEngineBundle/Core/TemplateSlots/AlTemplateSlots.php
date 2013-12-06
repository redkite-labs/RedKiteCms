<?php
/**
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

namespace RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots;

/**
 * AlTemplateSlots is the object deputated to collect and manage the template's slots
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlTemplateSlots implements AlTemplateSlotsInterface
{
    private $slots = array();

    /**
     * { @inheritdoc }
     */
    public function addSlot(AlSlot $slot)
    {
        $this->slots[$slot->getSlotName()] = $slot;
    }

    /**
     * { @inheritdoc }
     */
    public function getSlots()
    {
        return $this->slots;
    }

    /**
     * { @inheritdoc }
     */
    public function getSlot($slotName)
    {
        return $this->checkSlotExists($slotName) ? $this->slots[$slotName] : null;
    }

    /**
     * { @inheritdoc }
     */
    public function toArray($fullSlot = false)
    {
        $slots = array();
        foreach ($this->slots as $slot) {
            $slots[$slot->getRepeated()][] = ($fullSlot) ? $slot->toArray() : $slot->getSlotName();
        }

        return $slots;
    }

    /**
     * Returns the repeated content status for the required slot
     *
     * @param  string $slotName The slot name to retrieve
     * @return string The repeated slot status or null if a non existent slot is required
     */
    public function getRepeatedContentFromSlot($slotName)
    {
        if(!$this->checkSlotExists($slotName)) return null;

        return $this->slots[$slotName]->getRepeated();
    }

    /**
     * Returns the default html content when a new content is added to the slot
     *
     * @param  string $slotName The slot name to retrieve
     * @return string The default text
     */
    public function getContentFromSlot($slotName)
    {
        if(!$this->checkSlotExists($slotName)) return null;

        return $this->slots[$slotName]->getContent();
    }

    /**
     * Checks if a slot exists
     *
     * @param  string  $slotName The slot name to check
     * @return boolean
     */
    private function checkSlotExists($slotName)
    {
        return (!array_key_exists($slotName, $this->slots)) ? false : true;
    }
}
