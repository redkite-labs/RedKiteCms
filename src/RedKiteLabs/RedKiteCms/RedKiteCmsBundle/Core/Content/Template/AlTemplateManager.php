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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Translation\TranslatorInterface;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageContentsContainer\AlPageContentsContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;

/**
 * AlTemplateManager manages the slots where the page contents are saved.
 * 
 * The AlTemplateManager object collects the slots from the templated defined by and object derived 
 * from an AlTemplateSlotsInterface, then populates them using the contents defined by an object
 * derived from an AlPageContentsContainerInterface.
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlTemplateManager extends AlTemplateBase
{
    protected $slotManagers = array();
    protected $templateSlotsClass;
    private $templateSlots = null;
    
    /**
     * Constructor
     * 
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface $translator
     * @param AlTemplateSlotsInterface $templateSlots
     * @param AlPageContentsContainerInterface $pageContentsContainer
     * @param AlBlockManagerFactoryInterface $blockManagerFactory
     * @param \PropelPDO $connection 
     */
    public function __construct(EventDispatcherInterface $dispatcher, TranslatorInterface $translator, AlTemplateSlotsInterface $templateSlots, AlPageContentsContainerInterface $pageContentsContainer, AlBlockManagerFactoryInterface $blockManagerFactory = null, \PropelPDO $connection = null)
    {
        parent::__construct($dispatcher, $translator, $blockManagerFactory, $connection);
        
        $this->templateSlots = $templateSlots;
        $this->pageContentsContainer = $pageContentsContainer;
        
        $this->setUpSlotManagers();
    }
    
    /**
     * Sets the current AlTemplateSlots object
     * 
     * @param AlTemplateSlotsInterface $templateSlots 
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager 
     */
    public function setTemplateSlots(AlTemplateSlotsInterface $templateSlots)
    {
        $this->templateSlots = $templateSlots;
        
        if (null !== $this->pageContentsContainer) {
            $this->setUpSlotManagers();
        }
        
        return $this;
    }
    
    /**
     * Sets the page contents container object
     * 
     * @param AlPageContentsContainerInterface $pageContentsContainer
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager 
     */
    public function setPageContentsContainer(AlPageContentsContainerInterface $pageContentsContainer)
    {
        $this->pageContentsContainer = $pageContentsContainer;
        
        if (null !== $this->templateSlots) {
            $this->setUpSlotManagers();
        }
        
        return $this;
    }
    
    /**
     * Returns the current AlTemplateSlots object
     * 
     * @api
     * @return \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlots 
     */
    public function getTemplateSlots()
    {
        return $this->templateSlots;
    }
    
    /**
     * Returns the current page contents container object
     * 
     * @api
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageContentsContainer\AlPageContentsContainer 
     */
    public function getPageContentsContainer()
    {
        return $this->pageContentsContainer;
    }
    
    /**
     * Returns the managed slot managers
     * 
     * @api
     * @return array
     */
    public function getSlotManagers()
    {
        return $this->slotManagers;
    }
    
    /**
     * Returns the slot manager that matches the given parameter
     * 
     * @api
     * @param string $slotName
     * @return null|AlSlotManager 
     */
    public function getSlotManager($slotName)
    {
        if (!is_string($slotName))
        {
            return null;
        }
        
        return (array_key_exists($slotName, $this->slotManagers)) ? $this->slotManagers[$slotName] : null;
    }
    
    /**
     * Returns the slot manager as an array
     * 
     * @api
     * @param string $slotName
     * @return array
     * @throws \InvalidArgumentException 
     */
    public function slotToArray($slotName)
    {
        if (!is_string($slotName)) {
            throw new \InvalidArgumentException($this->translator->trans("slotToArray accepts only strings"));
        }
        
        if (!array_key_exists($slotName, $this->slotManagers)) {
            return array();
        }
        
        $slotManager = $this->slotManagers[$slotName];   
        
        return $slotManager->toArray();
    }
    
    /**
     * Returns all the slotManagers as array
     * 
     * @api
     * @return array 
     */
    public function slotsToArray() 
    {
        $slotContents = array();        
        foreach ($this->slotManagers as $slotName => $slot)
        {
            $slotContents[$slotName] = $slot->toArray();
        }
        
        return $slotContents;
    }
    
    /**
     * Populates each slot and saves them to the database. 
     * 
     * This method is used to add a new page based on the template managed by this object. The slots
     * are filled up using the dafault values provided by each single slot.
     * 
     * @api
     * @param int $idLanguage   The id that identified the language to add
     * @param int $idPage       The id that identified the page to add
     * @return boolean
     * @throws \Exception 
     */
    public function populate($idLanguage, $idPage)
    {
        try
        {
            $rollBack = false;
            $this->connection->beginTransaction();
            
            foreach ($this->slotManagers as $slot) {
                $slot->setForceSlotAttributes(true);
                $result = $slot->addBlock($idLanguage, $idPage); 
                if (null !== $result) {
                    $rollBack = !$result;
                    if ($rollBack) break;
                }
            }
            
            if (!$rollBack) {
                $this->connection->commit(); 
                
                return true;
            }
            else {
                $this->connection->rollback();
                
                return false;
            }
        }
        catch(\Exception $e)
        {
            if (isset($this->connection) && $this->connection !== null) {
                $this->connection->rollback();
            }
            
            throw $e;
        }
    }
    
    /**
     * Creates the slot managers from the current template slot class
     */
    protected function setUpSlotManagers()
    {   
        $slots = $this->templateSlots->getSlots();
        foreach ($slots as $slotName => $slot) {
            $this->slotManagers[$slotName] = $this->createSlotManager($slot);
        }
        
        // Looks for existing slots on previous theme, not included in the theme in use
        $orphanSlots = array_diff(array_keys($this->pageContentsContainer->getBlocks()), array_keys($slots));
        foreach ($orphanSlots as $slotName) {   
            if ($slotName != "") {
                $slot = new AlSlot($slotName);
                $this->slotManagers[$slotName] = $this->createSlotManager($slot);
            }
        }
    }
    
    /**
     * Create the slot manager for the given slot
     * 
     * @param AlSlot $slot
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager 
     */
    protected function createSlotManager(AlSlot $slot)
    {
        $slotName = $slot->getSlotName();
        $alBlocks = $this->pageContentsContainer->getSlotBlocks($slotName);
        
        return new AlSlotManager($this->dispatcher, $this->translator, $slot, $this->blockManagerFactory, $alBlocks);   
    }
}