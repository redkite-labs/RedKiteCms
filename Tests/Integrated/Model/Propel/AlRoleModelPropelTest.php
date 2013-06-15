<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infroleRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Integrated\Model\Propel;

/**
 * AlRoleRepositoryPropelTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlRoleRepositoryPropelTest extends Base\BaseModelPropel
{
    private $roleRepository;

    protected function setUp()
    {
        parent::setUp();

        $container = $this->client->getContainer();
        $factoryRepository = $container->get('alpha_lemon_cms.factory_repository');
        $this->roleRepository = $factoryRepository->createRepository('Role');
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @expectedExceptionMessage AlRoleRepositoryPropel accepts only AlRole propel objects
     */
    public function testRepositoryAcceptsOnlyAlRoleObjects()
    {
        $this->roleRepository->setRepositoryObject(new \AlphaLemon\AlphaLemonCmsBundle\Model\AlPage());
    }

    public function testARoleObjectIsRetrievedFromItsPrimaryKey()
    {
        $role = $this->roleRepository->fromPk(1);
        $this->assertInstanceOf('\AlphaLemon\AlphaLemonCmsBundle\Model\AlRole', $role);
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