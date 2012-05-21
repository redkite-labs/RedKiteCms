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

use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Language;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\LanguagesEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\LanguageModelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException;

/**
 *  Adds some filters to the AlLanguageQuery object
 * 
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlLanguageModelPropel extends Base\AlPropelModel implements LanguageModelInterface
{
    public function getModelObjectClassName()
    {
        return '\AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage';
    }
    
    public function setModelObject($object = null)
    {
        if (null !== $object && !$object instanceof AlLanguage) {
            throw new InvalidParameterTypeException('AlLanguageModelPropel accepts only AlLanguage propel objects');
        }
        
        return parent::setModelObject($object);
    }
    
    public function fromPK($id)
    {
        $query = AlLanguageQuery::create();
        
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
    
    public function mainLanguage()
    {
        $query = AlLanguageQuery::create()->filterByMainLanguage(1)
                          ->filterByToDelete(0);
        
        // TODO Check out this behavior
        /*if(!$query->findOne())
        {
            $query = $this->filterByToDelete(0)
                             ->where('id != 1');
        }*/
        
        if(null !== $this->dispatcher)
        {
            $event = new Language\MainLanguageQueringEvent($query);
            $this->dispatcher->dispatch(LanguagesEvents::MAIN_LANGUAGE, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query->findOne();
    }

    public function fromLanguageName($languageName)
    {
        if (!is_string($languageName))
        {
            throw new \InvalidArgumentException('AlLanguageQuery->fromLanguageName() accepts only strings');
        }

        $query = AlLanguageQuery::create()->filterByToDelete(0)
                    ->filterByLanguage($languageName);
        
        if(null !== $this->dispatcher)
        {
            $event = new Language\FromLanguageNameQueringEvent($query);
            $this->dispatcher->dispatch(LanguagesEvents::FROM_LANGUAGE_NAME, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query->findOne();
    }

    /**
    * Retrieves the active languages in the website
    *
    * @return  array of objects
    */
    public function activeLanguages()
    {
        $query = AlLanguageQuery::create()->filterByToDelete(0)->where('id > 1');
        
        if(null !== $this->dispatcher)
        {
            $event = new Language\ActiveLanguagesQueringEvent($query);
            $this->dispatcher->dispatch(LanguagesEvents::ACTIVE_LANGUAGES, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query->find();
    }
}
