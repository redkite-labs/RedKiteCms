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


namespace AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Base;

use Symfony\Component\EventDispatcher\Event;

/**
 * Defines a base event raised when a filter is applied on a ModelCriteria object
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class BaseActionEvent extends Event
{
    protected $query;

    public function __construct(\ModelCriteria $query)
    {
        $this->query = $query;
    }

    public function getQuery()
    {
        return $this->query;
    }
}

