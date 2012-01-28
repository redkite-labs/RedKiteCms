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
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactory;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlContentQuery;

/**
 * AlSlotManager represents a slot, the cage on the page where the page contents (blocks) live, which is responsible to add, 
 * edit and remove the contained blocks
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class AlSlotManager extends AlTemplateBase
{
    protected $slot;
    protected $contentManagers = null;
    protected $useSlotAttributes = false;
    
    /**
     * Constructor
     * 
     * @param ContainerInterface $container
     * @param AlSlot $slot                      The slot to manage
     * @param AlPage $alPage                    The AlPage object where the slot lives. When null the current page is used
     * @param AlLanguage $alLanguage            The AlLanguage object where the slot lives. When null the current language is used
     * @param array $alContents                 The contents to manage.When null the object retrieves them on its own. Contents are injected
     *                                          just to reduce the number of queries and optimize performances
     */
    public function __construct(ContainerInterface $container, AlSlot $slot, AlPage $alPage = null, AlLanguage $alLanguage = null, array $alContents = null)
    {
        parent::__construct($container, $alPage, $alLanguage);
        
        $this->slot = $slot;
        $this->setUpContentManagers($alContents);
    }
    
    /**
     * Returns the mode how the slot add a new content
     * 
     * @return boolean 
     */
    public function getUseSlotAttributes()
    {
        return $this->useSlotAttributes;
    }
    
    /**
     * Sets the mode how the slot add a new content. When true adds the content using the default slot attributes for the block
     * type and block content
     * 
     * @param bool
     */
    public function setUseSlotAttributes($v)
    {
        if(!is_bool($v))
        {
            throw new \InvalidArgumentException("setUseSlotAttributes accepts only boolean values");
        }
        
        $this->useSlotAttributes = $v;
    }
    
    /**
     * Returns the slot's contents repeated status
     * 
     * @return string 
     */
    public function getRepeated()
    {
        return $this->slot->getRepeated();
    }
    
    /**
     * Returns the name of the slot
     * 
     * @return string 
     */
    public function getSlotName()
    {
        return $this->slot->getSlotName();
    }

    /**
     * Returns the contents managers
     * @return array 
     */
    public function getContentManagers()
    {
        return $this->contentManagers;
    }
    
    /**
     * Returns the first content manager
     * 
     * @return AlBlockManager object or null when empty
     */
    public function first()
    {
        return ($this->length() > 0) ? $this->contentManagers[0] : null;
    }
    
    /**
     * Returns the last content manager
     * 
     * @return AlBlockManager object or null when empty
     */
    public function last()
    {
        return ($this->length() > 0) ? $this->contentManagers[$this->length() - 1] : null;
    }
    
    /**
     * Returns the content manager at the given index.
     * 
     * @return AlBlockManager object or null when requesting a non existent index
     */
    public function indexAt($index)
    {
        return ($index >= 0 && $index <= $this->length() - 1) ? $this->contentManagers[$index] : null;
    }
    
    /**
     * The number of contents managers
     * @return int
     */
    public function length()
    {
        return count($this->contentManagers);
    }

    
    /**
     * Sets up the slot's contentManagers
     * 
     * @param array $alContents 
     */
    protected function setUpContentManagers(array $alContents = null)
    {
        if(null === $alContents) $alContents = AlContentQuery::create()->setContainer($this->container)->retrieveContents(array(1, $this->alLanguage->getId()), array(1, $this->alPage->getId()), $this->slot->getSlotName())->find();
        
        $this->contentManagers = array();
        foreach($alContents as $alContent)
        {
            $slotName = $alContent->getSlotName();
            $alBlockManager = AlBlockManagerFactory::createBlock($this->container, $alContent, $slotName);
            $this->contentManagers[] = $alBlockManager;
        } 
    }
    
    /**
     * Adds a new block on the managed slot
     * 
     * @param string    $type                   The content type. It must be a valid content as defined in the page_blocks parameter
     * @param int       $referenceAlContentId   The id of the reference content. When given, the content is placed below this one
     * @return boolean 
     */
    public function addBlock($type = 'Text', $referenceAlContentId = null)
    {
        try
        {
            $idPage = $this->alPage->getId();
            $idLanguage = $this->alLanguage->getId();
            switch ($this->slot->getRepeated())
            {
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
            
            // Someone is trying to copy the contents, i.e. when a new page is created, so repeated contents are skipped
            $isCopyingContent = (null !== $this->container->get('al_page_tree')->getAlPage() && $this->container->get('al_page_tree')->getAlPage()->getId() != $this->alPage->getId()) ? true : false;
            if(($idLanguage == 1 || $idPage == 1) && $isCopyingContent)
            {
                if(AlContentQuery::create()->setContainer($this->container)->retrieveContents($idLanguage, $idPage, $this->slot->getSlotName())->count() > 0) return null;
            }
            
            $rollBack = false;
            $this->connection->beginTransaction();

            $leftArray = array();
            $rightArray = array();
            $managersLength = $this->length();
            if(null !== $referenceAlContentId)
            {
                $index = $this->getContentManagerIndex($referenceAlContentId);
                if(null !== $index)
                {
                    // The new content must de added below the current one, so it must retrieve the content manager down the reference manager
                    $index += 1;              
                    if($managersLength > $index)
                    {
                        $leftArray = array_slice($this->contentManagers, 0 , $index);
                        $rightArray = array_slice($this->contentManagers, $index , $managersLength - 1);

                        $manager = $this->contentManagers[$index];
                        $position = $manager->get()->getContentPosition();
                        $rollBack = !$this->adjustPosition('add', $rightArray);
                    }
                    else
                    {
                        $position = $managersLength + 1;
                    }
                }
                else
                {
                    $position = 1;
                }
            }
            else
            {
                $position = $managersLength + 1;
            }

            if (!$rollBack)
            {
                if($this->useSlotAttributes) $type = $this->slot->getContentType();
                $alBlockManager = AlBlockManagerFactory::createBlock($this->container, $type); 
                $contentValue = array(
                  "PageId"          => $idPage,
                  "LanguageId"      => $idLanguage,
                  "SlotName"        => $this->slot->getSlotName(),
                  "ClassName"       => $type,
                  "ContentPosition" => $position
                );
                
                if($this->useSlotAttributes) $contentValue["HtmlContent"] = $this->slot->getDefaultText();
                $rollBack = !$alBlockManager->save($contentValue);
            }

            if (!$rollBack)
            {
                $this->connection->commit(); 
                
                if(!empty($leftArray) || !empty($rightArray))
                {
                    $index = $position - 1;
                    $this->contentManagers = array_merge($leftArray, array($index => $alBlockManager), $rightArray);
                }
                else 
                {
                    $this->contentManagers[] = $alBlockManager;
                }
                
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
     * Edits the block 
     * 
     * @param   int       $idContent  The id of the content to edit
     * @param   array     $values     The values to change
     * @return  boolean    
     */
    public function editBlock($idContent, array $values)
    {
        $contentManager = $this->getContentManager($idContent);
        if($contentManager != null)
        {
            return $contentManager->save($values);
        }
        
        return null;
    }
    
    /**
     * Deletes the block from the slot
     * 
     * @param   int       $idContent The id of the content to remove
     * @return  boolean 
     */
    public function deleteBlock($idContent)
    {
        $leftArray = array();
        $rightArray = array();
        $info = $this->getContentManagerAndIndex($idContent);
        if($info != null)
        {
            $index = $info['index'];
            $leftArray = array_slice($this->contentManagers, 0 , $index); 
            $rightArray = array_slice($this->contentManagers, $index + 1, $this->length() - 1);
                 
            try
            {
                $rollBack = false;
                $this->connection->beginTransaction();
            
                $this->adjustPosition('del', $rightArray);
                $res = $info['manager']->delete();
                $rollBack = !$res;
                
                if (!$rollBack)
                {
                    $this->connection->commit(); 
                }
                else
                {
                    $this->connection->rollback();
                }
                
                if($res)
                {
                    $this->contentManagers = array_merge($leftArray, $rightArray);
                }

                return $res;
            }
            catch(\Exception $e)
            {
                if(isset($this->connection) && $this->connection !== null) $this->connection->rollback();
                throw $e;
            }
        }
        
        return null;
    }
    
    /**
     * Deletes the slot's blocks from the website
     * 
     * @return  boolean 
     */
    public function deleteBlocks()
    {
        try
        {
            $rollBack = false;
            $this->connection->beginTransaction();
              
            foreach($this->contentManagers as $contentManager)
            {
                $res = $contentManager->delete();
                if(!$res)
                {
                    $rollBack = true;
                    break;
                }
            }

            if (!$rollBack)
            {
                $this->connection->commit(); 
                $this->contentManagers = array();
                
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
     * Retrieves the content manager and the index from the content identity it manages
     * 
     * @param   int     $idContent The id of the content to retrieve  
     * @return  array 
     */
    protected function getContentManagerAndIndex($idContent)
    {
        foreach($this->contentManagers as $index => $contentManager)
        {
            if($contentManager->get()->getId() == $idContent)
            {
                return array('index' => $index, 'manager' => $contentManager);
            }
        }
        
        return null;
    }
    
    /**
     * Retrieves the content manager from the content identity it manages
     * 
     * @param   int                 $idContent The id of the content to retrieve  
     * @return  AlBlockManager 
     */
    public function getContentManager($idContent)
    {
        $info = $this->getContentManagerAndIndex($idContent);
        return (null !== $info) ? $info['manager'] : null;
    }
    
    /**
     * Retrieves the content manager index from the content identity it manages
     * 
     * @param   int                 $idContent The id of the content to retrieve  
     * @return  int 
     */
    public function getContentManagerIndex($idContent)
    {
        $info = $this->getContentManagerAndIndex($idContent);
        return (null !== $info) ? $info['index'] : null;
    }
    
    /**
     * Returns the managed contents as an array
     * 
     * @return array 
     */
    public function toArray()
    {
        $result = array();
        foreach($this->contentManagers as $contentManager)
        {
            $result[] = $contentManager->toArray();
        }
        
        return $result;
    }
    
    /**
     * Adjusts the contents position on the slot, when a new content is added or a content is deleted. 
     * 
     * When add mode, it creates a space between the adding content's position and
     * the contents below, incrementing their position by one
     * 
     * When del mode, decrements by 1 the position of the contents placed below the
     * removing content
     * 
     * @param   string      $op         The operation to do. It accepts add or del as valid values
     * @param   array       $managers   An array of content managers
     * @return  boolean 
     * @throws  InvalidArgumentException    When an invalid option is given to the $op param
     * 
     */
    protected function adjustPosition($op, array $managers)
    {
        try 
        {
            // Checks the $op parameter. If doesn't match, throwns and exception
            $required = array("add", "del");
            if (!in_array($op, $required))
            {
                throw new \InvalidArgumentException(AlToolkit::translateMessage($this->container, 'The %className% adjustPosition protected method requires one of the following values: "%options%". Your input parameter is: "%parameter%"', array('%className%' => get_class($this), '%options%' => $required, '%parameter%' => $op), 'al_slot_manager_exceptions'));
            }
            
            $rollback = false;
            $this->connection->beginTransaction();
            
            foreach($managers as $contentManager)
            {
                $content = $contentManager->get();
                $position = ($op == 'add') ? $content->getContentPosition() + 1 : $content->getContentPosition() - 1;
                $content->setContentPosition($position);
                $result = $content->save(); 
                if ($content->isModified() && $result == 0)
                {
                  $rollback = true;
                  break;
                }
            }

            if (!$rollback)
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
}