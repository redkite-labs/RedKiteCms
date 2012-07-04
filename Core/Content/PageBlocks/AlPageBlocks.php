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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\BlockRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;
use AlphaLemon\PageTreeBundle\Core\PageBlocks\AlPageBlocks as AlPageBlocksBase;

/**
 * Extends the AlPageBlocks class to load blocks from the database
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlPageBlocks extends AlPageBlocksBase
{
    protected $idPage = null;
    protected $idLanguage = null;
    protected $blockModel;
    protected $dispatcher;

    /**
     * Constructor
     *
     * @param EventDispatcherInterface $dispatcher
     * @param BlockRepositoryInterface $blockModel
     */
    public function __construct(EventDispatcherInterface $dispatcher, BlockRepositoryInterface $blockModel)
    {
        $this->dispatcher = $dispatcher;
        $this->blockModel = $blockModel;
    }

    /**
     * The id of the page to retrieve
     *
     * @param int $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks
     * @throws General\InvalidParameterTypeException
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
     * @param type $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks
     * @throws General\InvalidParameterTypeException
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
     */
    public function getIdPage()
    {
        return $this->idPage;
    }

    /**
     * Returns the current language id
     *
     * @return int
     */
    public function getIdLanguage()
    {
        return $this->idLanguage;
    }

    /**
     * Refreshes the blocks
     *
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks
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
        $alBlocks = $this->blockModel->retrieveContents(array(1, $this->idLanguage), array(1, $this->idPage));
        foreach ($alBlocks as $alBlock) {
            $this->blocks[$alBlock->getSlotName()][] = $alBlock;
        }
    }
}