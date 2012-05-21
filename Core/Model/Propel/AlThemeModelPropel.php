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
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\ThemeModelInterface;
use AlphaLemon\ThemeEngineBundle\Core\Model\AlThemeQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException;

/*
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\AlSeo;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Seo;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo;*/

/**
 *  Adds some filters to the AlThemeQuery object
 * 
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlThemeModelPropel extends Base\AlPropelModel implements ThemeModelInterface
{ 
    public function getModelObjectClassName()
    {
        return '\AlphaLemon\AlphaLemonCmsBundle\Model\AlTheme';
    }
    
    public function setModelObject($object = null)
    {
        if (null !== $object && !$object instanceof AlTheme) {
            throw new InvalidParameterTypeException('AlThemeModelPropel accepts only AlTheme propel objects');
        }
        
        return parent::setModelObject($object);
    }
    
    public function fromName($themeName)
    {
        $query = AlThemeQuery::create()->filterByThemeName($themeName);
        
        /* TODO
        if(null !== $this->dispatcher)
        {
            $event = new PageAttributes\FromPageAndLanguageQueringEvent($query);
            $this->dispatcher->dispatch(PageAttributesEvents::FROM_PAGE_AND_LANGUAGE, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }*/
        
        return $query;
    }

    public function activeBackend()
    {
        $query = AlThemeQuery::create()->filterByActive(1);
        
        /* TODO
        if(null !== $this->dispatcher)
        {
            $event = new PageAttributes\FromPageAndLanguageQueringEvent($query);
            $this->dispatcher->dispatch(PageAttributesEvents::FROM_PAGE_AND_LANGUAGE, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }*/
        
        return $query->findOne();
    }
}