<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infuserRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Integrated\Model\Propel;

/**
 * UserRepositoryPropelTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class UserRepositoryPropelTest extends Base\BaseModelPropel
{
    private $userRepository;

    protected function setUp()
    {
        parent::setUp();

        $container = $this->client->getContainer();
        $factoryRepository = $container->get('red_kite_cms.factory_repository');
        $this->userRepository = $factoryRepository->createRepository('User');
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @expectedExceptionMessage exception_only_propel_user_objects_are_accepted
     */
    public function testRepositoryAcceptsOnlyUserObjects()
    {
        $this->userRepository->setRepositoryObject(new \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Page());
    }

    public function testAUserObjectIsRetrievedFromItsPrimaryKey()
    {
        $user = $this->userRepository->fromPk(1);
        $this->assertInstanceOf('\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\User', $user);
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
    
    public function testRetrieveUsersByRole()
    {
        $users = $this->userRepository->usersByRole(1);
        $this->assertEquals(0, count($users));
        
        $users = $this->userRepository->usersByRole(2);
        $this->assertEquals(1, count($users));
        
        $users = $this->userRepository->usersByRole(3);
        $this->assertEquals(0, count($users));
    }
    
    
}