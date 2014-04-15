<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
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
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Blocks;

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
     * @param  int                     $idBlock
     * @param  BlockManagersCollection $blockManagersCollection
     * @throws \Exception
     * @return null|boolean
     */
    public function remove($idBlock, BlockManagersCollection $blockManagersCollection)
    {
        $blockManagerInfo = $blockManagersCollection->getManagerInfoByBlockId($idBlock);
        $blockManager = $blockManagerInfo['manager'];
        // @codeCoverageIgnoreStart
        if (null === $blockManager) {
            return null;
        }
        // @codeCoverageIgnoreEnd

        try {
            $this->blockRepository->startTransaction();

            // Adjust the blocks position
            $parts = $blockManagersCollection->removeAt($blockManagerInfo["index"]);
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
        } catch (\Exception $e) { echo $e->getMessage();
            $this->blockRepository->rollBack();

            throw $e;
        }
    }

    /**
     * Deletes all the blocks managed by the slot
     *
     * @param  BlockManagersCollection $blockManagersCollection
     * @throws \Exception
     * @return boolean
     *
     * @api
     */
    public function clear(BlockManagersCollection $blockManagersCollection)
    {
        // @codeCoverageIgnoreStart
        if ($blockManagersCollection->count() == 0) {
            return null;
        }
        // @codeCoverageIgnoreEnd

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
        } catch (\Exception $e) {
            $this->blockRepository->rollBack();

            throw $e;
        }
    }
}
