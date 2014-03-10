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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot;

use RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\AlSlot;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Blocks\BlockManagersCollection;

/**
 * AlSlotManager is the object deputaed to manage the page's slots.
 *
 * A slot is the place on the page where one or more blocks lives.
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlSlotManager
{
    protected $slot;
    protected $blockManagers = array();
    protected $forceSlotAttributes = false;
    protected $skipSiteLevelBlocks = false;
    protected $blocksAdder;
    protected $blocksEdited;
    protected $blocksRemover;
    protected $blockManagerFactory;
    protected $blockManagersCollection = null;

    /**
     * Constructor
     *
     * @param AlSlot                         $slot
     * @param BlockRepositoryInterface       $blockRepository
     * @param AlBlockManagerFactoryInterface $blockManagerFactory
     * @param Blocks\BlocksAdder             $blocksAdder|null
     * @param Blocks\BlocksRemover           $blocksRemover|null
     * @param BlockManagersCollection        $blockManagersCollection|null
     */
    public function __construct(AlSlot $slot, BlockRepositoryInterface $blockRepository, AlBlockManagerFactoryInterface $blockManagerFactory, Blocks\BlocksAdder $blocksAdder = null, Blocks\BlocksRemover $blocksRemover = null, BlockManagersCollection $blockManagersCollection = null)
    {
        $this->slot = $slot;
        $this->blockRepository = $blockRepository;
        $this->blockManagerFactory = $blockManagerFactory;

        $this->blocksAdder = $blocksAdder;
        if (null === $this->blocksAdder) {
            $this->blocksAdder = new Blocks\BlocksAdder($this->blockRepository, $this->blockManagerFactory);
        }

        $this->blocksRemover = $blocksRemover;
        if (null === $blocksRemover) {
            $this->blocksRemover = new Blocks\BlocksRemover($this->blockRepository);
        }

        $this->blockManagersCollection = $blockManagersCollection;
        if (null === $this->blockManagersCollection) {
            $this->blockManagersCollection = new BlockManagersCollection();
        }
    }

    /**
     * Sets the slot object
     *
     * @param  AlSlot        $slot
     * @return AlSlotManager
     *
     * @api
     */
    public function setSlot(AlSlot $slot)
    {
        $this->slot = $slot;

        return $this;
    }

    /**
     * Returns the slot object
     *
     * @return AlSlot
     *
     * @api
     */
    public function getSlot()
    {
        return $this->slot;
    }

    /**
     * Sets the slot manager's behavior when a new block is added
     *
     * When true forces the add operation to use the default AlSlot attributes for
     * the new block type
     *
     * @param  boolean                   $forceSlotAttributes
     * @return AlSlotManager
     * @throws \InvalidArgumentException
     *
     * @api
     */
    public function setForceSlotAttributes($forceSlotAttributes)
    {
        if (!is_bool($forceSlotAttributes)) {
            throw new InvalidArgumentException('exception_boolean_value_required_for_setForceSlotAttributes');
        }

        $this->forceSlotAttributes = $forceSlotAttributes;

        return $this;
    }

    /**
     * Skips adding a new block when the slot is repeated at site level and the block
     * has been already added
     *
     * @param  boolean                   $skipSiteLevelBlocks
     * @return AlSlotManager
     * @throws \InvalidArgumentException
     *
     * @api
     */
    public function setSkipSiteLevelBlocks($skipSiteLevelBlocks)
    {
        if (!is_bool($skipSiteLevelBlocks)) {
            throw new InvalidArgumentException('exception_boolean_value_required_for_setSkipSiteLevelBlocks');
        }

        $this->skipSiteLevelBlocks = $skipSiteLevelBlocks;

        return $this;
    }

    /**
     * Returns the slot manager's behavior when a new block is added
     *
     * @return boolean
     *
     * @api
     */
    public function getForceSlotAttributes()
    {
        return $this->forceSlotAttributes;
    }

    /**
     * Returns the slot's blocks repeated status
     *
     * @return string
     *
     * @api
     */
    public function getRepeated()
    {
        return $this->slot->getRepeated();
    }

    /**
     * Returns the name of the slot
     *
     * @return string
     *
     * @api
     */
    public function getSlotName()
    {
        return $this->slot->getSlotName();
    }

    /**
     * Returns the block managers collection associated with the slot manager
     *
     * @return BlockManagersCollection|null
     *
     * @api
     */
    public function getBlockManagersCollection()
    {
        return $this->blockManagersCollection;
    }

    /**
     * Returns the last block manager added to the slot manager
     *
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\AlBlockManager|null
     *
     * @api
     */
    public function lastAdded()
    {
        return $this->blocksAdder->lastAdded();
    }

    /**
     * Returns the last edited block manager
     *
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\AlBlockManager|null
     *
     * @api
     */
    public function lastEdited()
    {
        return $this->blocksAdder->lastEdited();
    }

    /**
     * Adds a new AlBlock object to the slot
     *
     * The created block managed is added to the collection. When the $referenceBlockId param is valorized,
     * the new block is created under the block identified by the given id
     *
     * @param  array        $options
     * @return null|boolean
     */
    public function addBlock(array $options)
    {
        $this->checkInteger($options["idLanguage"], 'exception_invalid_value_for_language_id');
        $this->checkInteger($options["idPage"], 'exception_invalid_value_for_page_id');

        $options = $this->normalizeOptions($options);
        $options["skipSiteLevelBlocks"] = $this->skipSiteLevelBlocks;
        $options["forceSlotAttributes"] = $this->forceSlotAttributes;

        $result = $this->blocksAdder->add($this->slot, $this->blockManagersCollection, $options);

        return $result;
    }

    /**
     * Edits the block
     *
     * @param  int          $idBlock The id of the block to edit
     * @param  array        $values  The new values
     * @return boolean|null
     *
     * @api
     */
    public function editBlock($idBlock, array $values)
    {
        $blockManager = $this->blockManagersCollection->getBlockManager($idBlock);
        if (null === $blockManager) {
            return null;
        }

        return $this->blocksAdder->edit($blockManager, $values);
    }

    /**
     * Deletes the block from the slot
     *
     * @param  int     $idBlock The id of the block to remove
     * @return boolean
     *
     * @api
     */
    public function deleteBlock($idBlock)
    {
        return $this->blocksRemover->remove($idBlock, $this->blockManagersCollection);
    }

    /**
     * Deletes all the blocks managed by the slot
     *
     * @return boolean
     * @throws \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     *
     * @api
     */
    public function deleteBlocks()
    {
        return $this->blocksRemover->clear($this->blockManagersCollection);
    }

    /**
     * Sets up the block managers.
     *
     * When the blocks have not been given, it retrieves all the pages's contents saved on the slot
     *
     * @param array $blocks
     *
     * @api
     */
    public function setUpBlockManagers(array $blocks)
    {
        foreach ($blocks as $block) {
            $blockManager = $this->blockManagerFactory->createBlockManager($block);
            if (null === $blockManager) {
                continue;
            }
            $this->blockManagersCollection->addBlockManager($blockManager);
        }
    }

    private function normalizeOptions(array $options)
    {
        if ( ! array_key_exists("type", $options)) {
            $options["type"] = "Text";
        }

        // Forces the creation of the block type defined in the AlSlot object
        if ($this->forceSlotAttributes) {
            $options["type"] = $this->slot->getBlockType();
        }

        if ( ! array_key_exists("referenceBlockId", $options)) {
            $options["referenceBlockId"] = null;
        }

        if ( ! array_key_exists("insertDirection", $options) || $options['insertDirection'] == null) {
            $options["insertDirection"] = 'bottom';
        }

        return $options;
    }

    private function checkInteger($value, $errorMessage)
    {
        if ((int) $value == 0) {
            throw new InvalidArgumentTypeException($errorMessage);
        }
    }
}
