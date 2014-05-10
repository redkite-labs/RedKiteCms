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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Generator;

use RedKiteLabs\RedKiteCms\InstallerBundle\Core\Generator\ConfigurationGenerator;
use RedKiteLabs\RedKiteCms\InstallerBundle\Tests\TestCase;
use org\bovigo\vfs\vfsStream;

/**
 * ConfigurationGeneratorTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ConfigurationGeneratorTest extends TestCase
{
    private $configurationGenerator;

    protected function setUp()
    {
        parent::setUp();

        $this->root = vfsStream::setup('root', null, array(
            'app' => array(
                'config' => array(
                ),
            ),
            'web' => array(),
        ));

        $this->configurationGenerator = new ConfigurationGenerator(vfsStream::url('root/app'), array(
            'bundle' => 'AcmeWebsiteBundle',
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'redkite',
            'user' => 'root',
            'password' => '',
            'website-url' => 'http://example.com/',
        ));
    }

    public function testRedKiteCmsKernelAndConsole()
    {
        $this->configurationGenerator->generateApplication();
        $this->assertFileExists(vfsStream::url('root/app/RedKiteCmsAppKernel.php'));
        $this->assertFileExists(vfsStream::url('root/app/rkconsole'));
    }

    public function testConfigurations()
    {
        $this->configurationGenerator->generateConfigurations();
        $this->assertFileExists(vfsStream::url('root/app/config/config_rkcms.yml'));
        $this->assertFileExists(vfsStream::url('root/app/config/config_rkcms_dev.yml'));
        $this->assertFileExists(vfsStream::url('root/app/config/config_rkcms_test.yml'));
        $this->assertFileExists(vfsStream::url('root/app/config/config_stage.yml'));
        $this->assertFileExists(vfsStream::url('root/app/config/config_stage_dev.yml'));
    }

    public function testRouting()
    {
        $this->configurationGenerator->generateRoutes();
        $this->assertFileExists(vfsStream::url('root/app/config/routing_rkcms.yml'));
        $this->assertFileExists(vfsStream::url('root/app/config/routing_rkcms_dev.yml'));
        $this->assertFileExists(vfsStream::url('root/app/config/routing_rkcms_test.yml'));
        $this->assertFileExists(vfsStream::url('root/app/config/routing_stage.yml'));
        $this->assertFileExists(vfsStream::url('root/app/config/routing_stage_dev.yml'));
    }

    public function testFrontcontrollers()
    {
        $this->configurationGenerator->generateFrontcontrollers();
        $this->assertFileExists(vfsStream::url('root/web/rkcms.php'));
        $this->assertFileExists(vfsStream::url('root/web/rkcms_dev.php'));
        $this->assertFileExists(vfsStream::url('root/web/stage.php'));
        $this->assertFileExists(vfsStream::url('root/web/stage_dev.php'));
    }
}