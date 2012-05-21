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

use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Page;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\PagesEvents;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\PageModelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException;

/**
 *  Adds some filters to the AlPageQuery object
 * 
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlPageModelPropel extends Base\AlPropelModel implements PageModelInterface
{    
    public function getModelObjectClassName()
    {
        return '\AlphaLemon\AlphaLemonCmsBundle\Model\AlPage';
    }
    
    public function setModelObject($object = null)
    {
        if (null !== $object && !$object instanceof AlPage) {
            throw new InvalidParameterTypeException('AlPageModelPropel accepts only AlPage propel objects.');
        }
        
        return parent::setModelObject($object);
    }
    
    public function fromPK($id)
    {
        $query = AlPageQuery::create();
        
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
    
    /**
     * Filters by website's pages ordered ascending 
     *
     * @return  AlPageQuery
     */
    public function activePages()
    {
        $query = AlPageQuery::create()->filterByToDelete(0)
                      ->where('id > 1')
                      ->orderby('PageName');
        
        if (null !== $this->dispatcher) {
            $event = new Page\ActivePagesQueringEvent($query);
            $this->dispatcher->dispatch(PagesEvents::ACTIVE_PAGES, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query->find();
    }

    /**
     * Finds the AlPage object from its name
     *
     * @param   string  The name of the page
     * 
     * @return  AlPageQuery
     * @throws  InvalidArgumentException when param is null or not a string
     */
    public function fromPageName($pageName)
    {
        if (!is_string($pageName))
        {
          throw new \InvalidArgumentException('This method accepts only strings');
        }

        $query = AlPageQuery::create()->filterByToDelete(0)
                      ->filterByPageName(AlToolkit::slugify($pageName));
        
        if (null !== $this->dispatcher) {
            $event = new Page\FromPageNameQueringEvent($query);
            $this->dispatcher->dispatch(PagesEvents::FROM_PAGE_NAME, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query->findOne();
    }
    
    /**
     *
     * Finds the AlPage that represents the website's home page
     * 
     * @return AlPage 
     */
    public function homePage()
    {
        $query =  AlPageQuery::create()->filterByIsHome(1)
                      ->filterByToDelete(0); 
        
        /* TODO Review this behavior
        if(!$query)
        {
            $query = $this->filterByToDelete(0)
                         ->where('id != 1');
        }*/
        
        if (null !== $this->dispatcher) {
            $event = new Page\HomePageQueringEvent($query);
            $this->dispatcher->dispatch(PagesEvents::HOME_PAGE, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }
        
        return $query->findOne();
    }
} 
