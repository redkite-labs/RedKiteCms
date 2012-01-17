<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * (c) Since 2011 AlphaLemon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 */

namespace AlphaLemon\ThemeEngineBundle\Core\Model;

use AlphaLemon\ThemeEngineBundle\Model\AlThemeQuery as BaseThemeQuery;

class AlThemeQuery extends BaseThemeQuery
{ 
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