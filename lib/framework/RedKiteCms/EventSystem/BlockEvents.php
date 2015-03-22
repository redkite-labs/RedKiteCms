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
 * Class BlockEvents is the object deputed to define the names for blocks events
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem
 */
final class BlockEvents
{
    /**
     * The block.adding event is raised just before adding a block
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Block\BlockAddingEvent instance.
     *
     * @type string
     */
    CONST BLOCK_ADDING = 'block.adding';

    /**
     * The block.added event is raised just after a block was correctly added
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Block\BlockAddedEvent instance.
     *
     * @type string
     */
    CONST BLOCK_ADDED = 'block.added';

    /**
     * The block.editing event is raised just before editing a block
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Block\BlockEditingEvent instance.
     *
     * @type string
     */
    CONST BLOCK_EDITING = 'block.editing';

    /**
     * The block.edited event is raised just after a block was correctly edited
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Block\BlockEditedEvent instance.
     *
     * @type string
     */
    CONST BLOCK_EDITED = 'block.edited';

    /**
     * The block.removing event is raised just before removing a block
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Block\BlockRemovingEvent instance.
     *
     * @type string
     */
    CONST BLOCK_REMOVING = 'block.removing';

    /**
     * The block.removed event is raised just after a block was correctly removed
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Block\BlockRemovedEvent instance.
     *
     * @type string
     */
    CONST BLOCK_REMOVED = 'block.removed';

    /**
     * The block.moving_same_slot event is raised just before moving a block on the same
     * slot
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Block\BlockMovingSameSlotEvent instance.
     *
     * @type string
     */
    CONST BLOCK_MOVING_SAME_SLOT = 'block.moving_same_slot';

    /**
     * The block.moved_same_slot event is raised just after a block was correctly removed
     * on the same slot
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Block\BlockMovedSameSlotEvent instance.
     *
     * @type string
     */
    CONST BLOCK_MOVED_SAME_SLOT = 'block.moved_same_slot';

    /**
     * The block.moving_another_slot event is raised just before moving a block on another
     * slot
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Block\BlockMovingAnotherSlotEvent instance.
     *
     * @type string
     */
    CONST BLOCK_MOVING_ANOTHER_SLOT = 'block.moving_another_slot';

    /**
     * The block.moved_another_slot event is raised just after a block was correctly removed
     * on another slot
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Block\BlockMovedAnotherSlotEvent instance.
     *
     * @type string
     */
    CONST BLOCK_MOVED_ANOTHER_SLOT = 'block.moved_another_slot';

    /**
     * The block.restoring event is raised just before restoring a block
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Block\BlockRestoringEvent instance.
     *
     * @type string
     */
    CONST BLOCK_RESTORING = 'block.restoring';

    /**
     * The block.restored event is raised just after a block was correctly restored
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Block\BlockRestoredEvent instance.
     *
     * @type string
     */
    CONST BLOCK_RESTORED = 'block.restored';

    /**
     * The block.approving event is raised just before approving a block
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Block\BlockApprovingEvent instance.
     *
     * @type string
     */
    CONST BLOCK_APPROVING = 'block.approving';

    /**
     * The block.approved event is raised just after a block was correctly approved
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Block\BlockApprovedEvent instance.
     *
     * @type string
     */
    CONST BLOCK_APPROVED = 'block.approved';

    /**
     * The block.approving_removal event is raised just before approving a block removal
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Block\BlockApprovingRemovalEvent instance.
     *
     * @type string
     */
    CONST BLOCK_APPROVING_REMOVAL = 'block.approving_removal';

    /**
     * The block.approved_removal event is raised just after a block was correctly approved
     * fo removal
     *
     * The event listener receives a
     * RedKiteCms\EventSystem\Event\Block\BlockApprovedRemovalEvent instance.
     *
     * @type string
     */
    CONST BLOCK_APPROVED_REMOVAL = 'block.approved_removal';
}