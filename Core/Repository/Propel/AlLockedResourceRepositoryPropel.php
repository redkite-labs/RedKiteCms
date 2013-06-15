<?php
/**
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel;

use AlphaLemon\AlphaLemonCmsBundle\Model\AlLockedResource;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLockedResourceQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\LockedResourceRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;

/**
 *  Implements the UserRepositoryInterface to work with Propel
 *
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlLockedResourceRepositoryPropel extends Base\AlPropelRepository implements LockedResourceRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRepositoryObjectClassName()
    {
        return '\AlphaLemon\AlphaLemonCmsBundle\Model\AlLockedResource';
    }

    /**
     * {@inheritdoc}
     */
    public function setRepositoryObject($object = null)
    {
        if (null !== $object && !$object instanceof AlLockedResource) {
            $exception = array(
                'message' => 'AlLockedResourceRepositoryPropel accepts only AlLockedResource propel objects',
                'domain' => 'exceptions',
            );
            throw new InvalidArgumentTypeException(json_encode($exception));
        }

        return parent::setRepositoryObject($object);
    }
    
    /**
     * {@inheritdoc}
     */
    public function fromResourceName($resource)
    {
       return AlLockedResourceQuery::create()
                              ->filterByResourceName($resource)
                              ->findOne();
    }
    
    /**
     * {@inheritdoc}
     */
    public function fromResourceNameByUser($userId, $resource)
    {
        return AlLockedResourceQuery::create()
                               ->filterByUserId($userId)
                               ->filterByResourceName($resource)
                               ->findOne();
    }
    
    /**
     * {@inheritdoc}
     */
    public function freeLockedResource($resource)
    {
        return AlLockedResourceQuery::create()
                                    ->filterByResourceName($resource)
                                    ->delete();
    }
    
    /**
     * {@inheritdoc}
     */
    public function removeExpiredResources($expiredTime)
    {
        return AlLockedResourceQuery::create('a')
                                    ->where('a.UpdatedAt <= ?', $expiredTime)
                                    ->delete();
    }
    
    /**
     * {@inheritdoc}
     */
    public function freeUserResource($userId)
    {
        return AlLockedResourceQuery::create()
                                    ->filterByUserId($userId)
                                    ->delete();
    }
    
    /**
     * {@inheritdoc}
     */
    public function fetchResources($userId = null)
    {
        return AlLockedResourceQuery::create()
                                    ->_if($userId)
                                        ->filterBySlotName($userId)
                                    ->_endif()
                                    ->find();
    }
}