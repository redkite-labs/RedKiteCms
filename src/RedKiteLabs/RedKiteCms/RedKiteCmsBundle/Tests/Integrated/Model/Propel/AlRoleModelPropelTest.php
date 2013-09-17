<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infroleRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Integrated\Model\Propel;

/**
 * AlRoleRepositoryPropelTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlRoleRepositoryPropelTest extends Base\BaseModelPropel
{
    private $roleRepository;

    protected function setUp()
    {
        parent::setUp();

        $container = $this->client->getContainer();
        $factoryRepository = $container->get('red_kite_cms.factory_repository');
        $this->roleRepository = $factoryRepository->createRepository('Role');
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @expectedExceptionMessage exception_only_propel_role_objects_are_accepted
     */
    public function testRepositoryAcceptsOnlyAlRoleObjects()
    {
        $this->roleRepository->setRepositoryObject(new \RedKiteLabs\RedKiteCmsBundle\Model\AlPage());
    }

    public function testARoleObjectIsRetrievedFromItsPrimaryKey()
    {
        $role = $this->roleRepository->fromPk(1);
        $this->assertInstanceOf('\RedKiteLabs\RedKiteCmsBundle\Model\AlRole', $role);
        $this->assertEquals(1, $role->getId());
    }

    public function testRetrieveRoleObjectFromRoleName()
    {
        $role = $this->roleRepository->fromRoleName('ROLE_ADMIN');
        $this->assertEquals(1, count($role));
        $this->assertEquals('ROLE_ADMIN', $role->getRole());
    }

    public function testRetrieveActiveRoles()
    {
        $roles = $this->roleRepository->activeRoles();
        $this->assertEquals(3, count($roles));
    }
}