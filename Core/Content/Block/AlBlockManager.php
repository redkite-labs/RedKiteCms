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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block; 

use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use Symfony\Component\DependencyInjection\Exception;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\BlockEvents;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\AlContentManagerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;

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
abstract class AlBlockManager extends AlContentManagerBase implements AlContentManagerInterface
{
    protected $alBlock = null;
    

    /**
     * Defines the default value of the managed block
     * 
     * 
     * Returns an array which may contain one or more of these keys:
     *
     *   - *HtmlContent*            The html content displayed on the page
     *   - *ExternalJavascript*     A comma separated external javascripts files
     *   - *InternalJavascript*     A javascript code
     *   - *ExternalStylesheet*     A comma separated external stylesheets files
     *   - *InternalStylesheet*     A stylesheet code
     * 
     * @api
     * @return array
     */
    abstract function getDefaultValue();
    
    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return $this->alBlock;
    }

    /**
     * {@inheritdoc}
     */
    public function set(\BaseObject $propelObject = null)
    {
        if (null !== $propelObject && !$propelObject instanceof AlBlock) {
            throw new Exception\InvalidArgumentException('AlBlockManager accepts only AlBlock propel objects');
        }
        
        $this->alBlock = $propelObject;
    }

    /**
     * Defines when a content is rendered or not in edit mode. 
     * 
     * 
     * By default the content is rendered when the edit mode is active. To hide the content, simply override 
     * this method and return true
     * 
     * @api
     * @return Boolean 
     */
    public function getHideInEditMode()
    {
        return false;
    }
    
    /**
     * Displays a message inside the editor to suggest a page relead
     * 
     * Return true tu display a warnig on editor that suggest the used to reload the page when the block is added or edited
     * 
     * @api
     * @return Boolean 
     */
    public function getReloadSuggested()
    {
        return false;
    }
    
    /**
     * Returns the content that must be displayed on the page
     * 
     * The content that is displayed on the page not always is the same saved in the database.
     * 
     * @api
     * @return string
     */
    public function getHtmlContent()
    {
        return $this->alBlock->getHtmlContent();
    }

    /**
     * Returns the content to display, when the site is in CMS mode
     * 
     * When the CMS mode is active, AlphaLemon CMS renders the same content displayed on the page. 
     * Override this method to change the content to display
     * 
     * @api
     * @return string
     */
    public function getHtmlContentCMSMode()
    {
        return $this->getHtmlContent();
    }
    
    /**
     * Returns the content displayed in the editor
     * 
     * The editor that manages the content gets the content saved into the database.
     * Override this method to change the content to display
     * 
     * @api
     * @return string
     */
    public function getHtmlContentForEditor()
    {
        return $this->alBlock->getHtmlContent();
    }
    
    /**
     * Returns the current saved ExternalJavascript value
     * 
     * @api
     * @return array
     */
    public function getExternalJavascript()
    {
        $javascripts = trim($this->alBlock->getExternalJavascript());

        return ($javascripts != "") ? explode(',', $javascripts) : array();
    }
    
    /**
     * Returns the current saved ExternalStylesheet value
     * 
     * @api
     * @return array
     */
    public function getExternalStylesheet()
    {
        $stylesheets = trim($this->alBlock->getExternalStylesheet());

        return ($stylesheets != "") ? explode(',', $stylesheets) : array();
    }

    /**
     * Returns the current saved InternalJavascript. 
     * 
     * When the values is setted, it is encapsulated in a try/catch 
     * block to avoid breaking the execution of AlphaLemon javascripts
     * 
     * @api
     * @return string
     */
    public function getInternalJavascript()
    {
        $function = '';
        if (trim($this->alBlock->getInternalJavascript()) != '') {
            $function .= "try{\n";
            $function .= $this->alBlock->getInternalJavascript();
            $function .= "\n} catch(e){\n";
            $function .= sprintf("alert('The javascript added to the slot %s has been generated an error, which reports:\n\n' + e);\n", $this->alBlock->getSlotName());
            $function .= "}\n";
        }
        
        return $function;
    }
    
    /**
     * Returns the current saved InternalStylesheet 
     * 
     * @api
     * @return string
     */
    public function getInternalStylesheet()
    {
        return $this->alBlock->getInternalStylesheet();
    }
    
    /**
     * Returns the current saved InternalStylesheet displayed in the editor
     * 
     * @return string
     */
    public function getInternalJavascriptForEditor()
    {
        return $this->alBlock->getInternalJavascript();
    }
    
    /**
     * {@inheritdoc}
     */
    public function save(array $parameters)
    {
        if (null === $this->alBlock || null === $this->alBlock->getId()) {
            return $this->add($parameters);
        }
        else {
            return $this->edit($parameters);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        try
        {
            if (null === $this->alBlock) {
                throw new \RuntimeException($this->translator->trans("Any valid block has been setted. Nothing to delete", array()));
            }
            
            if (null !== $this->dispatcher) {
                $event = new  Content\Block\BeforeBlockDeletingEvent($this);
                $this->dispatcher->dispatch(BlockEvents::BEFORE_DELETE_BLOCK, $event);
                
                if ($event->isAborted()) {
                    throw new \RuntimeException($this->translator->trans("The content deleting action has been aborted", array()));
                }
            }
            
            $rollback = false;
            $this->connection->beginTransaction();

            // Marks for deletion
            $this->alBlock->setToDelete(1);
            $this->result = $this->alBlock->save();
            if ($this->alBlock->isModified() && $this->result == 0) {
                $rollback = true; 
            }
            
            if (!$rollback) {
                $this->connection->commit();

                if (null !== $this->dispatcher) {
                    $event = new  Content\Block\AfterBlockDeletedEvent($this);
                    $this->dispatcher->dispatch(BlockEvents::AFTER_DELETE_BLOCK, $event);
                }
                
                return true;
            }
            else {
                $this->connection->rollBack();
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
     * Converts the AlBlockManager object into an array
     * 
     * @api
     * @return array
     */
    public function toArray()
    {
        if (null === $this->alBlock) {
            return array();
        }
            
        $blockManager = array();
        $blockManager["HideInEditMode"] = $this->getHideInEditMode();
        $blockManager["HtmlContent"] = $this->getHtmlContent();
        $blockManager["HtmlContentCMSMode"] = $this->getHtmlContentCMSMode();
        $blockManager["ExternalJavascript"] = $this->getExternalJavascript();
        $blockManager["InternalJavascript"] = $this->getInternalJavascript();
        $blockManager["ExternalStylesheet"] = $this->getExternalStylesheet();
        $blockManager["InternalStylesheet"] = $this->getInternalStylesheet();
        $blockManager["Block"] = $this->get()->toArray();

        return $blockManager;
    }
    

    /**
     * Adds a new block to the AlBlock table
     *
     * @param array  $values      An array where keys are the AlBlockField definition and values are the values to add
     * @throws \InvalidArgumentException  When the expected parameters are invalid
     * @throws \RuntimeException  When the action is aborted by a calling event 
     * @return Boolean
     */
    protected function add(array $values)
    {
        try
        {
            if (null !== $this->dispatcher) {
                $event = new Content\Block\BeforeBlockAddingEvent($this, $values);
                $this->dispatcher->dispatch(BlockEvents::BEFORE_ADD_BLOCK, $event);
               
                if ($event->isAborted()) {
                    throw new \RuntimeException($this->translator->trans("The current block adding action has been aborted", array(), 'exceptions'));
                }

                if ($values !== $event->getValues()) {
                    $values = $event->getValues();
                }
            }
            
            $this->checkEmptyParams($values);

            $requiredParameters = array("PageId" => "", "LanguageId" => "", "SlotName" => ""); 
            $this->checkRequiredParamsExists($requiredParameters, $values);
        
            /* TODO Should be safety removed?
            $languageId = (isset($values['LanguageId'])) ? $values['LanguageId'] : $this->container->get('al_page_tree')->getAlLanguage()->getId();
            $pageId = (isset($values['PageId'])) ? $values['PageId'] : $this->container->get('al_page_tree')->getAlPage()->getId();
            $values['LanguageId'] = $languageId;
            $values['PageId'] = $pageId;
            */
            
            // When the Content is null the dafault text is inserted
            if (!array_key_exists('HtmlContent', $values)) { 
                $defaults = $this->getDefaultValue();
                if (!is_array($defaults)) {
                    throw new \InvalidArgumentException($this->translator->trans('The abstract method getDefaultValue() defined for the object %className% must return an array', array('%className%' => get_class($this), 'al_content_manager_exceptions')));
                }

                $availableOptions = array('HtmlContent', 'InternalJavascript', 'ExternalJavascript', 'InternalStylesheet', 'ExternalStylesheet');
                $diff = array_diff(array_keys($defaults), $availableOptions);
                if (count($diff) == count($defaults)) {
                    throw new \InvalidArgumentException($this->translator->trans('%className% requires at least one of the following options: "%options%". Your input parameters are: "%parameters%"', array('%className%' => get_class($this), '%options%' => implode(', ', $availableOptions), '%parameters%' => implode(', ', array_keys($defaults))), 'al_content_manager_exceptions'));
                }
                
                $values = array_merge($values, $defaults);
            }
                        
            $result = false;
            $rollback = false;
            $this->connection->beginTransaction();

            // Saves the content
            if (null === $this->alBlock) {
                $this->alBlock = new AlBlock();
            }
            
            $this->alBlock->fromArray($values); 
            $result = $this->alBlock->save(); 
            if ($this->alBlock->isModified() && $result == 0) {
                $rollback = true;
            }

            if (!$rollback) {
                $this->connection->commit();
                
                if (null !== $this->dispatcher) {
                    $event = new  Content\Block\AfterBlockAddedEvent($this);
                    $this->dispatcher->dispatch(BlockEvents::AFTER_ADD_BLOCK, $event);
                }
                
                return true;
            }
            else {
                $this->connection->rollBack();
                
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
     * Edits the current block object
     *
     * @param array  $values     An array where keys are the AlBlockField definition and values are the values to edit
     * @throws \InvalidArgumentException  When the expected parameters are invalid
     * @throws \RuntimeException  When the action is aborted by a calling event 
     * @return Boolean
     */
    protected function edit(array $values)
    {
        try
        {               
            if (null !== $this->dispatcher) {
                $event = new  Content\Block\BeforeBlockEditingEvent($this, $values);
                $this->dispatcher->dispatch(BlockEvents::BEFORE_EDIT_BLOCK, $event);
            
                if ($event->isAborted()) {
                    throw new \RuntimeException($this->translator->trans("The content editing action has been aborted", array(), 'al_content_manager_exceptions'));
                }
                
                if ($values !== $event->getValues()) {
                    $values = $event->getValues(); 
                }
            }
            
            $this->checkEmptyParams($values);
            
            $rollback = false;
            $this->connection->beginTransaction();
            
            // Edits the source content
            $this->alBlock->fromArray($values);
            $this->result = $this->alBlock->save();
            if ($this->alBlock->isModified() && $this->result == 0) {  
                $rollback = true;
            }

            if (!$rollback) {
                $this->connection->commit();

                if (null !== $this->dispatcher) {
                    $event = new  Content\Block\AfterBlockEditedEvent($this);
                    $this->dispatcher->dispatch(BlockEvents::AFTER_EDIT_BLOCK, $event);
                }
                
                return true;
            }
            else {
                $this->connection->rollBack();
                
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
}