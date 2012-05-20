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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageContentsContainer; 

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\BlockModelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;

/**
 * AlBlockManager wraps an AlBlock object. 
 * 
 * 
 * AlBlockManager manages an AlBlock object, implementig the base methods to add, edit and delete it and 
 * provides several methods to change the behavior of the block, when it is rendered on the page.
 * 
 * Every new block content must inherit from this class.
 * 
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlPageContentsContainer implements AlPageContentsContainerInterface
{
    protected $idPage = null;
    protected $idLanguage = null;
    protected $blockModel;
    protected $dispatcher;
    protected $blocks = array(); 
    
    public function __construct(EventDispatcherInterface $dispatcher, BlockModelInterface $blockModel)
    {
        $this->dispatcher = $dispatcher;
        $this->blockModel = $blockModel;
    }
    
    public function setIdPage($v)
    {
        if (!is_numeric($v)) {
            throw new General\InvalidParameterTypeException("The page id must be a numeric value");
        }
        
        $this->idPage = $v;
        
        return $this;
    }
    
    public function setIdLanguage($v)
    {
        if (!is_numeric($v)) {
            throw new General\InvalidParameterTypeException("The language id must be a numeric value");
        }
        
        $this->idLanguage = $v;
        
        return $this;
    }
    
    public function getIdPage()
    {
        return $this->idPage;
    }
    
    public function getIdLanguage()
    {
        return $this->idLanguage;
    }

    public function getBlocks()
    {
        return $this->blocks;
    }
    
    public function getSlotBlocks($slotName)
    {
        return (array_key_exists($slotName, $this->blocks)) ? $this->blocks[$slotName] : array();
    }
    
    public function refresh()
    {
        $this->setUpBlocks();
        
        return $this;
    }
          
    /**
     * Retrieves from the database the contents by slot
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