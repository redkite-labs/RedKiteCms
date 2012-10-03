<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Controller;

use AlphaLemon\AlphaLemonCmsBundle\Tests\WebTestCaseFunctional;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlSeoRepositoryPropel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel;

/**
 * SecurityControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class SecurityControllerTest extends WebTestCaseFunctional
{
    private $pageRepository;
    private $seoRepository;
    private $blockRepository;

    protected function setUp()
    {
    }

    public function testUserList()
    {
        $client = $this->setUpClient();

        $crawler = $client->request('GET', '/backend/users/en/al_userList');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('.al_items_list')->count() == 1);
        $this->assertTrue($crawler->filter('.al_edit_user')->count() == 1);
        $this->assertTrue($crawler->filter('.al_delete_user')->count() == 0);
        $this->assertTrue($crawler->filter('html:contains("admin")')->count() == 1);
    }

    public function testShowAddUserForm()
    {
        $client = $this->setUpClient();

        $crawler = $client->request('GET', '/backend/users/en/al_showUser');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Username")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("Password")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("Email")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("Role")')->count() == 1);
        $this->assertTrue($crawler->filter('input')->count() == 6);
        $this->assertTrue($crawler->filter('select')->count() == 1);
    }

    public function testAddUserFailsBecauseUsernameIsBlank()
    {
        $client = $this->setUpClient();

        $params = array(
            "id" => 0,
            "al_email" => "text@example.com",
            "al_username" => "",
            "al_password" => "password",
            "al_role_id" => "6",
        );
        $crawler = $client->request('POST', '/backend/users/en/al_showUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Username field can not be empty")')->count() == 1);
    }

    public function testAddUserFailsBecauseUsernameIsTooShort()
    {
        $client = $this->setUpClient();

        $params = array(
            "id" => 0,
            "al_email" => "text@example.com",
            "al_username" => "aa",
            "al_password" => "password",
            "al_role_id" => "6",
        );
        $crawler = $client->request('POST', '/backend/users/en/al_showUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Username field is too short. It should have 3 characters or more")')->count() == 1);
    }

    public function testAddUserFailsBecauseEmailIsBlank()
    {
        $client = $this->setUpClient();

        $params = array(
            "id" => 0,
            "al_email" => "",
            "al_username" => "username",
            "al_password" => "password",
            "al_role_id" => "6",
        );
        $crawler = $client->request('POST', '/backend/users/en/al_showUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Email field can not be empty")')->count() == 1);
    }

    public function testAddUserFailsBecauseEmailIsInvalid()
    {
        $client = $this->setUpClient();

        $params = array(
            "id" => 0,
            "al_email" => "text@example",
            "al_username" => "username",
            "al_password" => "password",
            "al_role_id" => "6",
        );
        $crawler = $client->request('POST', '/backend/users/en/al_showUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("The email you entered is not valid")')->count() == 1);
    }

    public function testAddUserFailsBecausePasswordIsBlank()
    {
        $client = $this->setUpClient();

        $params = array(
            "id" => 0,
            "al_email" => "text@example.com",
            "al_username" => "username",
            "al_password" => "",
            "al_role_id" => "6",
        );
        $crawler = $client->request('POST', '/backend/users/en/al_showUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Password field can not be empty")')->count() == 1);
    }

    public function testAddUserFailsBecausePasswordIsTooShort()
    {
        $client = $this->setUpClient();

        $params = array(
            "id" => 0,
            "al_email" => "text@example.com",
            "al_username" => "username",
            "al_password" => "pwd",
            "al_role_id" => "6",
        );
        $crawler = $client->request('POST', '/backend/users/en/al_showUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Password field is too short. It should have 6 characters or more")')->count() == 1);
    }

    public function testUserHasBeenAdded()
    {
        $client = $this->setUpClient();

        $params = array(
            "id" => 0,
            "al_email" => "text@example.com",
            "al_username" => "username",
            "al_password" => "password",
            "al_role_id" => "6",
        );
        $crawler = $client->request('POST', '/backend/users/en/al_showUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("The user has been saved")')->count() == 1);

        $crawler = $client->request('GET', '/backend/users/en/al_userList');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('.al_edit_user')->count() == 2);
        $this->assertTrue($crawler->filter('.al_delete_user')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("admin")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("username")')->count() == 1);
    }

    public function testShowEditUserForm()
    {
        $this->markTestSkipped(
            'Something wrong retrieving the user.'
        );

        $client = $this->setUpClient();

        $user = $this->fetchUser($client, 'username');
        $params = array(
            "id" => $user->getId(),
        );

        $crawler = $client->request('GET', '/backend/users/en/al_showUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Username")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("username")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("Password")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("Email")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("text@example.com")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("Role")')->count() == 1);
        $this->assertTrue($crawler->filter('input')->count() == 6);
        $this->assertTrue($crawler->filter('select')->count() == 1);
    }

    public function testUserHasBeenEdited()
    {
        $client = $this->setUpClient();

        $user = $this->fetchUser($client, 'username');
        $params = array(
            "id" => $user->getId(),
            "al_email" => "edited@example.com",
            "al_username" => "john_doe",
            "al_password" => "secret",
            "al_role_id" => "2",
        );
        $crawler = $client->request('POST', '/backend/users/en/al_showUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("The user has been saved")')->count() == 1);

        $crawler = $client->request('GET', '/backend/users/en/al_userList');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('.al_edit_user')->count() == 2);
        $this->assertTrue($crawler->filter('.al_delete_user')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("admin")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("john_doe")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("edited@example.com")')->count() == 1);

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

        $crawler = $client->request('GET', '/backend/users/en/al_userList');
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

        $crawler = $client->request('GET', '/backend/users/en/al_rolesList');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("ROLE_USER")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("ROLE_ADMIN")')->count() == 1);
        $this->assertTrue($crawler->filter('html:contains("ROLE_SUPER_ADMIN")')->count() == 1);
        $this->assertEquals(3, $crawler->filter('.al_edit_role')->count());
        $this->assertEquals(3, $crawler->filter('.al_delete_role')->count());
    }

    public function testShowAddRoleForm()
    {
        $client = $this->setUpClient();

        $crawler = $client->request('GET', '/backend/users/en/al_showRole');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Role")')->count() == 1);
    }

    public function testAddRoleFailsBecauseRoleIsBlank()
    {
        $client = $this->setUpClient();

        $params = array(
            "id" => 0,
            "al_role" => "",
        );
        $crawler = $client->request('POST', '/backend/users/en/al_showRole', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Role field can not be empty")')->count() == 1);
    }

    public function testAddRoleFailsBecauseGivenRoleHasAnInvalidPrefix()
    {
        $client = $this->setUpClient();

        $params = array(
            "id" => 0,
            "al_rolename" => "FAKE",
        );
        $crawler = $client->request('POST', '/backend/users/en/al_showRole', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("A valid role must start with the ROLE_ prefix")')->count() == 1);
    }

    public function testRoleHasBeenAdded()
    {
        $client = $this->setUpClient();

        $params = array(
            "id" => 0,
            "al_rolename" => "ROLE_BOSS",
        );
        $crawler = $client->request('POST', '/backend/users/en/al_showRole', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("The role has been saved")')->count() == 1);

        $crawler = $client->request('GET', '/backend/users/en/al_rolesList');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("ROLE_BOSS")')->count() == 1);
    }

    public function testRoleHasBeenEdited()
    {
        $client = $this->setUpClient();

        $role = $this->fetchRole($client, 'ROLE_BOSS');
        $params = array(
            "id" => $role->getId(),
            "al_rolename" => "ROLE_GOD",
        );
        $crawler = $client->request('POST', '/backend/users/en/al_showRole', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("The role has been saved")')->count() == 1);

        $crawler = $client->request('GET', '/backend/users/en/al_rolesList');
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

        $crawler = $client->request('GET', '/backend/users/en/al_rolesList');
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
            "al_email" => "text@example.com",
            "al_username" => "username",
            "al_password" => "password",
            "al_role_id" => $role->getId(),
        );
        $crawler = $client->request('POST', '/backend/users/en/al_showUser', $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $credentials = array(
                'PHP_AUTH_USER' => 'username',
                'PHP_AUTH_PW' => 'password',
            );
        $client = $this->setUpClient($credentials);

        $crawler = $client->request('GET', '/backend/en/index');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $crawler = $client->request('GET', '/backend/users/en/al_userList');
        $response = $client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());

        $crawler = $client->request('GET', '/backend/en/al_local_deploy');
        $response = $client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
    }

    private function setUpClient(array $credentials = null)
    {
        if (null === $credentials) {
            $credentials = array(
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW' => 'admin',
            );
        }

        $client = static::createClient(
            array(
                'environment' => 'alcms_test',
                'debug'       => true,
            ),
            $credentials
        );

        return $client;
    }

    private function fetchUser($client, $username)
    {
        $factoryRepository = $client->getContainer()->get('alpha_lemon_cms.factory_repository');
        $repository = $factoryRepository->createRepository('User');

        return $repository->fromUserName($username);
    }

    private function fetchRole($client, $rolename)
    {
        $factoryRepository = $client->getContainer()->get('alpha_lemon_cms.factory_repository');
        $repository = $factoryRepository->createRepository('Role');

        return $repository->fromRoleName($rolename);
    }
}
