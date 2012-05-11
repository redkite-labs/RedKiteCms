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

use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlockQuery as BaseBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Content;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\ContentsEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 *  Adds some filters to the AlBlockQuery object
 * 
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockQuery extends BaseBlockQuery
{
    protected $dispatcher = null;
    
    /**
     * Sets the dispatcher
     * 
     * @param EventDispatcherInterface $v
     * @return AlBlockQuery 
     */
    public function setDispatcher(EventDispatcherInterface $v)
    {
        $this->dispatcher = $v;
        
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof AlBlockQuery) {
                return $criteria;
        }
        $query = new AlBlockQuery();
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
        
        if(null !== $this->dispatcher)
        {
            $event = new Content\RetrieveContentsQueringEvent($query);
            $this->dispatcher->dispatch(ContentsEvents::RETRIEVE_CONTENTS, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query;
    }
    
    public function retrieveContentsBySlotName($slotName)
    {
        $query = $this->filterBySlotName($slotName)
                  ->filterByToDelete(0);
        
        if(null !== $this->dispatcher)
        {
            $event = new Content\RetrieveContentsBySlotNameQueringEvent($query);
            $this->dispatcher->dispatch(ContentsEvents::RETRIEVE_CONTENTS_BY_SLOT_NAME, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query;
    }
    
    public function fromLanguageId($languageId)
    {
        $query = $this->filterByLanguageId($languageId)
                  ->filterByToDelete(0);
        
        if(null !== $this->dispatcher)
        {
            $event = new Content\FromLanguageIdQueringEvent($query);
            $this->dispatcher->dispatch(ContentsEvents::FROM_LANGUAGE_ID, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query;
    }
    
    public function fromPageId($pageId)
    {
        $query = $this->filterByPageId($pageId)
                      ->filterByToDelete(0);
        
        if(null !== $this->dispatcher)
        {
            $event = new Content\FromPageIdQueringEvent($query);
            $this->dispatcher->dispatch(ContentsEvents::FROM_PAGE_ID, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query;
    }
    
    public function fromPageIdAndSlotName($pageId, $slotName)
    {
        $query = $this->filterByPageId($pageId)
                      ->filterBySlotName($slotName)
                      ->filterByToDelete(0);
        
        if(null !== $this->dispatcher)
        {
            $event = new Content\FromPageIdAndSlotNameQueringEvent($query);
            $this->dispatcher->dispatch(ContentsEvents::FROM_PAGE_ID_AND_SLOT_NAME, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query;
    }    
    
    public function fromHtmlContent($search)
    {
        $query = $this->filterByHtmlContent('%' . $search . '%')
                      ->filterByToDelete(0);
        
        if(null !== $this->dispatcher)
        {
            $event = new Content\FromPageIdAndSlotNameQueringEvent($query);
            $this->dispatcher->dispatch(ContentsEvents::FROM_PAGE_ID_AND_SLOT_NAME, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query;
    }
} // AlBlockQuery
