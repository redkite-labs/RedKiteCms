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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\Base;

use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlockQuery as BaseBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Content;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\ContentsEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 *  Adds some filters to the AlBlockQuery object
 * 
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlPropel
{
    protected $dispatcher = null;
    
    /**
     * Constructor
     * 
     * @param EventDispatcherInterface $v 
     */
    public function _constructor(EventDispatcherInterface $v)
    {
        $this->dispatcher = $v;
    }
    
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
} // AlBlockQuery
