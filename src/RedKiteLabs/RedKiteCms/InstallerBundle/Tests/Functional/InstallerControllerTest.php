<?php
/*
 * This file is part of the RedKiteCms CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@alphalemon.com>
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

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

require_once __DIR__ . '/project/src/Acme/WebSiteBundle/AcmeWebSiteBundle.php';

/**
 * InstallerControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class InstallerControllerTest extends WebTestCase
{
    private static $filesystem;
/*
    public static function setUpBeforeClass()
    {
        self::$languages = array(array('Language'      => 'en',));

        self::$pages = array(array('PageName'      => 'index',
                                    'TemplateName'  => 'home',
                                    'IsHome'        => '1',
                                    'Permalink'     => 'this is a website fake page',
                                    'MetaTitle'         => 'page title',
                                    'MetaDescription'   => 'page description',
                                    'MetaKeywords'      => 'key'),
                            array('PageName'      => 'page1',
                                    'TemplateName'  => 'fullpage',
                                    'Permalink'     => 'page-1',
                                    'MetaTitle'         => 'page 1 title',
                                    'MetaDescription'   => 'page 1 description',
                                    'MetaKeywords'      => ''));
        self::populateDb();
    }

    protected function setUp()
    {
        parent::setUp();

        $this->pageRepository = new AlPageRepositoryPropel();
        $this->seoRepository = new AlSeoRepositoryPropel();
        $this->blockRepository = new AlBlockRepositoryPropel();
    }

    public static function setUpBeforeClass()
    {
        self::$filesystem = new \Symfony\Component\Filesystem\Filesystem();
        self::$filesystem->mirror(__DIR__ . '/project', __DIR__ . '/project-bck');
    }

    public static function tearDownAfterClass()
    {return;
        self::$filesystem->remove(__DIR__ . '/project');
        self::$filesystem->mirror(__DIR__ . '/project-bck', __DIR__ . '/project');
        self::$filesystem->remove(__DIR__ . '/project-bck');
    }*/

    protected function setUp()
    {
        $this->client = static::createClient(array(
            'environment' => 'test',
            'debug'       => true,
            ));
    }

    public function testInstallForm()
    {
        $crawler = $this->client->request('GET', 'install');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("RedKiteCms CMS requires a bundle where RedKiteCms CMS will save the contents you insert")')->count() > 0);
        $this->assertEquals(1, $crawler->filter('#red_kite_cms_parameters_company')->count());
        $this->assertEquals(1, $crawler->filter('#red_kite_cms_parameters_bundle')->count());
        $this->assertEquals(1, $crawler->filter('#red_kite_cms_parameters_driver')->count());
        $this->assertEquals(1, $crawler->filter('#red_kite_cms_parameters_host')->count());
        $this->assertEquals(1, $crawler->filter('#red_kite_cms_parameters_database')->count());
        $this->assertEquals(1, $crawler->filter('#red_kite_cms_parameters_port')->count());
        $this->assertEquals(1, $crawler->filter('#red_kite_cms_parameters_user')->count());
        $this->assertEquals(1, $crawler->filter('#red_kite_cms_parameters_password_password')->count());
        $this->assertEquals(1, $crawler->filter('#red_kite_cms_parameters_password_password_again')->count());
        $this->assertEquals(1, $crawler->filter('input[type=submit]')->count());
    }

    public function testDoInstall()
    {return;
        $crawler = $this->client->request('GET', 'install');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $form = $crawler->filter('input[type=submit]')->form();
        $crawler = $this->client->submit($form, array(
            'red_kite_cms_parameters[company]' => 'Acme',
            'red_kite_cms_parameters[bundle]' => 'WebSiteBundle',
            'red_kite_cms_parameters[driver]' => 'mysql',
            'red_kite_cms_parameters[host]' => 'localhost',
            'red_kite_cms_parameters[database]' => 'alphalemon_test',
            'red_kite_cms_parameters[port]' => '3306',
            'red_kite_cms_parameters[user]' => 'root',
            'red_kite_cms_parameters[password][password]' => '',
        ));
    }

}