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

use Symfony\Component\DependencyInjection\ContainerInterface;
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
 * AlBlockManager is the base object that defines a block content on a slot.
 * It manages an AlBlock object, implementig the base methods to add, edit and delete it
 *
 * A new block content must inherit from this class.
 * 
 * @author AlphaLemon <info@alphalemon.com>
 */
abstract class AlBlockManager extends AlContentManagerBase implements AlContentManagerInterface
{
    protected $alBlock = null;
            

    /*
     * Returns an array which may contain one or more of these keys:
     *
     *   HtmlContent            The html content displayed on the page
     *   ExternalJavascript     A comma separated external javascripts files
     *   InternalJavascript     A javascript code
     *   ExternalStylesheet     A comma separated external stylesheets files
     *   InternalStylesheet     A stylesheet code
     * 
     * @return array
     */
    abstract function getDefaultValue();
    
    /**
     * Constructor
     * 
     * @param ContainerInterface $container 
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

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
        if(null !== $propelObject && !$propelObject instanceof AlBlock)
        {
            throw new Exception\InvalidArgumentException('AlBlockManager accepts only AlBlock propel objects');
        }

        $this->alBlock = $propelObject;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $parameters)
    {
        if(null === $this->alBlock)
        {
            return $this->add($parameters);
        }
        else
        {
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
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new  Content\Block\BeforeBlockDeletingEvent($this);
                $dispatcher->dispatch(BlockEvents::BEFORE_DELETE_BLOCK, $event);
                
                if($event->isAborted())
                {
                    throw new \RuntimeException(AlToolkit::translateMessage($this->container, "The content deleting action has been aborted", array(), 'al_content_manager_exceptions'));
                }
            }
            
            $result = false;
            $rollback = false;
            $this->connection->beginTransaction();

            // Marks for deletion
            $this->alBlock->setToDelete(1);
            $this->result = $this->alBlock->save();
            if ($this->alBlock->isModified() && $this->result == 0)
            {
                $rollback = true;
            }
            
            if (!$rollback)
            {
                $this->connection->commit();

                if(null !== $dispatcher)
                {
                    $event = new  Content\Block\AfterBlockDeletedEvent($this);
                    $dispatcher->dispatch(BlockEvents::AFTER_DELETE_BLOCK, $event);
                }
                
                return true;
            }
            else
            {
                $this->connection->rollBack();
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
     * Defines when a content is rendered or not in edit mode. By default the content is rendered, to hide the content,
     * simply override this method and return true
     * 
     * @return Boolean 
     */
    public function getHideInEditMode()
    {
        return false;
    }

    /**
     * Returns the content to display when the site is browsed in CMS mode
     * 
     * @return string
     */
    public function getHtmlContentCMSMode()
    {
        return $this->getHtmlContent();
    }
    
    /**
     * Returns the current saved HtmlContent 
     * 
     * @return string
     */
    public function getHtmlContent()
    {
        return $this->alBlock->getHtmlContent();
    }

    /**
     * Returns the current saved ExternalJavascript 
     * 
     * @return array
     */
    public function getExternalJavascript()
    {
        $javascripts = trim($this->alBlock->getExternalJavascript());

        return ($javascripts != "") ? explode(',', $javascripts) : array();
    }
    
    /**
     * Returns the current saved ExternalStylesheet 
     * 
     * @return array
     */
    public function getExternalStylesheet()
    {
        $stylesheets = trim($this->alBlock->getExternalStylesheet());

        return ($stylesheets != "") ? explode(',', $stylesheets) : array();
    }

    /**
     * Returns the current saved InternalJavascript. When the values is setted, it is encapsulated in a try/catch 
     * block to avoid breaking the execution of AlphaLemon javascripts
     * 
     * @return string
     */
    public function getInternalJavascript()
    {
        $function = '';
        if(trim($this->alBlock->getInternalJavascript()) != '')
        {
            $function .= 'try{';
            $function .= $this->alBlock->getInternalJavascript();
            $function .= '}';
            $function .= 'catch(e){';
            $function .= sprintf('alert("The javascript added to the slot %s has been generated an error, which reports:\n\n" + e);', $this->alBlock->getSlotName());
            $function .= '}';
        }
        
        return $function;
    }

    /**
     * Returns the current saved InternalStylesheet 
     * 
     * @return string
     */
    public function getInternalStylesheet()
    {
        return $this->alBlock->getInternalStylesheet();
    }

    /** 
     * Converts the AlBlock object into an array
     * 
     * @return array
     */
    public function toArray()
    {
        $content = array();
        $content["Id"] = $this->alBlock->getId();
        $content["HideInEditMode"] = $this->getHideInEditMode();
        $content["HtmlContent"] = $this->getHtmlContent();
        $content["HtmlContentCMSMode"] = $this->getHtmlContentCMSMode();
        $content["ExternalJavascript"] = $this->getExternalJavascript();
        $content["InternalJavascript"] = $this->getInternalJavascript();
        $content["ExternalStylesheet"] = $this->getExternalStylesheet();
        $content["InternalStylesheet"] = $this->getInternalStylesheet();
        $content["Position"] = $this->alBlock->getContentPosition();
        $content["Type"] = $this->alBlock->getClassName();

        return $content;
    }
    

