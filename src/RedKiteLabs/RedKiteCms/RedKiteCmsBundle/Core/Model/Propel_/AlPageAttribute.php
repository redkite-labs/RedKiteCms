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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel;

use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\PageAttributes;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\PageAttributesEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 *  Adds some filters to the AlPageAttributeQuery object
 * 
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlPageAttributeQuery extends Base\AlPropel
{
    public function fromPageAndLanguage($pageId, $languageId)
    {
        $query = AlPageAttributeQuery::create()->filterByPageId($pageId)
                      ->filterByLanguageId($languageId)
                      ->filterByToDelete(0);
        
        if(null !== $this->dispatcher)
        {
            $event = new PageAttributes\FromPageAndLanguageQueringEvent($query);
            $this->dispatcher->dispatch(PageAttributesEvents::FROM_PAGE_AND_LANGUAGE, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query;
    }

    public function fromPermalink($permalink, $languageId)
    {
        $query = $this->filterByPermalink($permalink)
                      ->filterByLanguageId($languageId)
                      ->filterByToDelete(0);
        
        if(null !== $this->dispatcher)
        {
            $event = new PageAttributes\FromPermalinkQueringEvent($query);
            $this->dispatcher->dispatch(PageAttributesEvents::FROM_PERMALINK, $event);

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
            $event = new PageAttributes\FromPageIdQueringEvent($query);
            $this->dispatcher->dispatch(PageAttributesEvents::FROM_PAGE_ID, $event);

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
            $event = new PageAttributes\FromLanguageIdQueringEvent($query);
            $this->dispatcher->dispatch(PageAttributesEvents::FROM_LANGUAGE_ID, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
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
        if(null !== $this->dispatcher)
        {
            $event = new PageAttributes\FromPageIdWithLanguagesQueringEvent($query);
            $this->dispatcher->dispatch(PageAttributesEvents::FROM_PAGE_ID_WITH_LANGUAGES, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query;
    }
} // AlPageAttributeQuery
