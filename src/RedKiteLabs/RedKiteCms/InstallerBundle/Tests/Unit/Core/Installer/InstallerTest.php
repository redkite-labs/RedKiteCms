<?php
/*
 * This file is part of the RedKiteCmsBootstrapBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 *
 * @license    MIT License
 */

namespace RedKiteCms\InstallerBundle\Tests\Unit\Installer;

use RedKiteCms\InstallerBundle\Tests\TestCase;
use RedKiteCms\InstallerBundle\Core\Installer\Installer;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;


/**
 * InstallerTest
 *
 * @author RedKiteCms <webmaster@alphalemon.com>
 */
class InstallerTest extends TestCase
{
    private $installer = null;


    protected function setUp()
    {
        parent::setUp();

        /*
        $folders = array('app' => array('Resources' => array(
                                            'java' => array(
                                                'yuicompressor.jar' => ''),
                                            ),
                                        'config' => array(
                                            'config.yml' => '',
                                            'routing.yml' => ''),
                                            ),
                         'src' => array(
                             'Acme' =>
                                array(
                                    'WebSiteBundle' => array(
                                        'Resources' => array(
                                            'config' => array()
                                            )
                                    )
                                )
                             ),
                         'web' => array('js' => array('tiny_mce' => array())),
                         'vendor' => array('phing' => array(), 'propel' => array(), 'alphalemon' => array('alphalemon-cms-bundle' => array('RedKiteCms' => array()))),
                        );*/
        $folders = array('app' => array(),
                         'src' => array(
                             'Acme' =>
                                array(
                                    'WebSiteBundle' => array(
                                        'Resources' => array(
                                            'config' => array()
                                            )
                                    )
                                )
                             ),
                         'web' => array(),
                         'vendor' => array(),
                        );
        $this->root = vfsStream::setup('root', null, $folders);
/*
        vfsStream::copyFromFileSystem(__DIR__ . '/../../../Functional/Resources', $this->root->getChild('app'));
        vfsStream::copyFromFileSystem(__DIR__ . '/../../../../vendor/alphalemon/alphalemon-cms-bundle/RedKiteCms', $this->root->getChild('vendor')->getChild('alphalemon')->getChild('alphalemon-cms-bundle')->getChild('RedKiteCms'));
*/

        $this->orm = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Orm\OrmInterface');
        $this->processConsole = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\ProcessConsole\ProcessConsoleInterface');
        $this->installer = new Installer(vfsStream::url('root/vendor'), $this->orm, $this->processConsole);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error occoured. RedKiteCms CMS requires phing installed into vfs://root/vendor folder. Please install the required library then run the script again.
     */
    public function testRedKiteCmsRequiresPhing()
    {
        $this->installer->install('Acme', 'WebSiteBundle', 'mysql:host=localhost;port=3306;dbname=alphalemon_test', 'alphalemon_test', 'root', '', 'mysql');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error occoured. RedKiteCms CMS requires tiny_mce installed into vfs://root/vendor/../web/js folder. Please install the required library then run the script again.
     */
    public function testRedKiteCmsRequiresTinyMCE()
    {
        $this->addPhing();
        $this->installer->install('Acme', 'WebSiteBundle', 'mysql:host=localhost;port=3306;dbname=alphalemon_test', 'alphalemon_test', 'root', '', 'mysql');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error occoured. RedKiteCms CMS requires yuicompressor.jar installed into vfs://root/vendor/../app/Resources/java folder. Please install the required library then run the script again.
     */
    public function testRedKiteCmsRequiresYuicompressor()
    {
        $this->addPhing();
        $this->addTinyMCE();
        $this->installer->install('Acme', 'WebSiteBundle', 'mysql:host=localhost;port=3306;dbname=alphalemon_test', 'alphalemon_test', 'root', '', 'mysql');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The required vfs://root/vendor/../app/AppKernel.php file has not been found
     */
    public function testRedKiteCmsRequiresAppKernel()
    {
        $this->addPhing();
        $this->addTinyMCE();
        $this->addYuicompressor();
        $this->installer->install('Acme', 'WebSiteBundle', 'mysql:host=localhost;port=3306;dbname=alphalemon_test', 'alphalemon_test', 'root', '', 'mysql');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The required vfs://root/vendor/../app/config/config.yml file has not been found
     */
    public function testConfigFileDoesNotExist()
    {
        $this->addPhing();
        $this->addTinyMCE();
        $this->addYuicompressor();
        $this->addAppKernel();
        $this->installer->install('Acme', 'WebSiteBundle', 'mysql:host=localhost;port=3306;dbname=alphalemon_test', 'alphalemon_test', 'root', '', 'mysql');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The required vfs://root/vendor/../app/config/routing.yml file has not been found
     */
    public function testRoutingFileDoesNotExist()
    {
        $this->addPhing();
        $this->addTinyMCE();
        $this->addYuicompressor();
        $this->addAppKernel();
        $this->addConfigFile();
        $this->installer->install('Acme', 'WebSiteBundle', 'mysql:host=localhost;port=3306;dbname=alphalemon_test', 'alphalemon_test', 'root', '', 'mysql');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The database has not be created. Check your configuration parameters
     */
    public function testDatabaseCreationFails()
    {
        $this->addPhing();
        $this->addTinyMCE();
        $this->addYuicompressor();
        $this->addAppKernel();
        $this->addConfigFile();
        $this->addRoutingFile();

        $this->orm->expects($this->once())
                ->method('executeQuery')
                ->will($this->returnValue(false));

        $this->installer->install('Acme', 'WebSiteBundle', 'mysql:host=localhost;port=3306;dbname=alphalemon_test', 'alphalemon_test', 'root', '', 'mysql');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error has occoured executing the "propel:insert-sql --force --env=alcms_dev" command
     */
    public function testRedKiteCmsHasBeenInstalled1()
    {
        $this->addPhing();
        $this->addTinyMCE();
        $this->addYuicompressor();
        $this->addAppKernel();
        $this->addConfigFile();
        $this->addRoutingFile();

        $this->processConsole->expects($this->once())
                ->method('executeCommands')
                ->will($this->throwException(new \RuntimeException('An error has occoured executing the "propel:insert-sql --force --env=alcms_dev" command')));

        $this->installer->install('Acme', 'WebSiteBundle', 'mysql:host=localhost;port=3306;dbname=alphalemon_test', 'alphalemon_test', 'root', '', 'mysql');
    }

    public function testRedKiteCmsHasBeenInstalled()
    {
        $this->addPhing();
        $this->addTinyMCE();
        $this->addYuicompressor();
        $this->addAppKernel();
        $this->addConfigFile();
        $this->addRoutingFile();

        $this->processConsole->expects($this->once())
                ->method('executeCommands');

        $this->installer->install('Acme', 'WebSiteBundle', 'mysql:host=localhost;port=3306;dbname=alphalemon_test', 'alphalemon_test', 'root', '', 'mysql');

        $appKernelContents = file_get_contents(vfsStream::url('root/app/AppKernel.php'));
        $this->assertRegExp("/RedKiteCms\\\\BootstrapBundle\\\\RedKiteCmsBootstrapBundle\(\),/s", $appKernelContents);
        $this->assertRegExp('/\$bootstrapper = new \\\\RedKiteCms\\\\BootstrapBundle\\\\Core\\\\Autoloader\\\\BundlesAutoloader\(__DIR__, \$this-\>getEnvironment\(\), \$bundles\);/s', $appKernelContents);
        $this->assertRegExp('/\$bundles = \$bootstrapper-\>getBundles\(\);/s', $appKernelContents);
        $this->assertRegExp('/\$configFolder = __DIR__ . \'\/config\/bundles\/config\';/s', $appKernelContents);

        $config = file_get_contents(vfsStream::url('root/app/config/config.yml'));
        $this->assertRegExp('/alpha_lemon_frontend:/s', $config);
        $this->assertRegExp('/deploy_bundle: AcmeWebSiteBundle/s', $config);

        $routing = file_get_contents(vfsStream::url('root/app/config/routing.yml'));
        $this->assertRegExp('/_AcmeWebSiteBundle:/s', $routing);
        $this->assertRegExp('/resource: "@AcmeWebSiteBundle\/Resources\/config\/site_routing.yml"/s', $routing);

        $this->assertTrue(file_exists(vfsStream::url('root/src/Acme/WebSiteBundle/Resources/config/site_routing.yml')));
    }

    private function addPhing()
    {
        mkdir(vfsStream::url('root/vendor/phing'), 0777, true);
    }

    private function addTinyMCE()
    {
        mkdir(vfsStream::url('root/web/js/tiny_mce'), 0777, true);
    }

    private function addYuicompressor()
    {
        mkdir(vfsStream::url('root/app/Resources/java'), 0777, true);
        file_put_contents(vfsStream::url('root/app/Resources/java/yuicompressor.jar'), "");
    }

    private function addAppKernel()
    {
        vfsStream::copyFromFileSystem(__DIR__ . '/../../../Functional/Resources', $this->root->getChild('app'));

        mkdir(vfsStream::url('root/vendor/alphalemon/alphalemon-cms-bundle/RedKiteCms'), 0777, true);
        vfsStream::copyFromFileSystem(__DIR__ . '/../../../../vendor/alphalemon/alphalemon-cms-bundle/RedKiteCms', $this->root->getChild('vendor')->getChild('alphalemon')->getChild('alphalemon-cms-bundle')->getChild('RedKiteCms'));
    }

    private function addConfigFile()
    {
        mkdir(vfsStream::url('root/app/config'), 0777, true);
        file_put_contents(vfsStream::url('root/app/config/config.yml'), "");
    }

    private function addRoutingFile()
    {
        file_put_contents(vfsStream::url('root/app/config/routing.yml'), "");
    }
}