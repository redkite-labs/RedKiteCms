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

use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\ResourcesLocker\Exception\ResourceNotFreeException;

/**
 * 
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlResourcesLocker
{
    private $lockedResourceRepository;
    private $expiringTime;
    
    /**
     * Constructor
     *
     * @param AlFactoryRepositoryInterface $factoryRepository
     */
    public function __construct(AlFactoryRepositoryInterface $factoryRepository, $expiringTime = 300)
    {
        $this->factoryRepository = $factoryRepository;
        $this->lockedResourceRepository = $this->factoryRepository->createRepository('LockedResource');
        $this->expiringTime = $expiringTime;
    }
    
    public function lockResource($userId, $resourceName)
    {
        $resource = $this->lockedResourceRepository->fromResourceNameByUser($userId, $resourceName);
        if (null === $resource) {
            if ( ! $this->isResourceFree($resourceName)) {
                throw new ResourceNotFreeException('The resource you requested is locked by another user. Please retry in a couple of minutes');
            }
            
            $resourceClass = $this->lockedResourceRepository->getRepositoryObjectClassName();
            $resource = new $resourceClass();
            $values = array(
                'ResourceName' => $resourceName,
                'UserId' => $userId,
                'CreatedAt' => time(),
                'UpdatedAt' => time(),
            );
        }
        else {
            $values = array(
                'UpdatedAt' => time(),
            );
        }
            
        $this->lockedResourceRepository
             ->setRepositoryObject($resource)
             ->save($values);
    }
    
    public function unlockUserResource($userId)
    {
        $this->lockedResourceRepository->freeUserResource($userId);
    }
    
    public function freeResource($resourceName)
    {
        $this->lockedResourceRepository->freeLockedResource($resourceName);
    }
    
    public function freeExpiredResources()
    {
        $expiredTime = time() - $this->expiringTime;
        
        $this->lockedResourceRepository->removeExpiredResources($expiredTime);
    }
    
    protected function isResourceFree($resourceName)
    {
        return (null === $this->lockedResourceRepository->fromResourceName($resourceName, true)) ? true : false;
    }
}