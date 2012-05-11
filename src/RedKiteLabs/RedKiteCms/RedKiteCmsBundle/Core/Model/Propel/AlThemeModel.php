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

/**
 *  Adds some filters to the AlThemeQuery object
 * 
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlThemeModel extends Base\AlPropel
{ 
    public function fromName($themeName)
    {
        $query = AlThemeQuery::create()->filterByThemeName($themeName);
        
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

    public function activeBackend()
    {
        $query = AlThemeQuery::create()->filterByActive(1);
        
        if(null !== $this->dispatcher)
        {
            $event = new PageAttributes\FromPageAndLanguageQueringEvent($query);
            $this->dispatcher->dispatch(PageAttributesEvents::FROM_PAGE_AND_LANGUAGE, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query->findOne();
    }
}