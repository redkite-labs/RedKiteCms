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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks;

use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General;
use RedKiteLabs\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocks as AlPageBlocksBase;

/**
 * Extends the AlPageBlocks class to load blocks from the database
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlPageBlocks extends AlPageBlocksBase
{
    /**
     * @var int
     */
    protected $idPage = null;

    /**
     * @var int
     */
    protected $idLanguage = null;

    /**
     * @var \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface
     */
    protected $factoryRepository = null;

    /**
     * @var \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface
     */
    protected $blockRepository;

    protected $alBlocks = null;

    /**
     * Constructor
     *
     * @param AlFactoryRepositoryInterface $factoryRepository
     *
     * @api
     */
    public function __construct(AlFactoryRepositoryInterface $factoryRepository)
    {
        $this->factoryRepository = $factoryRepository;
        $this->blockRepository = $this->factoryRepository->createRepository('Block');
    }

    /**
     * The id of the page to retrieve
     *
     * @param  int                                                                                          $v
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocks
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * 
     * @api
     */
    public function setIdPage($v)
    {
        if (!is_numeric($v)) {
            throw new General\InvalidArgumentTypeException('The page id argument must be a numeric value');
        }

        $this->idPage = $v;

        return $this;
    }

    /**
     * The id of the language to retrieve
     *
     * @param  int                                                                                          $v
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocks
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * 
     * @api
     */
    public function setIdLanguage($v)
    {
        if (!is_numeric($v)) {
            throw new General\InvalidArgumentTypeException('The language id argument must be a numeric value');
        }

        $this->idLanguage = $v;

        return $this;
    }

    /**
     * Returns the current page id
     *
     * @return int
     *
     * @api
     */
    public function getIdPage()
    {
        return $this->idPage;
    }

    /**
     * Returns the current language id
     *
     * @return int
     *
     * @api
     */
    public function getIdLanguage()
    {
        return $this->idLanguage;
    }

    /**
     * Refreshes the blocks
     *
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocks
     *
     * @api
     */
    public function refresh()
    {
        $this->setUpBlocks();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setAlBlocks($blocks)
    {
        $this->alBlocks = $blocks;
        $this->arrangeBlocks();

        return $this;
    }
    
    /**
     * Returns the block types associated to this PageBlock
     * 
     * @return array
     */
    public function getBlockTypes()
    {
        $types = array();
        foreach($this->alBlocks as $block) {
            $type = $block->getType();
            if ( !in_array($type, $types)) {
                $types[] = $type;
            }
        }
        
        return $types;
    }

    /**
     * Retrieves from the database the contents and arranges them by slots
     *
     * @return array
     */
    protected function setUpBlocks()
    {
        if (null === $this->idLanguage) {
            throw new General\ArgumentIsEmptyException('Blocks cannot be retrieved because the id language has not been set');
        }

        if (null === $this->idPage) {
            throw new General\ArgumentIsEmptyException('Blocks cannot be retrieved because the id page has not been set');
        }

        $this->alBlocks = $this->fetchBlocks();
        $this->arrangeBlocks();
    }

    protected function fetchBlocks()
    {
        return $this->blockRepository->retrieveContents(array(1, $this->idLanguage), array(1, $this->idPage));
    }

    protected function arrangeBlocks()
    {
        $this->blocks = array();
        foreach ($this->alBlocks as $alBlock) {
            $this->blocks[$alBlock->getSlotName()][] = $alBlock;
        }
    }
}
