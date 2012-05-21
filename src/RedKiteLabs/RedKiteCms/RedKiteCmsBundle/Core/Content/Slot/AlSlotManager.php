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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateBase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\BlockModelInterface;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Translation\TranslatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlBlockModel;

/**
 * AlSlotManager represents a slot on a page. 
 * 
 * 
 * A slot is a zone on the page where one or more blocks lives. This object is responsible to manage the blocks that it contains, 
 * adding, editing and removing them. 
 * 
 * @api
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlSlotManager extends AlTemplateBase
{
    protected $slot;
    protected $lastAdded = null;
    protected $blockManagers = array();
    protected $forceSlotAttributes = false;
    
    /**
     * Constructor
     * 
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface $translator
     * @param AlSlot $slot
     * @param BlockModelInterface $blockModel
     * @param AlParametersValidatorInterface $validator
     * @param AlBlockManagerFactoryInterface $blockManagerFactory 
     */
    public function __construct(EventDispatcherInterface $dispatcher, TranslatorInterface $translator, AlSlot $slot, BlockModelInterface $blockModel, AlParametersValidatorInterface $validator = null, AlBlockManagerFactoryInterface $blockManagerFactory = null)
    {
        parent::__construct($dispatcher, $translator, $validator, $blockManagerFactory);
        
        $this->slot = $slot;
        $this->blockModel = $blockModel;        
    }
    
    /**
     * Sets the slot object
     * 
     * @api
     * @param AlSlot $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager 
     */
    public function setSlot(AlSlot $v)
    {
        $this->slot = $v;
        
        return $this;
    }
    
    /**
     * Returns the slot object
     * 
     * @api
     * @return \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot
     */
    public function getSlot()
    {
        return $this->slot;
    }
    
    /**
     * Sets the block model object
     * 
     * @api
     * @param BlockModelInterface $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager 
     */
    public function setBlockModel(BlockModelInterface $v)
    {
        $this->blockModel = $v;
        
        return $this;
    }
    
    /**
     * Returns the block manager object
     * 
     * @api
     * @return \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot
     */
    public function getBlockModel()
    {
        return $this->blockModel;
    }

    /**
     * Sets the slot manager's behavior when a new block is added
     * 
     * @api
     * When true forces the add operation to use the default AlSlot attributes for 
     * the new block type
     * 
     * @param Boolean
     */
    public function setForceSlotAttributes($v)
    {
        if (!is_bool($v)) {
            throw new \InvalidArgumentException("setForceSlotAttributes accepts only boolean values");
        }
        
        $this->forceSlotAttributes = $v;
        
        return $this;
    }
    
    /**
     * Returns the slot manager's behavior when a new block is added
     * 
     * @api
     * @return boolean 
     */
    public function getForceSlotAttributes()
    {
        return $this->forceSlotAttributes;
    }
    
    /**
     * Returns the slot's blocks repeated status
     * 
     * @api
     * @return string 
     */
    public function getRepeated()
    {
        return $this->slot->getRepeated();
    }
    
    /**
     * Returns the name of the slot
     * 
     * @api
     * @return string 
     */
    public function getSlotName()
    {
        return $this->slot->getSlotName();
    }

    /**
     * Returns the block managers
     * 
     * @api
     * @return array 
     */
    public function getBlockManagers()
    {
        return $this->blockManagers;
    }
    
    /**
     * Returns the first block manager placed on the slot
     * 
     * @api
     * @return null|AlBlockManager
     */
    public function first()
    {
        return ($this->length() > 0) ? $this->blockManagers[0] : null;
    }
    
    /**
     * Returns the last block manager placed on the slot
     * 
     * @api
     * @return null|AlBlockManager
     */
    public function last()
    {
        return ($this->length() > 0) ? $this->blockManagers[$this->length() - 1] : null;
    }
    
    /**
     * Returns the block manager at the given index.
     * 
     * @api
     * @return null|AlBlockManager
     */
    public function indexAt($index)
    {
        return ($index >= 0 && $index <= $this->length() - 1) ? $this->blockManagers[$index] : null;
    }
    
    /**
     * Returns the number of block managers managed by the slot manager
     * 
     * @api
     * @return int
     */
    public function length()
    {
        return count($this->blockManagers);
    }
    
    /**
     * Returns the last block manager added to the slot manager
     * 
     * @api
     * @return AlBlockManager object or null
     */
    public function lastAdded()
    {
        return $this->lastAdded;
    }

    /**
     * Adds a new AlBlock object to the slot
     * 
     * The created block managed is added to the collection. When the $referenceBlockId param is valorized,
     * the new block is created under the block identified by the given id
     * 
     * @api
     * @param int $idLanguage
     * @param type $idPage
     * @param type $type                The block type. By default a Text block is added
     * @param type $referenceBlockId    The id of the reference block. When given, the block is placed below this one
     * @return null|boolean
     * @throws Exception
     * @throws \InvalidArgumentException 
     */
    public function addBlock($idLanguage, $idPage, $type = 'Text', $referenceBlockId = null)
    {
        try
        {
            switch ($this->slot->getRepeated()) {
                case 'site':
                    $idPage = 1;
                    $idLanguage = 1;
                    //idGroup = 1; //TODO
                    break;
                case 'language':
                    $idPage = 1;
                    //idGroup = 1; //TODO
                    break;
                case 'group':
                    $idPage = 1;
                    break;
                case 'page':
                    //idGroup = 1; //TODO
                    break;
            }
            
            $alBlockManager = $this->blockManagerFactory->createBlock($this->blockModel, $type);
            if (null === $alBlockManager) {
                throw new \InvalidArgumentException("The $type type does not exist");
            }
            
            if (count($this->blockModel->retrieveContents($idLanguage, $idPage, $this->slot->getSlotName())) > 0) {
                return null;
            }
                        
            $result = true;
            $this->blockModel->startTransaction();

            // Find the block position
            $leftArray = array();
            $rightArray = array();
            $managersLength = $this->length();
            $position = $managersLength + 1;
            if (null !== $referenceBlockId) {
                $index = $this->getBlockManagerIndex($referenceBlockId);
                if (null !== $index) {
                    // The new block must de added below the current one, so it must retrieve the block manager down the reference manager
                    $index += 1;              
                    if ($managersLength > $index) {
                        $leftArray = array_slice($this->blockManagers, 0 , $index);
                        $rightArray = array_slice($this->blockManagers, $index , $managersLength - 1);

                        $manager = $this->blockManagers[$index];
                        $position = $manager->get()->getContentPosition();
                        $result = $this->adjustPosition('add', $rightArray);
                    }
                }
            }

            if (false !== $result) {
                if ($this->forceSlotAttributes) {
                    $type = $this->slot->getBlockType();
                }
                
                $values = array(
                  "PageId"          => $idPage,
                  "LanguageId"      => $idLanguage,
                  "SlotName"        => $this->slot->getSlotName(),
                  "ClassName"       => $type,
                  "ContentPosition" => $position
                );
                
                if ($this->forceSlotAttributes) {
                    $values["HtmlContent"] = $this->slot->getHtmlContent();
                    $values["ExternalJavascript"] = $this->slot->getExternalJavascript();
                    $values["InternalJavascript"] = $this->slot->getInternalJavascript();
                    $values["ExternalStylesheet"] = $this->slot->getExternalStylesheet();
                    $values["InternalStylesheet"] = $this->slot->getInternalStylesheet();
                }
                
                $alBlockManager->set(null);
                $result = $alBlockManager->save($values);
            }

            if ($result) {
                $this->blockModel->commit(); 
                
                if (!empty($leftArray) || !empty($rightArray)) {
                    $index = $position - 1;
                    $this->blockManagers = array_merge($leftArray, array($index => $alBlockManager), $rightArray);
                }
                else {
                    $this->blockManagers[] = $alBlockManager;
                }
                
                $this->lastAdded = $alBlockManager;
            }
            else {
                $this->blockModel->rollBack();
            }
            
            return $result;
        }
        catch (\Exception $e) {
            if (isset($this->blockModel) && $this->blockModel !== null) {
                $this->blockModel->rollBack();
            }
            
            throw $e;
        }
    }
    
    /**
     * Edits the block 
     * 
     * @api
     * @param   int       $idBlock  The id of the block to edit
     * @param   array     $values   The new values
     * 
     * @return  boolean    
     */
    public function editBlock($idBlock, array $values)
    {
        $blockManager = $this->getBlockManager($idBlock);
        if ($blockManager != null) {
            try {
                $this->blockModel->startTransaction();

                $result = $blockManager->save($values);

                if ($result) {
                    $this->blockModel->commit(); 
                }
                else {
                    $this->blockModel->rollBack();
                }
                
                return $result;
            }
            catch (\Exception $e) {
                if (isset($this->blockModel) && $this->blockModel !== null) {
                    $this->blockModel->rollBack();
                }

                throw $e;
            }
        }
    }
    
    /**
     * Deletes the block from the slot
     * 
     * @api
     * @param   int       $idBlock The id of the block to remove
     * @return  boolean 
     */
    public function deleteBlock($idBlock)
    {
        $leftArray = array();
        $rightArray = array();
        $info = $this->getBlockManagerAndIndex($idBlock);
        if ($info != null) {
            $index = $info['index'];
            $leftArray = array_slice($this->blockManagers, 0 , $index); 
            $rightArray = array_slice($this->blockManagers, $index + 1, $this->length() - 1);
                 
            try
            {
                $this->blockModel->startTransaction();
            
                // Adjust the blocks position
                $result = $this->adjustPosition('del', $rightArray);
                if (false !== $result) {
                    $result = $info['manager']->delete();
                }
                
                if ($result) {
                    $this->blockModel->commit(); 
                    
                    $this->blockManagers = array_merge($leftArray, $rightArray);
                }
                else {
                    $this->blockModel->rollBack();
                }

                return $result;
            }
            catch(\Exception $e)
            {
                if (isset($this->blockModel) && $this->blockModel !== null) {
                    $this->blockModel->rollBack();
                }
                
                throw $e;
            }
        }
        
        return null;
    }
    
    /**
     * Deletes all the blocks managed by the slot
     * 
     * @api
     * @return  boolean 
     */
    public function deleteBlocks()
    {
        try
        {
            if(count($this->blockManagers) > 0) {
                $result = null;
                $this->blockModel->startTransaction();

                foreach($this->blockManagers as $blockManager) {
                    $result = $blockManager->delete();
                    if (!$result) {
                        break;
                    }
                }

                if ($result) {
                    $this->blockModel->commit(); 
                    $this->blockManagers = array();

                    return true;
                }
                else {
                    $this->blockModel->rollBack();

                    return false;
                }
            }
        }
        catch(\Exception $e)
        {
            if (isset($this->blockModel) && $this->blockModel !== null) {
                $this->blockModel->rollBack();
            }
            
            throw $e;
        }
    }
    
    /**
     * Retrieves the block manager by the block's id
     * 
     * @api
     * @param   int  $idBlock The id of the block to retrieve  
     * @return  AlBlockManager|null
     */
    public function getBlockManager($idBlock)
    {
        $info = $this->getBlockManagerAndIndex($idBlock);
        
        return (null !== $info) ? $info['manager'] : null;
    }
    
    /**
     * Retrieves the block manager index by the block's id
     * 
     * @api
     * @param   int  $idBlock The id of the block to retrieve  
     * @return  int 
     */
    public function getBlockManagerIndex($idBlock)
    {
        $info = $this->getBlockManagerAndIndex($idBlock);
        
        return (null !== $info) ? $info['index'] : null;
    }
    
    /**
     * @deprecated
     */
    public function getContentManagers()
    {
        throw new \Exception ('Use the getBlockManagers() method instead of this one');
    }
    
    /**
     * @deprecated
     */
    public function getContentManager($idContent)
    {
        throw new \Exception ('Use the getBlockManager() method instead of this one');
    }
    
    /**
     * @deprecated
     */
    public function getContentManagerIndex($idContent)
    {
        throw new \Exception ('Use the getBlockManagerIndex() method instead of this one');
    }
    
    /**
     * Returns the managed blocks as an array
     * 
     * @api
     * @return array 
     */
    public function toArray()
    {
        $result = array();
        foreach($this->blockManagers as $blockManager) {
            if (null !== $blockManager) {
                $result[] = $blockManager->toArray();
            }
        }
        
        return $result;
    }
    
    /**
     * Sets up the block managers.
     * 
     * 
     * When the blocks have not been given, it retrieves all the pages's contents saved on the slot
     * 
     * @api
     * @param array $alBlocks 
     */
    public function setUpBlockManagers(array $alBlocks)
    {
        foreach($alBlocks as $alBlock)
        {
            $alBlockManager = $this->blockManagerFactory->createBlock($this->blockModel, $alBlock);
            $this->blockManagers[] = $alBlockManager;
        } 
    }
    
    /**
     * Retrieves the block manager and the index by the block's id
     * 
     * @api
     * @param   int     $idBlock The id of the block to retrieve  
     * @return  null|array 
     */
    protected function getBlockManagerAndIndex($idBlock)
    {
        foreach($this->blockManagers as $index => $blockManager) {
            if ($blockManager->get()->getId() == $idBlock) {
                return array('index' => $index, 'manager' => $blockManager);
            }
        }
        
        return null;
    }
    
    /**
     * Adjusts the blocks position on the slot, when a new block is added or a block is deleted. 
     * 
     * 
     * When in *add* mode, it creates a space between the adding block's position and
     * the blocks below, incrementing their position by one
     * 
     * When in *del* mode, decrements by 1 the position of the blocks placed below the
     * removing block
     * 
     * @api
     * @param   string      $op         The operation to do. It accepts add or del as valid values
     * @param   array       $managers   An array of block managers
     * @return  boolean 
     * @throws  \InvalidArgumentException    When an invalid option is given to the $op param
     * 
     */
    protected function adjustPosition($op, array $managers)
    {
        try 
        {
            // Checks the $op parameter. If doesn't match, throwns and exception
            $required = array("add", "del");
            if (!in_array($op, $required)) {
                throw new \InvalidArgumentException($this->translator->trans('The %className% adjustPosition protected method requires one of the following values: "%options%". Your input parameter is: "%parameter%"', array('%className%' => get_class($this), '%options%' => $required, '%parameter%' => $op), 'al_slot_manager_exceptions'));
            }
            
            if (count($managers) > 0) {
                $result = null;
                $this->blockModel->startTransaction();
                foreach ($managers as $blockManager) {
                    $block = $blockManager->get();
                    $position = ($op == 'add') ? $block->getContentPosition() + 1 : $block->getContentPosition() - 1;
                    $result = $this->blockModel
                                    ->setModelObject($block)
                                    ->save(array("ContentPosition" => $position));

                    if (!$result) break;
                }

                if ($result) {
                    $this->blockModel->commit();
                }
                else {
                    $this->blockModel->rollBack();
                }

                return $result;
            }
        }
        catch(\Exception $e)
        {
            if (isset($this->blockModel) && $this->blockModel !== null) {
                $this->blockModel->rollBack();
            }
            
            throw $e;
        }
    }
}