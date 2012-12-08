<?php
/**
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks;

use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;
use AlphaLemon\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocks as AlPageBlocksBase;

/**
 * Extends the AlPageBlocks class to load blocks from the database
 *
 * @author alphalemon <webmaster@alphalemon.com>
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
     * @var \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface 
     */
    protected $factoryRepository = null;
    
    /**
     * @var \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\BlockRepositoryInterface 
     */
    protected $blockRepository;

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
     * @param  int $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException
     * 
     * @api
     */
    public function setIdPage($v)
    {
        if (!is_numeric($v)) {
            throw new General\InvalidParameterTypeException("The page id must be a numeric value");
        }

        $this->idPage = $v;

        return $this;
    }

    /**
     * The id of the language to retrieve
     *
     * @param  int $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException
     * 
     * @api
     */
    public function setIdLanguage($v)
    {
        if (!is_numeric($v)) {
            throw new General\InvalidParameterTypeException("The language id must be a numeric value");
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
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks
     * 
     * @api
     */
    public function refresh()
    {
        $this->setUpBlocks();

        return $this;
    }

    /**
     * Retrieves from the database the contents and arranges them by slots
     *
     * @return array
     */
    protected function setUpBlocks()
    {
        if (null === $this->idLanguage) {
            throw new General\ParameterIsEmptyException("Contents cannot be retrieved because the id language has not been set");
        }

        if (null === $this->idPage) {
            throw new General\ParameterIsEmptyException("Contents cannot be retrieved because the id page has not been set");
        }

        $this->blocks = array();
        $alBlocks = $this->blockRepository->retrieveContents(array(1, $this->idLanguage), array(1, $this->idPage));
        foreach ($alBlocks as $alBlock) {
            $this->blocks[$alBlock->getSlotName()][] = $alBlock;
        }
    }
}