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

use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguageQuery as BaseLanguageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Language;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\LanguagesEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *  Adds some filters to the AlLanguageQuery object
 * 
 *  @author AlphaLemon <info@alphalemon.com>
 */
class AlLanguageQuery extends BaseLanguageQuery
{
    protected $container = null;
    
    /**
     * Sets the container
     * 
     * @param ContainerInterface $v
     * @return AlBlockQuery 
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
        if ($criteria instanceof AlLanguageQuery) {
                return $criteria;
        }
        $query = new AlLanguageQuery();
        if (null !== $modelAlias) {
                $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
                $query->mergeWith($criteria);
        }
        return $query;
    }
    
    public function mainLanguage()
    {
        $query = $this->filterByMainLanguage(1)
                          ->filterByToDelete(0);
        if(!$query->findOne())
        {
            $query = $this->filterByToDelete(0)
                             ->where('id != 1');
        }
        
        if(null !== $this->container)
        {
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new Language\MainLanguageQueringEvent($query);
                $dispatcher->dispatch(LanguagesEvents::MAIN_LANGUAGE, $event);

                if($query !== $event->getQuery())
                {
                    $query = $event->getQuery();
                }
            }
        }
        
        return $query;
    }

    
    public function fromLanguageName($languageName)
    {
        if (!is_string($languageName))
        {
            throw new \InvalidArgumentException('AlLanguageQuery->fromLanguageName() accepts only strings');
        }

        $query = $this->filterByToDelete(0)
                    ->filterByLanguage($languageName);
        
        if(null !== $this->container)
        {
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new Language\FromLanguageNameQueringEvent($query);
                $dispatcher->dispatch(LanguagesEvents::FROM_LANGUAGE_NAME, $event);

                if($query !== $event->getQuery())
                {
                    $query = $event->getQuery();
                }
            }
        }
        
        return $this;
    }

    /**
    * Retrieves the active languages in the website
    *
    * @return  array of objects
    */
    public function activeLanguages()
    {
        $query = $this->filterByToDelete(0)->where('id > 1');
        
        if(null !== $this->container)
        {
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new Language\ActiveLanguagesQueringEvent($query);
                $dispatcher->dispatch(LanguagesEvents::ACTIVE_LANGUAGES, $event);

                if($query !== $event->getQuery())
                {
                    $query = $event->getQuery();
                }
            }
        }
        
        return $query;
    }
} // AlLanguageQuery
