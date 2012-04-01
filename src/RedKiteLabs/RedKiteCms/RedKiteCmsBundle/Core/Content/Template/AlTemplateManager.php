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

use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;

use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;

use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;

/**
 * AlTemplateManager represents a template which is made by a serie of slots. It is responsibile to fill up 
 * the slots managers and to manage them
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class AlTemplateManager extends AlTemplateBase
{
    protected $slotManagers = array();
    protected $themeName;
    protected $templateName;
    private $templateSlots = null;
    private $templateSlotClass = null;
    
    /**
     * Constructor
     * 
     * @param ContainerInterface $container
     * @param AlPage        $alPage
     * @param AlLanguage    $alLanguage
     * @param string        $themeName
     * @param string        $templateName
     * @param string        $templateSlotClass 
     */
    public function __construct(ContainerInterface $container, AlPage $alPage = null, AlLanguage $alLanguage = null, $themeName = null, $templateName = null, $templateSlotClass = null)
    {
        parent::__construct($container, $alPage, $alLanguage);
        
        if(null !== $alPage) $this->alPage = $alPage;
        if(null !== $alLanguage){ $this->alLanguage = $alLanguage;} 
        
        $this->themeName = (null === $themeName) ? $this->container->get('al_page_tree')->getThemeName() : $themeName;
        $this->templateName = (null === $templateName) ? (null === $alPage) ? $this->alPage->getTemplateName() : $alPage->getTemplateName() : $templateName; 
        
        if(null !== $templateSlotClass)
        {
            $this->setUpSlotManagers($templateSlotClass);
        }
        else
        {
            $this->setUpSlotManagers();
        }
    }
    
    /**
     * Returns the current AlTemplateSlot object
     * 
     * @return string 
     */
    public function getTemplateSlots()
    {
        return $this->templateSlots;
    }
    
    /**
     * Returns the custom template slot class
     * 
     * @return string 
     */
    public function getTemplateSlotClass()
    {
        return $this->templateSlotClass;
    }
    
    /**
     * Set a custom template slot class
     * 
     * @return string 
     */
    public function setTemplateSlotClass($v)
    {
        $this->setUpSlotManagers($v);
    }
    
    /**
     * Returns the template name
     * 
     * @return string 
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }
    
    /**
     * Returns the slot managers
     * 
     * @return array
     */
    public function getSlotManagers()
    {
        return $this->slotManagers;
    }
    
    /**
     *
     * @param type $slotName
     * 
     * @return type 
     */
    public function getSlotManager($slotName)
    {
        if(!is_string($slotName))
        {
            return null;
        }
        
        return (array_key_exists($slotName, $this->slotManagers)) ? $this->slotManagers[$slotName] : null;
    }
    
    /**
     * Returns the slot manager when exists for the given argument
     * 
     * @param string $slotName
     * @return array
     */
    public function slotToArray($slotName)
    {
        if(!is_string($slotName))
        {
            throw new \InvalidArgumentException(AlToolkit::translateMessage($this->container, "slotToArray accepts only strings"));
        }
        
        if(!array_key_exists($slotName, $this->slotManagers))
        {
            return array();
        }
        
        $slotManager = $this->slotManagers[$slotName];        
        return $slotManager->toArray();
    }
    
    /**
     * Returns the slotManagers as array
     * 
     * @return array 
     */
    public function slotsToArray()
    {
        $slotContents = array();        
        foreach($this->slotManagers as $slotName => $slot)
        {
            $slotContents[$slotName] = $slot->toArray();
        }
        
        return $slotContents;
    }
    
    /**
     * Populates each slot with its default content and saves them to the database. This method is usually used
     * to add a new page based on the template managed by this class
     * 
     * @return boolean
     */
    public function populate()
    {
        try
        {
            $rollBack = false;
            $this->connection->beginTransaction();
            
            foreach($this->slotManagers as $slotName => $slot)
            {
                $slot->setUseSlotAttributes(true);
                $result = $slot->addBlock(); 
                if(null !== $result)
                {
                    $rollBack = !$result;
                    if($rollBack) break;
                }
            }
            
            if (!$rollBack)
            {
                $this->connection->commit(); 
                return true;
            }
            else
            {
                $this->connection->rollback();
                return false;
            }
        }
        catch(\Exception $e)
        {
            if(isset($this->connection) && $this->connection !== null) $this->connection->rollback();
            throw $e;
        }
    }
    
    /**
     * Creates the slot managers from the current template slot class
     */
    protected function setUpSlotManagers($class = null)
    {
        $templateSlotsClass = (null === $class) ? \sprintf('\AlphaLemon\Theme\%s\Core\Slots\%s%sSlots', $this->themeName, $this->themeName, ucfirst($this->templateName)) : $class;
        if(!\class_exists($templateSlotsClass))
        {
            throw new \RuntimeException(AlToolkit::translateMessage($this->container, 'The class %className% does not exist. You must create a [ThemeName][TemplateName]Slots class for each template of your theme', array('%className%' => $templateSlotsClass)));
        }
        $this->templateSlotClass = $templateSlotsClass;
        
        $this->templateSlots = new $templateSlotsClass($this->container);                
        $contents = $this->retrieveContents();
        
        $slots = $this->templateSlots->getSlots();
        foreach($slots as $slotName => $slot)
        {
            $alBlocks = array_key_exists($slotName, $contents) ? $contents[$slotName] : array();
            $slotManager = new AlSlotManager($this->container, $slot, $this->alPage, $this->alLanguage, $alBlocks);
            
            $this->slotManagers[$slotName] = $slotManager;
        }
        
        // Looks for existing slots on previous theme, not included in the theme in use
        $orphanSlots = array_diff(array_keys($contents), array_keys($slots));
        foreach($orphanSlots as $slotName)
        {   
            if($slotName != "")
            {
                $slot = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot($slotName);
                $alBlocks = array_key_exists($slotName, $contents) ? $contents[$slotName] : array();
                $slotManager = new AlSlotManager($this->container, $slot, $this->alPage, $this->alLanguage, $alBlocks);

                $this->slotManagers[$slotName] = $slotManager;
            }
        }
    }
    
    /**
     * Retrieves from the database the contents by slot
     * 
     * @return array
     */
    protected function retrieveContents()
    {
        $contents = array();
        
        $idLanguage = array(1, $this->alLanguage->getId());
        $idPage = array(1, $this->alPage->getId());
        
        $alBlocks = AlBlockQuery::create()->setContainer($this->container)->retrieveContents($idLanguage, $idPage)->find();
        foreach($alBlocks as $alBlock)
        {
            $contents[$alBlock->getSlotName()][] = $alBlock;
        }
        
        return $contents;
    }
}