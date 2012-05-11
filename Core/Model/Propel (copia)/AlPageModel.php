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

use AlphaLemon\AlphaLemonCmsBundle\Model\AlPageQuery as BasePageQuery;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use Symfony\Component\DependencyInjection\Exception;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Page;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\PagesEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;

/**
 *  Adds some filters to the AlPageQuery object
 * 
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlPageModel extends Base\AlPropel
{    
    
    public function setModelObject(\BaseObject $object)
    {
        if (null !== $object && !$object instanceof AlPage) {
            throw new General\InvalidParameterTypeException('AlPageModel accepts only AlPage propel objects.');
        }
        
        $this->modelObject = $object;
    }
    
    /**
     * Filters by website's pages ordered ascending 
     *
     * @return  AlPageQuery
     */
    public function activePages()
    {
        $query = $this->filterByToDelete(0)
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

        $query = $this->filterByToDelete(0)
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
        $query =  $this->filterByIsHome(1)
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
