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

use AlphaLemon\ThemeEngineBundle\Model\AlThemeQuery as BaseThemeQuery;

/**
 *  Adds some filters to the AlThemeQuery object
 * 
 *  @author AlphaLemon <info@alphalemon.com>
 */
class AlThemeQuery extends BaseThemeQuery
{ 
    protected $container = null;
    
    /**
     * Sets the container
     * 
     * @param ContainerInterface $v
     * @return AlContentQuery 
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
        if ($criteria instanceof AlThemeQuery) {
                return $criteria;
        }
        $query = new AlThemeQuery();
        if (null !== $modelAlias) {
                $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
                $query->mergeWith($criteria);
        }
        return $query;
    }
    
    public function fromName($themeName)
    {
        return $this->filterByThemeName($themeName);
    }

    public function activeBackend()
    {
        return $this->filterByActive(1);
    }
}