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

/**
 * AlBlockManagerFactory is the object responsible to create a new BlockManager object
 *
 * BlockManagers are created by an existing AlBlock object or by a valid string that identifies
 * a valid AlBlockType
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
namespace RedKiteLabs\RedKiteCmsBundle\Core\Content\Block;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Model\AlBlock;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface;

/**
 * AlBlockManagerFactory is the object responsible to create a new AlBlockManager object
 *
 * AlBlockManagerFactory collects all the AlBlockManager objects and uses the to create
 * the new object from an existing AlBlock object or by a valid string that identifies
 * a valid AlBlockType.
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlBlockManagerFactory implements AlBlockManagerFactoryInterface
{
    /**
     * The generable blockManagers
     *
     * @var array $blockManagersItems
     *
     * @api
     */
    private $blockManagersItems = array();

    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
     *
     * @api
     * @deprecated since 1.1.0
     */
    private $translator = null;

    /**
     * @var \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface
     *
     * @api
     */
    private $factoryRepository;

    /**
     * @var \RedKiteLabs\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface
     *
     * @api
     */
    private $eventsHandler;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface          $eventsHandler
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface $factoryRepository
     * 
     * @api
     */
    public function __construct(AlEventsHandlerInterface $eventsHandler, AlFactoryRepositoryInterface $factoryRepository = null)
    {
        $this->eventsHandler = $eventsHandler;
        $this->factoryRepository = $factoryRepository;
    }

    /**
     * Adds a block manager base object.
     *
     * This method is usually called by the AlBlocksCompilerPass object
     *
     * @param AlBlockManagerInterface $blockManager
     * @param array                   $attributes
     *
     * @api
     */
    public function addBlockManager(AlBlockManagerInterface $blockManager, array $attributes)
    {
        if (empty($attributes['type'])) {
            return;
        }
        
        $blockManager->setFactoryRepository($this->factoryRepository);
        $this->blockManagersItems[] = new AlBlockManagerFactoryItem($blockManager, $attributes);
    }

    /**
     * { @inheritDoc }
     */
    public function createBlockManager($block)
    {
        $isAlBlock = $block instanceof AlBlock;
        $blockType = $isAlBlock ? $block->getType() : $block;

        $items = count($this->blockManagersItems);
        if ($items == 0) {
            return null;
        }
        
        foreach ($this->blockManagersItems as $blockManagerItem) {
            if ($blockManagerItem->getType() == $blockType) {
                $blockManager = $blockManagerItem->getBlockManager();
                $blockManager = clone($blockManager);
                $blockManager->setEventsHandler($this->eventsHandler);
                if ($isAlBlock) $blockManager->set($block);

                return $blockManager;
            }
        }

        return null;
    }

    public function getAvailableBlocks()
    {
        $blockManagers = array();
        foreach ($this->blockManagersItems as $blockManagerItem) {
            if ($blockManagerItem->getBlockManager()->getIsInternalBlock()) {
                continue;
            }

            $blockManagers[] = $blockManagerItem->getType();
        }

        return $blockManagers;
    }

    /**
     * Returns an array that contains the blocks description objects that can be created by the
     * factory, ordered by group
     *
     * @return array
     *
     * @api
     */
    public function getBlocks()
    {
        $blockGroups = array();
        foreach ($this->blockManagersItems as $blockManagerItem) {

            if ($blockManagerItem->getBlockManager()->getIsInternalBlock()) {
                continue;
            }

            $group = $blockManagerItem->getGroup();
            if ($group != "") {
                $groups = explode(",", $group);
            } else {
                $groups = array('none');
            }

            $blockGroup = array($blockManagerItem->getType() => $blockManagerItem->getDescription());
            foreach (array_reverse($groups) as $key) {
               $blockGroup = array(trim($key) => $blockGroup);
            }
            $blockGroups = array_merge_recursive($blockGroups, $blockGroup);
        }

        // First displayed group
        $alphaLemonBlocks = array("Default" => $this->extractGroup('alphalemon_internals', $blockGroups));
        // Last displayed group
        $notGrouped = $this->extractGroup('none', $blockGroups);
        // Sorts
        $this->recurKsort($alphaLemonBlocks);
        if (count($notGrouped) > 0) {
            $this->recurKsort($notGrouped);
        }

        // Exstracts and sorts all other groups
        $blocks = array();
        foreach ($blockGroups as $blockGroup) {
            $blocks = array_merge($blocks, $blockGroup);
        }
        $this->recurKsort($blocks);

        // Merges blocks
        $blocks = array_merge($alphaLemonBlocks, $blocks);
        $blocks = array_merge($blocks, $notGrouped);

        return $blocks;
    }

    /**
     * Removes a block when it is given as parameter to look for but it is not found between
     * any of the available blocks
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Model\AlBlock $block
     */
    protected function removeBlock(AlBlock $block)
    {
        $blockManagerItem = $this->blockManagersItems[0];
        $repository = clone($blockManagerItem->getBlockManager()->getBlockRepository());
        $repository->setRepositoryObject($block);
        $repository->delete();
    }

    private function extractGroup($group, &$groups)
    {
        if (!array_key_exists($group, $groups)) {
            return array();
        }

        $blocks = $groups[$group];
        if (!empty($blocks)) {
            unset($groups[$group]);
        }

        return $blocks;
    }

    private function recurKsort(&$array)
    {
        foreach ($array as &$value) {
            if (is_array($value)) $this->recurKsort($value);
        }

        return ksort($array);
    }
}
