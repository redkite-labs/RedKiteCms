<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository;

/**
 * Defines the methods used to fetch locked resources records
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface LockedResourceRepositoryInterface
{
    /**
     * Fetches a resource  record using its primary key
     *
     * @param int       The primary key
     * @return object The fetched object
     */
    public function fromResourceName($resource);    
    
    public function fromResourceNameByUser($userId, $resource);    
    
    public function freeUserResource($userId);
    
    public function freeLockedResource($resource);    
    
    public function removeExpiredResources($expiredTime);  
}
