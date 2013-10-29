<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Functional\Controller;

/**
 * SecurityControllerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class SecurityControllerTest extends BaseSecured
{
    protected function setUp()
    {
    }
    
    public function testLoginForm()
    {
        $client = $this->setUpClient(array());

        $crawler = $client->request('GET', '/backend/login'); 
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Please authenticate yourself to start with RedKite CMS")')->count() == 1);
        $this->assertTrue($crawler->filter('#username')->count() == 1);
        $this->assertTrue($crawler->filter('#password')->count() == 1);
    }

    public function test403StatusIsReturnedWhenTheRequestIsAnXMLHttpRequest()
    {
        $client = $this->setUpClient(array());

        $crawler = $client->request('POST', '/backend/login', array(), array(), array('HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'));
        $response = $client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testUserList()
    {
        $client = $this->setUpClient();

        $crawler = $client->request('POST', '/backend/users/en/al_usersList');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('.al_security_list')->count() == 1);
        $this->assertTrue($crawler->filter('.al_edit_user')->count() == 1);
        $this->assertTrue($crawler->filter('.al_delete_user')->count() == 0);
        $this->assertTrue($crawler->filter('html:contains("admin")')->count() == 1);
        $this->assertTrue($crawler->filter('#al_user_username')->count() == 1);
        $this->assertTrue($crawler->filter('#al_user_password')->count() == 1);
        $this->assertTrue($crawler->filter('#al_user_email')->count() == 1);
        $this->assertTrue($crawler->filter('#al_user_AlRole')->count() == 1);
    }

    public function testAddUserFailsBecauseUsernameIsBlank()
    {
        $client = $this->setUpClient();

        $role = $this->fetchRole($client, 'ROLE_USER');
        $params = array(
            "userId" => 0,
            "email" => "text@example.com",
            "username" => "",
            "password" => "password",
            "roleId" => $role->getId(),
        );
        $crawler = $client->request('POST', '/backend/users/en/al_saveUser', $params); 
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode()); 
        $this->assertRegExp(
            '/security_controller_values_not_valid|Some values are not valid/si',
            $crawler->text()
        );
        //$this->assertTrue($crawler->filter('html:contains("Some values are not valid")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("Username field can not be empty")')->count() == 1);
    }

    public function testAddUserFailsBecauseUsernameIsTooShort()
    {
        $client = $this->setUpClient();

        $role = $this->fetchRole($client, 'ROLE_USER');
        $params = array(
            "userId" => 0,
            "email" => "text@example.com",
            "username" => "jo",
            "password" => "password",
            "roleId" => $role->getId(),
        );
        $crawler = $client->request('POST', '/backend/users/en/al_saveUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Username field is too short. It should have 3 characters or more")')->count() == 1);
    }

    public function testAddUserFailsBecauseEmailIsBlank()
    {
        $client = $this->setUpClient();

        $role = $this->fetchRole($client, 'ROLE_USER');
        $params = array(
            "userId" => 0,
            "email" => "",
            "username" => "username",
            "password" => "password",
            "roleId" => $role->getId(),
        );
        $crawler = $client->request('POST', '/backend/users/en/al_saveUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Email field can not be empty")')->count() == 1);
    }

    public function testAddUserFailsBecauseEmailIsInvalid()
    {
        $client = $this->setUpClient();

        $role = $this->fetchRole($client, 'ROLE_USER');
        $params = array(
            "userId" => 0,
            "email" => "text@example",
            "username" => "username",
            "password" => "password",
            "roleId" => $role->getId(),
        );
        $crawler = $client->request('POST', '/backend/users/en/al_saveUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("The email you entered is not valid")')->count() == 1);
    }

    public function testAddUserFailsBecausePasswordIsBlank()
    {
        $client = $this->setUpClient();

        $role = $this->fetchRole($client, 'ROLE_USER');
        $params = array(
            "userId" => 0,
            "email" => "text@example.com",
            "username" => "username",
            "password" => "",
            "roleId" => $role->getId(),
        );
        $crawler = $client->request('POST', '/backend/users/en/al_saveUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Password field can not be empty")')->count() == 1);
    }

    public function testAddUserFailsBecausePasswordIsTooShort()
    {
        $client = $this->setUpClient();

        $role = $this->fetchRole($client, 'ROLE_USER');
        $params = array(
            "userId" => 0,
            "email" => "text@example.com",
            "username" => "username",
            "password" => "pwd",
            "roleId" => $role->getId(),
        );
        $crawler = $client->request('POST', '/backend/users/en/al_saveUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Password field is too short. It should have 6 characters or more")')->count() == 1);
    }

    public function testUserHasBeenAdded()
    {
        $client = $this->setUpClient();

        $role = $this->fetchRole($client, 'ROLE_USER');
        $params = array(
            "userId" => 0,
            "email" => "text@example.com",
            "username" => "username",
            "password" => "password",
            "roleId" => $role->getId(),
        );
        $crawler = $client->request('POST', '/backend/users/en/al_saveUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp(
            '/security_controller_user_saved|User has been saved/si',
            $response->getContent()
        );
        
        $crawler = $client->request('POST', '/backend/users/en/al_usersList');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('.al_edit_user')->count() == 2);
        $this->assertTrue($crawler->filter('.al_delete_user')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("admin")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("username")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("ROLE_USER")')->count() == 1);
    }

    public function testUserHasBeenEdited()
    {
        $client = $this->setUpClient();

        $user = $this->fetchUser($client, 'username');
        $role = $this->fetchRole($client, 'ROLE_ADMIN');
        $params = array(
            "userId" => $user->getId(),
            "email" => "edited@example.com",
            "username" => "john_doe",
            "password" => "secret",
            "roleId" => $role->getId(),
        );
        $crawler = $client->request('POST', '/backend/users/en/al_saveUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp(
            '/security_controller_user_saved|User has been saved/si',
            $response->getContent()
        );

        $crawler = $client->request('POST', '/backend/users/en/al_usersList');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('.al_edit_user')->count() == 2);
        $this->assertTrue($crawler->filter('.al_delete_user')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("admin")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("john_doe")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("ROLE_ADMIN")')->count() == 1);

        $user = $this->fetchUser($client, 'john_doe');
        $this->assertEquals(2, $user->getRoleId());
        $this->assertEquals('edited@example.com', $user->getEmail());
    }

    public function testUserHasBeenDeleted()
    {
        $client = $this->setUpClient();

        $user = $this->fetchUser($client, 'john_doe');
        $params = array(
            "id" => $user->getId(),
        );
        $crawler = $client->request('POST', '/backend/users/en/al_deleteUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $crawler = $client->request('POST', '/backend/users/en/al_usersList');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('.al_edit_user')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("admin")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("john_doe")')->count() == 0);

        $user = $this->fetchUser($client, 'john_doe');
        $this->assertNull($user);
    }

    public function testRoleList()
    {
        $client = $this->setUpClient();

        $crawler = $client->request('POST', '/backend/users/en/al_rolesList');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("ROLE_USER")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("ROLE_ADMIN")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("ROLE_SUPER_ADMIN")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("Role")')->count() == 1);
        $this->assertEquals(3, $crawler->filter('.al_edit_role')->count());
        $this->assertEquals(3, $crawler->filter('.al_delete_role')->count());
    }

    public function testAddRoleFailsBecauseRoleIsBlank()
    {
        $client = $this->setUpClient();

        $params = array(
            "id" => 0,
            "role" => "",
        );
        $crawler = $client->request('POST', '/backend/users/en/al_saveRole', $params);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Role field can not be empty")')->count() == 1);
    }

    public function testAddRoleFailsBecauseGivenRoleHasAnInvalidPrefix()
    {
        $client = $this->setUpClient();

        $params = array(
            "roleId" => 0,
            "role" => "FAKE",
        );
        $crawler = $client->request('POST', '/backend/users/en/al_saveRole', $params);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("A valid role must start with the ROLE_ prefix")')->count() == 1);
    }

    public function testRoleHasBeenAdded()
    {
        $client = $this->setUpClient();

        $params = array(
            "roleId" => 0,
            "role" => "ROLE_BOSS",
        );
        $crawler = $client->request('POST', '/backend/users/en/al_saveRole', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp(
            '/security_controller_role_saved|Role has been saved/si',
            $response->getContent()
        );

        $crawler = $client->request('POST', '/backend/users/en/al_rolesList');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("ROLE_BOSS")')->count() == 1);
    }

    public function testRoleHasBeenEdited()
    {
        $client = $this->setUpClient();

        $role = $this->fetchRole($client, 'ROLE_BOSS');
        $params = array(
            "roleId" => $role->getId(),
            "role" => "ROLE_GOD",
        );
        $crawler = $client->request('POST', '/backend/users/en/al_saveRole', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp(
            '/security_controller_role_saved|Role has been saved/si',
            $response->getContent()
        );

        $crawler = $client->request('POST', '/backend/users/en/al_rolesList');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("ROLE_GOD")')->count() == 1);
    }

    public function testRoleHasBeenDeleted()
    {
        $client = $this->setUpClient();

        $role = $this->fetchRole($client, 'ROLE_GOD');
        $params = array(
            "id" => $role->getId()
        );
        $crawler = $client->request('POST', '/backend/users/en/al_deleteRole', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $crawler = $client->request('POST', '/backend/users/en/al_rolesList');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("ROLE_GOD")')->count() == 0);
    }

    public function testUserPermissions()
    {
        $client = $this->setUpClient();

        $role = $this->fetchRole($client, 'ROLE_USER');

        $params = array(
            "id" => 0,
            "email" => "text@example.com",
            "username" => "username",
            "password" => "password",
            "roleId" => $role->getId(),
        );
        $crawler = $client->request('POST', '/backend/users/en/al_saveUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        
        $credentials = array(
                'PHP_AUTH_USER' => 'username',
                'PHP_AUTH_PW' => 'password',
            );
        $authClient = $this->setUpClient($credentials);

        $crawler = $authClient->request('GET', '/backend/en/index');
        $response = $authClient->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $crawler = $authClient->request('POST', '/backend/users/en/al_usersList');
        $response = $authClient->getResponse();
        $this->assertEquals(403, $response->getStatusCode());

        $crawler = $authClient->request('GET', '/backend/en/al_productionDeploy');
        $response = $authClient->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
    }

    private function fetchUser($client, $username)
    {
        $factoryRepository = $client->getContainer()->get('red_kite_cms.factory_repository');
        $repository = $factoryRepository->createRepository('User');

        return $repository->fromUserName($username);
    }

    private function fetchRole($client, $role)
    {
        $factoryRepository = $client->getContainer()->get('red_kite_cms.factory_repository');
        $repository = $factoryRepository->createRepository('Role');

        return $repository->fromRoleName($role);
    }
}
