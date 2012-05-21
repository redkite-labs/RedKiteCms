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

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Seo;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\SeoEvents;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlSeoQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\SeoModelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException;

/**
 *  Adds some filters to the AlSeoQuery object
 * 
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlSeoModelPropel extends Base\AlPropelModel implements SeoModelInterface
{
    public function getModelObjectClassName()
    {
        return '\AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo';
    }
    
    public function fromPK($id)
    {
        $query = AlSeoQuery::create();
        
        if(null !== $this->dispatcher)
        {
            /* TODO
            $event = new Language\MainLanguageQueringEvent($query);
            $this->dispatcher->dispatch(LanguagesEvents::MAIN_LANGUAGE, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }*/
        }
        
        return $query->findPk($id);
    }
    
    public function setModelObject($object = null)
    {
        if (null !== $object && !$object instanceof AlSeo) {
            throw new InvalidParameterTypeException('AlSeoModel accepts only AlSeo propel objects.');
        }
        
        return parent::setModelObject($object);
    }
    
    public function fromPageAndLanguage($languageId, $pageId)
    {
        $query = AlSeoQuery::create()->filterByPageId($pageId)
                      ->filterByLanguageId($languageId)
                      ->filterByToDelete(0);
        
        if(null !== $this->dispatcher)
        {
            $event = new Seo\FromPageAndLanguageQueringEvent($query);
            $this->dispatcher->dispatch(SeoEvents::FROM_PAGE_AND_LANGUAGE, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query->findOne();
    }

    public function fromPermalink($permalink, $languageId)
    {
        $query = AlSeoQuery::create()->filterByPermalink($permalink)
                      ->filterByLanguageId($languageId)
                      ->filterByToDelete(0);
        
        if(null !== $this->dispatcher)
        {
            $event = new Seo\FromPermalinkQueringEvent($query);
            $this->dispatcher->dispatch(SeoEvents::FROM_PERMALINK, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query->findOne();
    }
    
    public function fromPageId($pageId)
    {
        $query = AlSeoQuery::create()->filterByPageId($pageId)
                      ->filterByToDelete(0);
        
        if(null !== $this->dispatcher)
        {
            $event = new Seo\FromPageIdQueringEvent($query);
            $this->dispatcher->dispatch(SeoEvents::FROM_PAGE_ID, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $this->find();
    }

    public function fromLanguageId($languageId)
    {
        $query = AlSeoQuery::create()->filterByLanguageId($languageId)
                      ->filterByToDelete(0);
        
        if(null !== $this->dispatcher)
        {
            $event = new Seo\FromLanguageIdQueringEvent($query);
            $this->dispatcher->dispatch(SeoEvents::FROM_LANGUAGE_ID, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query->find();
    }
    
    public function fromPageIdWithLanguages($pageId)
    {
        $query = AlSeoQuery::create()
                            ->joinAlLanguage()
                            ->filterByPageId($pageId)
                            ->filterByToDelete(0)
                            ->orderByLanguageId();
        if(null !== $this->dispatcher)
        {
            $event = new Seo\FromPageIdWithLanguagesQueringEvent($query);
            $this->dispatcher->dispatch(SeoEvents::FROM_PAGE_ID_WITH_LANGUAGES, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query->find();
    }
} 
