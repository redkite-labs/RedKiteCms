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

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlRole;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlRoleQuery;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\RoleRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;

/**
 *  Implements the UserRepositoryInterface to work with Propel
 *
 *  @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlRoleRepositoryPropel extends Base\AlPropelRepository implements RoleRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRepositoryObjectClassName()
    {
        return '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlRole';
    }

    /**
     * {@inheritdoc}
     */
    public function setRepositoryObject($object = null)
    {
        if (null !== $object && !$object instanceof AlRole) {
            throw new InvalidArgumentTypeException('exception_only_propel_role_objects_are_accepted');
        }

        return parent::setRepositoryObject($object);
    }

    /**
     * {@inheritdoc}
     */
    public function fromPK($id)
    {
        return AlRoleQuery::create()
                          ->findPk($id);
    }

    /**
     * {@inheritdoc}
     */
    public function fromRoleName($roleName)
    {
        return AlRoleQuery::create()
                          ->filterByRole($roleName)
                          ->findOne();
    }

    /**
     * {@inheritdoc}
     */
    public function activeRoles()
    {
        return AlRoleQuery::create()
                          ->find();
    }
}
