<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\User;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\UserQuery;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\UserRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;

/**
 *  Implements the UserRepositoryInterface to work with Propel
 *
 *  @author RedKite Labs <webmaster@redkite-labs.com>
 */
class UserRepositoryPropel extends Base\PropelRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRepositoryObjectClassName()
    {
        return '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\User';
    }

    /**
     * {@inheritdoc}
     */
    public function setRepositoryObject($object = null)
    {
        if (null !== $object && !$object instanceof User) {
            throw new InvalidArgumentTypeException('exception_only_propel_user_objects_are_accepted');
        }

        return parent::setRepositoryObject($object);
    }

    /**
     * {@inheritdoc}
     */
    public function fromPK($id)
    {
        return UserQuery::create()
                          ->findPk($id);
    }

    /**
     * {@inheritdoc}
     */
    public function fromUserName($userName)
    {
        return UserQuery::create()
                          ->filterByUserName($userName)
                          ->findOne();
    }

    /**
     * {@inheritdoc}
     */
    public function activeUsers()
    {
        return UserQuery::create('a')
                          ->joinWith('a.Role')
                          ->orderBy('Role.Role')
                          ->find();
    }

    /**
     * {@inheritdoc}
     */
    public function usersByRole($roleId)
    {
        return UserQuery::create()
                          ->filterByRoleId($roleId)
                          ->find();
    }
}
