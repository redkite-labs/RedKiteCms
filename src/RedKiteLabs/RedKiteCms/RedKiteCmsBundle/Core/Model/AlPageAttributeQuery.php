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

use AlphaLemon\AlphaLemonCmsBundle\Model\AlPageAttributeQuery as BasePageAttributeQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\PageAttributes;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\PageAttributesEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *  Adds some filters to the AlPageAttributeQuery object
 * 
 *  @author AlphaLemon <info@alphalemon.com>
 */
class AlPageAttributeQuery extends BasePageAttributeQuery
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
        if ($criteria instanceof AlPageAttributeQuery) {
                return $criteria;
        }
        $query = new AlPageAttributeQuery();
        if (null !== $modelAlias) {
                $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
                $query->mergeWith($criteria);
        }
        return $query;
    }
    
    public function fromPageAndLanguage($pageId, $languageId)
    {
        $query = $this->filterByPageId($pageId)
                      ->filterByLanguageId($languageId)
                      ->filterByToDelete(0);
        
        if(null !== $this->container)
        {
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new PageAttributes\FromPageAndLanguageQueringEvent($query);
                $dispatcher->dispatch(PageAttributesEvents::FROM_PAGE_AND_LANGUAGE, $event);

                if($query !== $event->getQuery())
                {
                    $query = $event->getQuery();
                }
            }
        }
        
        return $query;
    }

    public function fromPermalink($permalink, $languageId)
    {
        $query = $this->filterByPermalink($permalink)
                      ->filterByLanguageId($languageId)
                      ->filterByToDelete(0);
        
        if(null !== $this->container)
        {
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new PageAttributes\FromPermalinkQueringEvent($query);
                $dispatcher->dispatch(PageAttributesEvents::FROM_PERMALINK, $event);

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
                $event = new PageAttributes\FromPageIdQueringEvent($query);
                $dispatcher->dispatch(PageAttributesEvents::FROM_PAGE_ID, $event);

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
                $event = new PageAttributes\FromLanguageIdQueringEvent($query);
                $dispatcher->dispatch(PageAttributesEvents::FROM_LANGUAGE_ID, $event);

                if($query !== $event->getQuery())
                {
                    $query = $event->getQuery();
                }
            }
        }
        
        return $query;
    }
    
    public function fromPageIdWithLanguages($pageId)
    {
        $query = AlPageAttributeQuery::create()
                            ->joinAlLanguage()
                            ->filterByPageId($pageId)
                            ->filterByToDelete(0)
                            ->orderByLanguageId();
        if(null !== $this->container)
        {
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new PageAttributes\FromPageIdWithLanguagesQueringEvent($query);
                $dispatcher->dispatch(PageAttributesEvents::FROM_PAGE_ID_WITH_LANGUAGES, $event);

                if($query !== $event->getQuery())
                {
                    $query = $event->getQuery();
                }
            }
        }
        
        return $query;
    }

    // DA RIMUOVERE
    public function retrievePermalinksFromLanguage($languageId)
    {
        $permalinks = array();
        $alPagesAttribute = self::create()->fromLanguageId($languageId)->find();
        foreach($alPagesAttribute as $alPageAttribute)
        {
            $permalinks[$alPageAttribute->getPageId()] = $alPageAttribute->getPermalink();
        }

        return $permalinks;
    }
} // AlPageAttributeQuery
