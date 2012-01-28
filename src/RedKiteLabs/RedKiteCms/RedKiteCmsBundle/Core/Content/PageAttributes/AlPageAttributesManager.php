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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageAttributes;

use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlLanguageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageAttributeQuery;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPageAttribute;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactory;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\PageAttributesEvents;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\AlContentManagerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase;

/**
 * Defines the page attributes content manager object, that implements the methods to manage an AlPageAttribute object
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class AlPageAttributesManager extends AlContentManagerBase implements AlContentManagerInterface
{
    protected $pageAttributes = null;

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return $this->pageAttributes;
    }

    /**
     * {@inheritdoc}
     */
    public function set(\BaseObject $propelObject = null)
    {
        if(null !== $propelObject && !$propelObject instanceof AlPageAttribute)
        {
            throw new InvalidArgumentException('AlPageAttributesManager accepts only AlPageAttribute propel objects.');
        }

        $this->pageAttributes = $propelObject;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $values)
    {
        if(null === $this->pageAttributes)
        {
            return $this->add($values);
        }
        else
        {
            return $this->edit($values);
        }
    }
  
    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        if ($this->pageAttributes)
        {
            try
            {
                $dispatcher = $this->container->get('event_dispatcher');
                if(null !== $dispatcher)
                {
                    $event = new  Content\PageAttributes\BeforePageAttributesDeletingEvent($this);
                    $dispatcher->dispatch(PageAttributesEvents::BEFORE_DELETE_PAGE_ATTRIBUTES, $event);

                    if($event->isAborted())
                    {
                        throw new \RuntimeException(AlToolkit::translateMessage($this->container, "The page attributes deleting action has been aborted", array(), 'al_page_attributes_manager_exceptions'));
                    }
                }
                    
                $rollBack = false;
                $this->connection->beginTransaction();

                $this->pageAttributes->setToDelete(1);
                $result = $this->pageAttributes->save();
                if ($this->pageAttributes->isModified() && $result == 0)
                {
                    $rollBack = true;
                }
                
                if (!$rollBack)
                {
                    $this->connection->commit();
                    
                    if(null !== $dispatcher)
                    {
                        $event = new  Content\PageAttributes\AfterPageAttributesDeletedEvent($this);
                        $dispatcher->dispatch(PageAttributesEvents::AFTER_DELETE_PAGE_ATTRIBUTES, $event);
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
        else
        {
            throw new \RuntimeException($this->container->get('translator')->trans('The page attribute is null'));
        }
    }
    
    /**
     * Adds a new AlPageAttributes object from the given params
     * 
     * @param array $values
     * @return Boolean 
     */
    protected function add(array $values)
    {
        try
        {
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new  Content\PageAttributes\BeforePageAttributesAddingEvent($this, $values);
                $dispatcher->dispatch(PageAttributesEvents::BEFORE_ADD_PAGE_ATTRIBUTES, $event);

                if($event->isAborted())
                {
                    throw new \RuntimeException(AlToolkit::translateMessage($this->container, "The page attributes adding action has been aborted", array(), 'al_page_attributes_manager_exceptions'));
                }

                if($values !== $event->getValues())
                {
                    $values = $event->getValues();
                }
            }
            
            if(empty($values))
            {
                throw new \InvalidArgumentException(AlToolkit::translateMessage($this->container, 'The page cannot be added because any parameter has been given'));
            }

            $this->checkRequiredParamsExists(array('idPage' => '', 'idLanguage' => '', 'permalink' => '', 'title' => '', 'description' => '', 'keywords' => ''), $values);
        
            if (empty($values['idPage']))
            {
                throw new \InvalidArgumentException(AlToolkit::translateMessage($this->container, "The idPage parameter is mandatory to save a page attribute object"));
            }

            if (empty($values['idLanguage']))
            {
                throw new \InvalidArgumentException(AlToolkit::translateMessage($this->container, "The idLanguage parameter is mandatory to save a page attribute object"));
            }
            
            if (empty($values['permalink']))
            {
                throw new \InvalidArgumentException(AlToolkit::translateMessage($this->container, "The permalink parameter is mandatory to save a page attribute object"));
            }
        
            $rollBack = false;
            $this->connection->beginTransaction();
            $this->pageAttributes = new AlPageAttribute();
            $this->pageAttributes->setPageId($values["idPage"]);
            $this->pageAttributes->setLanguageId($values["idLanguage"]);
            $this->pageAttributes->setPermalink(AlToolkit::slugify($values["permalink"]));
            $this->pageAttributes->setMetaTitle($values["title"]);
            $this->pageAttributes->setMetaDescription($values["description"]);
            $this->pageAttributes->setMetaKeywords($values["keywords"]);
            $res = $this->pageAttributes->save();
            if ($this->pageAttributes->isModified() && $res == 0)
            {
                $rollBack = true;
            }
            
            if (!$rollBack)
            {
                $this->connection->commit();
              
                if(null !== $dispatcher)
                {
                    $event = new  Content\PageAttributes\AfterPageAttributesAddedEvent($this);
                    $dispatcher->dispatch(PageAttributesEvents::AFTER_ADD_PAGE_ATTRIBUTES, $event);
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
            throw $e;
        }
    }
    
    /**
     * Edits the managed page attributes object
     * 
     * @param array $values
     * @return type 
     */
    protected function edit(array $values = array())
    {
        try
        {
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new  Content\PageAttributes\BeforePageAttributesEditingEvent($this, $values);
                $dispatcher->dispatch(PageAttributesEvents::BEFORE_EDIT_PAGE_ATTRIBUTES, $event);

                if($event->isAborted())
                {
                    throw new \RuntimeException(AlToolkit::translateMessage($this->container, "The page attributes editing action has been aborted", array(), 'al_page_attributes_manager_exceptions'));
                }

                if($values !== $event->getValues())
                {
                    $values = $event->getValues();
                }
            }
            
            if(empty($values))
            {
                throw new \InvalidArgumentException(AlToolkit::translateMessage($this->container, 'Any value has been given: nothing to update'));
            }
            
            if(0 === count(array_intersect_key(array('permalink' => '', 'title' => '', 'description' => '', 'keywords' => ''), $values)))
            {
                return null;
            }   
        
            $rollBack = false;
            $this->connection->beginTransaction();
            
            if(isset($values['permalink'])) $this->pageAttributes->setPermalink(AlToolkit::slugify($values["permalink"]));
            if(isset($values['title'])) $this->pageAttributes->setMetaTitle($values["title"]);
            if(isset($values['description'])) $this->pageAttributes->setMetaDescription($values["description"]);
            if(isset($values['keywords'])) $this->pageAttributes->setMetaKeywords($values["keywords"]);
            $res = $this->pageAttributes->save();
            if ($this->pageAttributes->isModified() && $res == 0)
            {
                $rollBack = true;
            }

            if (!$rollBack)
            {
                $this->connection->commit();
              
                if(null !== $dispatcher)
                {
                    $event = new  Content\PageAttributes\AfterPageAttributesEditedEvent($this);
                    $dispatcher->dispatch(PageAttributesEvents::AFTER_EDIT_PAGE_ATTRIBUTES, $event);
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
            throw $e;
        }
    }
}
