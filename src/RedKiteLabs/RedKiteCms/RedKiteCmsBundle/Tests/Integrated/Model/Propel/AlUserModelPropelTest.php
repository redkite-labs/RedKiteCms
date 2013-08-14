<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infuserRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Integrated\Model\Propel;

/**
 * AlUserRepositoryPropelTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlUserRepositoryPropelTest extends Base\BaseModelPropel
{
    private $userRepository;

    protected function setUp()
    {
        parent::setUp();

        $container = $this->client->getContainer();
        $factoryRepository = $container->get('alpha_lemon_cms.factory_repository');
        $this->userRepository = $factoryRepository->createRepository('User');
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @expectedExceptionMessage AlUserRepositoryPropel accepts only AlUser propel objects
     */
    public function testRepositoryAcceptsOnlyAlUserObjects()
    {
        $this->userRepository->setRepositoryObject(new \RedKiteLabs\RedKiteCmsBundle\Model\AlPage());
    }

    public function testAUserObjectIsRetrievedFromItsPrimaryKey()
    {
        $user = $this->userRepository->fromPk(1);
        $this->assertInstanceOf('\RedKiteLabs\RedKiteCmsBundle\Model\AlUser', $user);
        $this->assertEquals(1, $user->getId());
    }

    public function testRetrieveUserObjectFromUserName()
    {
        $user = $this->userRepository->fromUserName('admin');
        $this->assertEquals(1, count($user));
        $this->assertEquals('admin', $user->getUserName());
    }

    public function testRetrieveActiveUsers()
    {
        $users = $this->userRepository->activeUsers();
        $this->assertEquals(1, count($users));
    }
}