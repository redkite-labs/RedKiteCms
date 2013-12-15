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

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface;

/**
 * BlockManagersCollection collects AlBlockManager objects
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class BlockManagersCollection implements \Countable
{
    protected $blockManagers = array();
    
    /**
     * Adds an AlBlockManagerInterface object to the collection
     * 
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface $blockManager
     * 
     * @api
     */
    public function addBlockManager(AlBlockManagerInterface $blockManager)
    {
        $this->blockManagers[] = $blockManager;
    }
    
    /**
     * Returns the array managed by the collection
     * 
     * @return array
     * 
     * @api
     */
    public function getBlockManagers()
    {
        return $this->blockManagers;
    }
    
    /**
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * @codeCoverageIgnore
     */
    public function count()
    {
        return count($this->blockManagers);
    }

    /**
     * Returns the first block manager placed on the slot
     *
     * @return null|AlBlockManager
     *
     * @api
     */
    public function first()
    {
        return ($this->count() > 0) ? $this->blockManagers[0] : null;
    }

    /**
     * Returns the last block manager placed on the slot
     *
     * @return null|AlBlockManager
     *
     * @api
     */
    public function last()
    {
        $elements = $this->count();
        
        return ($elements > 0) ? $this->blockManagers[$elements - 1] : null;
    }

    /**
     * Returns the block manager at the given index.
     *
     * @return null|AlBlockManager
     *
     * @api
     */
    public function indexAt($index)
    {
        return ($index >= 0 && $index <= $this->count() - 1) ? $this->blockManagers[$index] : null;
    }

    /**
     * Clears the collection
     *
     * @api
     */
    public function clear()
    {
        $this->blockManagers = array();
    }

    /**
     * Retrieves the block manager and the index by the block's id
     *
     * @param  int        $idBlock The id of the block to retrieve
     * @return null|array
     */
    public function getBlockManagerAndIndex($idBlock)
    {
        foreach ($this->blockManagers as $index => $blockManager) {
            if ($blockManager->get()->getId() == $idBlock) {
                return array('index' => $index, 'manager' => $blockManager);
            }
        }

        return null;
    }
    
    /**
     * Retrieves the block manager by the block's id
     *
     * @param  type                                                                      $idBlock The id of the block to retrieve
     * @return null|\RedKiteLabs\RedKiteCmsBundle\Core\Content\AlContentManagerInterface
     *
     * @api
     */
    public function getBlockManager($idBlock)
    {
        $info = $this->getBlockManagerAndIndex($idBlock);

        return (null !== $info) ? $info['manager'] : null;
    }

    /**
     * Retrieves the block manager index by the block's id
     *
     * @param  int $idBlock The id of the block to retrieve
     * @return int
     *
     * @api
     */
    public function getBlockManagerIndex($idBlock)
    {
        $info = $this->getBlockManagerAndIndex($idBlock);

        return (null !== $info) ? $info['index'] : null;
    }

    /**
     * Returns the managed blocks as an array
     *
     * @return array
     *
     * @api
     */
    public function toArray()
    {
        $result = array();
        foreach ($this->blockManagers as $blockManager) {
            if (null !== $blockManager) {
                $result[] = $blockManager->toArray();
            }
        }

        return $result;
    }

    /**
     * Sets up the block managers.
     *
     * When the blocks have not been given, it retrieves all the pages's contents saved on the slot
     *
     * @param array $alBlocks
     *
     * @api
     */
    public function setUpBlockManagers(array $alBlocks)
    {
        foreach ($alBlocks as $alBlock) {
            $alBlockManager = $this->blockManagerFactory->createBlockManager($alBlock);
            $this->blockManagers[] = $alBlockManager;
        }
    }
    
    /**
     * Inserts a block manager at the specified index
     * 
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface $element
     * @param int $at
     * @return array
     */
    public function insertAt(AlBlockManagerInterface $element, $at)
    {
        $elements = count($this->blockManagers);
        
        $leftPart = array_slice($this->blockManagers, 0 , $at);
        $rightPart = array_slice($this->blockManagers, $at , $elements);
        
        $this->blockManagers = array_merge($leftPart, array($element), $rightPart);
        
        return array(
            "left" => $leftPart,
            "right" => $rightPart,
        );
    }
    
    /**
     * Removes the block manager at the specified index
     * 
     * @param int $at
     * @return array
     */
    public function removeAt($at)
    {
        $elements = count($this->blockManagers) - 1;
        
        $leftPart = array_slice($this->blockManagers, 0 , $at);
        $rightPart = array_slice($this->blockManagers, $at + 1, $elements);
        
        $this->blockManagers = array_merge($leftPart, $rightPart);
        
        return array(
            "left" => $leftPart,
            "right" => $rightPart,
        );
    }
}