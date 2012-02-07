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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Model;

use AlphaLemon\AlphaLemonCmsBundle\Model\AlContentQuery as BaseContentQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Content;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\ContentsEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *  Adds some filters to the AlContentQuery object
 * 
 *  @author AlphaLemon <info@alphalemon.com>
 */
class AlContentQuery extends BaseContentQuery
{
    protected $container = null;
    
    /**
     * Sets the container
     * 
     * @param ContainerInterface $v
     * @return AlContentQuery 
     */
    public function setContainer(ContainerInterface $v)
    {
        $this->container = $v;
        
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof AlContentQuery) {
                return $criteria;
        }
        $query = new AlContentQuery();
        if (null !== $modelAlias) {
                $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
                $query->mergeWith($criteria);
        }
        
        return $query;
    }
    
    public function retrieveContents($idLanguage, $idPage, $slotName = null)
    {
        $query = $this->filterByPageId($idPage)
                  ->filterByLanguageId($idLanguage)
                  ->_if($slotName)
                      ->filterBySlotName($slotName)
                  ->_endif()
                  ->filterByToDelete(0)
                  ->orderBySlotName()
                  ->orderByContentPosition();
        
        if(null !== $this->container)
        {
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new Content\RetrieveContentsQueringEvent($query);
                $dispatcher->dispatch(ContentsEvents::RETRIEVE_CONTENTS, $event);

                if($query !== $event->getQuery())
                {
                    $query = $event->getQuery();
                }
            }
        }
        
        return $query;
    }
    
    public function retrieveContentsBySlotName($slotName)
    {
        $query = $this->filterBySlotName($slotName)
                  ->filterByToDelete(0);
        
        if(null !== $this->container)
        {
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new Content\RetrieveContentsBySlotNameQueringEvent($query);
                $dispatcher->dispatch(ContentsEvents::RETRIEVE_CONTENTS_BY_SLOT_NAME, $event);

                if($query !== $event->getQuery())
                {
                    $query = $event->getQuery();
                }
            }
        }
        
        return $query;
    }
    
    public function fromLanguageId($languageId)
    {
        $query = $this->filterByLanguageId($languageId)
                  ->filterByToDelete(0);
        
        if(null !== $this->container)
        {
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new Content\FromLanguageIdQueringEvent($query);
                $dispatcher->dispatch(ContentsEvents::FROM_LANGUAGE_ID, $event);

                if($query !== $event->getQuery())
                {
                    $query = $event->getQuery();
                }
            }
        }
        
        return $query;
    }
    
    public function fromPageId($pageId)
    {
        $query = $this->filterByPageId($pageId)
                      ->filterByToDelete(0);
        
        if(null !== $this->container)
        {
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new Content\FromPageIdQueringEvent($query);
                $dispatcher->dispatch(ContentsEvents::FROM_PAGE_ID, $event);

                if($query !== $event->getQuery())
                {
                    $query = $event->getQuery();
                }
            }
        }
        
        return $query;
    }
    
    public function fromPageIdAndSlotName($pageId, $slotName)
    {
        $query = $this->filterByPageId($pageId)
                      ->filterBySlotName($slotName)
                      ->filterByToDelete(0);
        
        if(null !== $this->container)
        {
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new Content\FromPageIdAndSlotNameQueringEvent($query);
                $dispatcher->dispatch(ContentsEvents::FROM_PAGE_ID_AND_SLOT_NAME, $event);

                if($query !== $event->getQuery())
                {
                    $query = $event->getQuery();
                }
            }
        }
        
        return $query;
    }    
    
    public function fromHtmlContent($search)
    {
        $query = $this->filterByHtmlContent('%' . $search . '%')
                      ->filterByToDelete(0);
        
        if(null !== $this->container)
        {
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new Content\FromPageIdAndSlotNameQueringEvent($query);
                $dispatcher->dispatch(ContentsEvents::FROM_PAGE_ID_AND_SLOT_NAME, $event);

                if($query !== $event->getQuery())
                {
                    $query = $event->getQuery();
                }
            }
        }
        
        return $query;
    }
} // AlContentQuery
