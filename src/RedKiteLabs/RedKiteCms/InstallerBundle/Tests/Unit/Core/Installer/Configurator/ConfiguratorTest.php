<?php
/**
 * This file is part of the RedKiteLabsRedKiteCmsBundle and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Installer\Configurator;

use RedKiteLabs\RedKiteCms\InstallerBundle\Core\Installer\Configurator\Configurator;
use RedKiteLabs\RedKiteCms\InstallerBundle\Tests\TestCase;
use org\bovigo\vfs\vfsStream;

/**
 * ConfiguratorTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ConfiguratorTest extends TestCase
{
    private $kernel;
    private $configurator;

    protected function setUp()
    {
        parent::setUp();

        $this->root = vfsStream::setup('root', null, array(
            'app' => array(
                'cache' => array(),
                'logs' => array(),
                'config' => array(
                    'bundles' => array(),
                    'config.yml' => '',
                    'routing.yml' => '',
                    'parameters.yml' => '
parameters:
    database_driver: pdo_mysql
    database_host: 127.0.0.1',
                ),
                'AppKernel.php' => '
                <?php
                    class AppKernel extends Kernel
                    {
                        public function registerBundles()
                        {
                            $bundles = array(
                                new Acme\WebSiteBundle\AcmeWebSiteBundle(),
                            );

                            return $bundles;
                        );
                    }',
            ),
            'src' => array(
                'InstallerBundle' => array(
                    'Resources' => array(
                        'config' => array(),
                    ),
                ),
            ),
            'vendor' => array(
                'phing' => array(),
            ),
            'web' => array(),
        ));

        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $this->kernel
            ->expects($this->once())
            ->method('getRootDir')
            ->will($this->returnValue(vfsStream::url('root/app')))
        ;
    }

    public function testConfigure()
    {
        $this->kernel
            ->expects($this->once())
            ->method('locateResource')
            ->will($this->returnValue(vfsStream::url('root/src/InstallerBundle')));

        $this->initConfigurator();

        $this->configurator->configure();
        //print_r(vfsStream::inspect(new \org\bovigo\vfs\visitor\vfsStreamStructureVisitor())->getStructure());exit;

        $this->assertRegExp("/RedKiteLabsThemeEngineBundle/s", file_get_contents(vfsStream::url('root/app/AppKernel.php')));
        $this->assertRegExp("/ModernBusinessThemeBundle/s", file_get_contents(vfsStream::url('root/app/AppKernel.php')));
        $this->assertFileExists(vfsStream::url('root/app/AppKernel.php.bak'));

        $this->assertEquals("\nred_kite_labs_theme_engine:\n    deploy_bundle: AcmeWebSiteBundle\n\n",file_get_contents(vfsStream::url('root/app/config/config.yml')));
        $this->assertFileExists(vfsStream::url('root/app/config/config.yml.bak'));
        $this->assertEquals("_AcmeWebSiteBundle:\n    resource: \"@AcmeWebSiteBundle/Resources/config/site_routing.yml\"\n\n",file_get_contents(vfsStream::url('root/app/config/routing.yml')));
        $this->assertFileExists(vfsStream::url('root/app/config/routing.yml.bak'));
        $this->assertEquals("parameters:\n    database_driver: pdo_mysql\n    database_host: 127.0.0.1\n    rkcms_database_driver: mysql\n    rkcms_database_host: localhost\n    rkcms_database_port: 3306\n    rkcms_database_name: redkite\n    rkcms_database_user: root\n    rkcms_database_password: ''\n",file_get_contents(vfsStream::url('root/app/config/parameters.yml')));
        $this->assertFileExists(vfsStream::url('root/app/config/parameters.yml.bak'));
        $this->assertFileExists(vfsStream::url('root/web/rkcms.php'));
        $this->assertFileExists(vfsStream::url('root/web/rkcms_dev.php'));
        $this->assertFileExists(vfsStream::url('root/web/stage.php'));
        $this->assertFileExists(vfsStream::url('root/web/stage_dev.php'));
    }

    public function testAtLeastOneFileIsNotWritable()
    {
        $this->initConfigurator();

        $webFolder = $this->root->getChild('web');
        $webFolder->chmod('0000');

        $this->assertEquals(-1, $this->configurator->configure());
        $webFolder->chmod('0777');

        $appKernel = $this->root->getChild('app')->getChild('AppKernel.php');
        $appKernel->chmod('0000');

        $this->assertEquals(-1, $this->configurator->configure());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error occurred. RedKite CMS requires phing installed into vfs://root/app/../vendor folder. Please install the required library then run the script again.
     */
    public function testAtLeastOnePrerequisiteIsNotInstalled()
    {
        $this->initConfigurator();

        rmdir(vfsStream::url('root/vendor/phing'));
        $this->configurator->configure();
    }

    private function initConfigurator($options = null)
    {
        if (null === $options) {
            $options = array(
                'bundle' => 'AcmeWebSiteBundle',
                'driver' => 'mysql',
                'host' => 'localhost',
                'database' => 'redkite',
                'port' => '3306',
                'user' => 'root',
                'password' => '',
                'website-url' => 'http://example.com/',
            );
        }

        $this->configurator = new Configurator($this->kernel, $options);
    }
}