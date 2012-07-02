<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
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
 * @author alphalemon <webmaster@alphalemon.com>
 */
namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * AlBlockManagerFactory is the object responsible to create a new AlBlockManager object
 *
 * AlBlockManagerFactory collects all the AlBlockManager objects and uses the to create
 * the new object from an existing AlBlock object or by a valid string that identifies
 * a valid AlBlockType.
 *
 * @author alphalemon <webmaster@alphalemon.com>
 *
 */
class AlBlockManagerFactory implements AlBlockManagerFactoryInterface
{
    private $blockManagers = array();
    private $translator = null;

    /**
     * Constructor
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(TranslatorInterface $translator = null)
    {
        $this->translator = $translator;
    }

    /**
     * Adds a block manager base object.
     *
     * This method is usually called by the AlBlocksCompilerPass object
     *
     * @param AlBlockManagerInterface $blockManager
     * @param array $attributes
     */
    public function addBlockManager(AlBlockManagerInterface $blockManager, array $attributes)
    {
        $this->blockManagers[] = new AlBlockManagerFactoryItem($blockManager, $attributes);
    }

    /**
     * { @inheritDoc }
     */
    public function createBlockManager($block)
    {
        $isAlBlock = $block instanceof AlBlock;
        $blockType = $isAlBlock ? strtolower($block->getClassName()) : strtolower($block);
        if(!preg_match('/app_[a-z]+.block/', $blockType))
        {
            $blockType = sprintf('app_%s.block', $blockType);
        }

        $items = count($this->blockManagers);
        if($items == 0) {
            return null;
        }

        foreach($this->blockManagers as $blockManagerItem)
        {
            if($blockManagerItem->getId() == $blockType) {
                $blockManager = $blockManagerItem->getBlockManager();
                $blockManager = clone($blockManager);
                if ($isAlBlock) $blockManager->set($block);
                if (null !== $this->translator) $blockManager->setTranslator($this->translator);

                return $blockManager;
            }
        }

        if ($isAlBlock) {
            $this->removeBlock($block);
        }

        return null;
    }

    /**
     * Returns an array that contains the blocks description objects that can be created by the
     * factory, ordered by group
     *
     * @return array
     */
    public function getBlocks()
    {
        $blockGroups = array();
        foreach($this->blockManagers as $blockManager)
        {
            $blockGroups[$blockManager->getGroup()][] = $blockManager->getDescription();
        }

        $blocks = $this->extractGroup('alphalemon_internals', $blockGroups);
        $notGrouped = $this->extractGroup('none', $blockGroups);
        foreach($blockGroups as $blockGroup)
        {
            sort($blockGroup);
            $blocks = array_merge($blocks, $blockGroup);
        }
        $blocks = array_merge($blocks, $notGrouped);

        return $blocks;
    }

    /**
     * Removes a block when it is given as parameter to look for but it is not found between
     * any of the available blocks
     *
     * @param AlBlock $block
     */
    protected function removeBlock(AlBlock $block)
    {
        $blockManagerItem = $this->blockManagers[0];
        $modelObject = clone($blockManagerItem->getBlockManager()->getBlockModel());
        $modelObject->setModelObject($block);
        $modelObject->delete();
    }

    private function extractGroup($group, &$groups)
    {
        if(!array_key_exists($group, $groups)) {
            return array();
        }

        $blocks = $groups[$group];
        if(!empty($blocks)) {
            sort($blocks);
            unset($groups[$group]);
        }

        return $blocks;
    }
}