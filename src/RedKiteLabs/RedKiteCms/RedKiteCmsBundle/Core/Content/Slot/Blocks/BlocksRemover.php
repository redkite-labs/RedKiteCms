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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Blocks;

use RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;

/**
 * BlocksAdder is the object deputated to remove a block from a slot
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class BlocksRemover extends BaseBlocks
{  
    /**
     * Removes the block manager that handles the given block id
     * 
     * @param int $idBlock
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Blocks\BlockManagersCollection $blockManagers
     * @return null|boolean
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException     *
     */
    public function remove($idBlock, BlockManagersCollection $blockManagers)
    {
        $blockManagerInfo = $blockManagers->getBlockManagerAndIndex($idBlock);
        $blockManager = $blockManagerInfo['manager'];
        if (null === $blockManager) {
            return;
        }
        
        try {
            $this->blockRepository->startTransaction();

            // Adjust the blocks position
            
            $parts = $blockManagers->removeAt($blockManagerInfo["index"]);
            $result = $this->adjustPosition('del', $parts["right"]);
            if (false !== $result) {
                $result = $blockManager->delete();
            }

            if (false !== $result) {
                $this->blockRepository->commit();

                return $result;
            }

            $this->blockRepository->rollBack();

            return $result;
        } catch (\Exception $e) {
            if (isset($this->blockRepository) && $this->blockRepository !== null) {
                $this->blockRepository->rollBack();
            }

            throw $e;
        }
    }
    
    /**
     * Deletes all the blocks managed by the slot
     *
     * @return boolean
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     *
     * @api
     */
    public function clear(BlockManagersCollection $blockManagersCollection)
    {
        if (count($blockManagersCollection) == 0) {
            return;
        }
        
        try {            
            $result = null;
            $this->blockRepository->startTransaction();
            $blockManagers = $blockManagersCollection->getBlockManagers();

            foreach ($blockManagers as $blockManager) {
                $result = $blockManager->delete();
                if (false === $result) {
                    break;
                }
            }

            if (false !== $result) {
                $this->blockRepository->commit();
                $blockManagersCollection->clear();

                return $result;
            }

            $this->blockRepository->rollBack();

            return $result;
        } 
        catch (\Exception $e) {
            if (isset($this->blockRepository) && $this->blockRepository !== null) {
                $this->blockRepository->rollBack();
            }

            throw $e;
        }
    }
}