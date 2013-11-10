<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel;

use RedKiteLabs\RedKiteCmsBundle\Model\AlUser;
use RedKiteLabs\RedKiteCmsBundle\Model\AlUserQuery;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\UserRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;

/**
 *  Implements the UserRepositoryInterface to work with Propel
 *
 *  @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlUserRepositoryPropel extends Base\AlPropelRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRepositoryObjectClassName()
    {
        return '\RedKiteLabs\RedKiteCmsBundle\Model\AlUser';
    }

    /**
     * {@inheritdoc}
     */
    public function setRepositoryObject($object = null)
    {
        if (null !== $object && !$object instanceof AlUser) {
            throw new InvalidArgumentTypeException('exception_only_propel_user_objects_are_accepted');
        }

        return parent::setRepositoryObject($object);
    }

    /**
     * {@inheritdoc}
     */
    public function fromPK($id)
    {
        return AlUserQuery::create()
                          ->findPk($id);
    }

    /**
     * {@inheritdoc}
     */
    public function fromUserName($userName)
    {
        return AlUserQuery::create()
                          ->filterByUserName($userName)
                          ->findOne();
    }

    /**
     * {@inheritdoc}
     */
    public function activeUsers()
    {
        return AlUserQuery::create('a')
                          ->joinWith('a.AlRole')
                          ->orderBy('AlRole.Role')
                          ->find();
    }
    
    /**
     * {@inheritdoc}
     */
    public function usersByRole($roleId)
    {
        return AlUserQuery::create()
                          ->filterByRoleId($roleId)
                          ->find();
    }
}
