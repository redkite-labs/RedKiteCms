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
use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;

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
    private $alPage;
    private $alLanguage;
    private $dispatcher;
    private $blocks = array();
    
    public function __construct(EventDispatcherInterface $dispatcher, AlLanguage $alLanguage, AlPage $alPage)
    {
        $this->alPage = $alPage;
        $this->alLanguage = $alLanguage;
        $this->dispatcher = $dispatcher;
        
        $this->setUpBlocks();
    }
    
    public function setAlPage(AlPage $v)
    {
        $this->alPage = $v;
        
        return $this;
    }
    
    public function setAlLanguage(AlLanguage $v)
    {
        $this->alLanguage = $v;
        
        return $this;
    }
    
    public function getAlPage()
    {
        return $this->alPage;
    }
    
    public function getAlLanguage()
    {
        return $this->alLanguage;
    }
    
    public function getBlocks()
    {
        return $this->blocks;
    }
    
    public function getSlotBlocks($slotName)
    {
        return $this->blocks[$slotName];
    }
    
    /**
     * Retrieves from the database the contents by slot
     * 
     * @return array
     */
    protected function setUpBlocks()
    {
        $idLanguage = array(1, $this->alLanguage->getId());
        $idPage = array(1, $this->alPage->getId());
        
        $alBlocks = AlBlockQuery::create()->setDispatcher($this->dispatcher)->retrieveContents($idLanguage, $idPage)->find();
        foreach ($alBlocks as $alBlock) {
            $this->blocks[$slotName->getSlotName()][] = $alBlock; // contents[$alBlock->getSlotName()][] = $alBlock;
        }
    }
}