    /**
     * Adds a content to the AlBlock table
     *
     * @param array  $values     An array where keys are the AlBlockField definition and values are the values to add     *
     * @return Boolean
     */
    protected function add(array $values)
    {
        try
        {
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new  Content\Block\BeforeBlockAddingEvent($this, $values);
                $dispatcher->dispatch(BlockEvents::BEFORE_ADD_BLOCK, $event);
                
                if($event->isAborted())
                {
                    throw new \RuntimeException(AlToolkit::translateMessage($this->container, "The content adding action has been aborted", array(), 'al_content_manager_exceptions'));
                }

                if($values !== $event->getValues())
                {
                    $values = $event->getValues();
                }
            }

            $this->checkEmptyParams($values);

            $requiredParameters = array("PageId" => "", "LanguageId" => "", "SlotName" => ""); 
            $this->checkRequiredParamsExists($requiredParameters, $values);
        
            // Moves the contents placed below the adding content one position down
            $languageId = (isset($values['LanguageId'])) ? $values['LanguageId'] : $this->container->get('al_page_tree')->getAlLanguage()->getId();
            $pageId = (isset($values['PageId'])) ? $values['PageId'] : $this->container->get('al_page_tree')->getAlPage()->getId();
            $values['LanguageId'] = $languageId;
            $values['PageId'] = $pageId;
            
            // When the Content is null the dafault text is inserted
            if (!array_key_exists('HtmlContent', $values))
            { 
                $defaults = $this->getDefaultValue();
                if(!is_array($defaults))
                {
                    throw new \InvalidArgumentException(AlToolkit::translateMessage($this->container, 'The abstract method getDefaultValue() defined for the object %className% must return an array', array('%className%' => get_class($this), 'al_content_manager_exceptions')));
                }

                $availableOptions = array('HtmlContent', 'InternalJavascript', 'ExternalJavascript', 'InternalStylesheet', 'ExternalStylesheet');
                $diff = array_diff(array_keys($defaults), $availableOptions);
                if (count($diff) == count($defaults))
                {
                    throw new \InvalidArgumentException(AlToolkit::translateMessage($this->container, '%className% requires at least one of the following options: "%options%". Your input parameters are: "%parameters%"', array('%className%' => get_class($this), '%options%' => implode(', ', $availableOptions), '%parameters%' => implode(', ', array_keys($defaults))), 'al_content_manager_exceptions'));
                }

                $values = array_merge($values, $defaults);
            }
            
            $result = false;
            $rollback = false;
            $this->connection->beginTransaction();

            // Saves the content
            $alBlock = new AlBlock();
            $alBlock->fromArray($values);
            $result = $alBlock->save(); 
            if ($alBlock->isModified() && $result == 0) $rollback = true;

            if (!$rollback)
            {
                $this->connection->commit();
                $this->alBlock = $alBlock;
                
                if(null !== $dispatcher)
                {
                    $event = new  Content\Block\AfterBlockAddedEvent($this);
                    $dispatcher->dispatch(BlockEvents::AFTER_ADD_BLOCK, $event);
                }
                
                return true;
            }
            else
            {
                $this->connection->rollBack();
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
     * Edits the AlBlock object
     *
     * @param array  $values     An array where keys are the AlBlockField definition and values are the values to edit     *
     * @return Boolean
     */
    protected function edit($values)
    {
        try
        {
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new  Content\Block\BeforeBlockEditingEvent($this, $values);
                $dispatcher->dispatch(BlockEvents::BEFORE_EDIT_BLOCK, $event);
            
                if($event->isAborted())
                {
                    throw new \RuntimeException(AlToolkit::translateMessage($this->container, "The content editing action has been aborted", array(), 'al_content_manager_exceptions'));
                }

                if($values !== $event->getValues())
                {
                    $values = $event->getValues();
                }
            }
            
            $this->checkEmptyParams($values);

            $rollback = false;
            $this->connection->beginTransaction();

            // Edits the source content
            $this->alBlock->fromArray($values);
            $this->result = $this->alBlock->save();
            if ($this->alBlock->isModified() && $this->result == 0) $rollback = true;

            if (!$rollback)
            {
                $this->connection->commit();

                if(null !== $dispatcher)
                {
                    $event = new  Content\Block\AfterBlockEditedEvent($this);
                    $dispatcher->dispatch(BlockEvents::AFTER_EDIT_BLOCK, $event);
                }
                
                return true;
            }
            else
            {
                $this->connection->rollBack();
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