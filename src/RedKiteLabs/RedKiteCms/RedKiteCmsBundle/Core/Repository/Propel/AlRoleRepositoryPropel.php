<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel;

use RedKiteLabs\RedKiteCmsBundle\Model\AlRole;
use RedKiteLabs\RedKiteCmsBundle\Model\AlRoleQuery;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\RoleRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;

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
        return '\RedKiteLabs\RedKiteCmsBundle\Model\AlRole';
    }

    /**
     * {@inheritdoc}
     */
    public function setRepositoryObject($object = null)
    {
        if (null !== $object && !$object instanceof AlRole) {
            throw new InvalidArgumentTypeException('AlRoleRepositoryPropel accepts only AlRole propel objects');
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
