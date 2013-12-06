<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageModelation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks;

/**
 * Defines the methods to manage page blocks objects
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
interface AlPageBlocksInterface
{
    /**
     *
     * Adds or edits a block on the giving slot.
     *
     * Addind a new block or edit an existing one depends on the position parameter.
     * When this last one is null the content is added, when it is given the block
     * saved at the key identified by the position is edited
     *
     * @param  string                    $slotName
     * @param  array                     $values
     * @param  int                       $position The first element has a position equals to 0
     * @throws \InvalidArgumentException
     */
    public function add($slotName, $value, $position = null);

    /**
     * Adds a range of blocks.
     *
     * The values array must be structured as follows:
     *    array('slotname' =>
     *              array(
     *                  array([Content] => 'content1'),
     *                  array([Content] => 'content2'),
     *                  ...,
     *                  array([Content] => 'content[n]'),
     *                  ),
     *          'slotname1' => ...
     *         )
     *
     * @param array $values
     * @param type  $override When true, the slot is cleared and repopulated by the new blocks
     */
    public function addRange(array $values, $override = false);

    /**
     * Clears the blocks for the given slot
     *
     * @param string $slotName
     */
    public function clearSlotBlocks($slotName);

    /**
     * Clears the blocks for the given slot
     */
    public function clearSlots();

    /**
     * Removes the given slot
     *
     * @param string $slotName
     */
    public function removeSlot($slotName);

    /**
     * Clears the all the slots
     */
    public function removeSlots();

    /**
     * Returns all the page's blocks
     *
     * @return array
     */
    public function getBlocks();

    /**
     * Return all blocks placed on the given slot name
     *
     * @return array
     */
    public function getSlotBlocks($slotName);
}
