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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\ResourcesLocker;

/**
 * 
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlResourcesLocker
{
    private $lockedResourceRepository;
    
    /**
     * Constructor
     *
     * @param AlFactoryRepositoryInterface $factoryRepository
     */
    public function __construct(AlFactoryRepositoryInterface $factoryRepository)
    {
        $this->factoryRepository = $factoryRepository;
        $this->lockedResourceRepository = $this->factoryRepository->createRepository('LockedResource');
    }
    
    public function addResource($resource, $userId)
    {
        if ( ! $this->isResourceFree($resource)) {
            throw new \InvalidArgumentException();
        }
        
        $values = array(
            'ResourceName' => $resource,
            'UserId' => $userId,
            'UpdatedAt' => $resource,
        );
        //$this->lockedResourceRepository->
    }
    
    protected function isResourceFree($resource)
    {
        return (0 === $this->lockedResourceRepository->fromResourceName($resource, true)) ? true : false;
    }
}