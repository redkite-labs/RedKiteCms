<?php
/*
 * This file is part of the AlphaLemonBootstrapBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 *
 * @license    MIT License
 */

namespace AlphaLemon\BootstrapBundle\Tests\Unit\Autoloader;

use AlphaLemon\BootstrapBundle\Core\Autoloader\BundlesAutoloader;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;
use AlphaLemon\BootstrapBundle\Tests\Unit\Base\BaseFilesystem;

/**
 * BundlesAutoloaderTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class BundlesAutoloaderTest extends BaseFilesystem
{
    private $bundlesAutoloader = null;

    protected function setUp()
    {
        parent::setUp();
        $this->scriptFactory = $this->getMock('AlphaLemon\BootstrapBundle\Core\Script\Factory\ScriptFactoryInterface');

        $folders = array('app' => array(),
                         'vendor' => array('composer' => array()),
                        );
        $this->root = vfsStream::setup('root', null, $folders);

        $this->bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $this->scriptFactory);
    }

    public function testFoldersHaveBeenCreated()
    {
        $expectedResult = array('root' =>
                                    array('app' =>
                                        array('config' =>
                                            array('bundles' =>
                                                array('autoloaders' => array(),
                                                      'config' => array(),
                                                      'routing' => array(),
                                                      'cache' => array(),
                                                    )
                                                )
                                            ),
                                'vendor' => array('composer' => array()),
                                ));

        $this->assertEquals($expectedResult, vfsStream::inspect(new vfsStreamStructureVisitor())->getStructure());
    }

    /**
     * @expectedException \AlphaLemon\BootstrapBundle\Core\Exception\InvalidProjectException
     * @expectedExceptionMessage "composer" folder has not been found. Be sure to use this bundle on a project managed by Composer
     */
    public function testAnExceptionIsThrownWhenTheProjectIsNotManagedByComposer()
    {
        $folders = array('app' => array(),
                         'vendor' => array(),
                        );
        $this->root = vfsStream::setup('root', null, $folders);
        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $this->scriptFactory);
        $bundlesAutoloader->getBundles();
    }

    public function testOnlyBundlesWithAnAutoloadFileAreAutoloaded()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselBundle/';
        $this->createBundle($bundleFolder, 'BusinessCarouselBundle');

        $bundleFolder = 'root/vendor/alphalemon/app-business-dropcap-bundle/AlphaLemon/Block/BusinessDropCapBundle/';
        $this->createBundle($bundleFolder, 'BusinessDropCapBundle', false); // This bundle has any autoload.json file

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $this->assertEquals(1, count($this->bundlesAutoloader->getBundles()));
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/autoloaders/businesscarousel.json')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/config/businesscarousel.yml')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/routing/businesscarousel.yml')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/autoloaders/businessdropcap.json')));
    }

    public function testOnlyBundlesWithAnAutoloadFileAreAutoloaded1()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselBundle/';

        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\Block\\\\BusinessCarouselBundle\\\\BusinessCarouselBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["all"]' . PHP_EOL;
        $autoload .= '        }' . PHP_EOL;
        $autoload .= '    },' . PHP_EOL;
        $autoload .= '    "actionManager" : "\\\\AlphaLemon\\\\Block\\\\BusinessCarouselBundle\\\\Core\\\\ActionManager\\\\ActionManagerBusinessCarousel"';
        $autoload .= '}';

        $this->createBundle($bundleFolder, 'BusinessCarouselBundle', $autoload);
        $this->addClassManager('root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselBundle/Core/ActionManager/', 'ActionManagerBusinessCarousel.php', 'BusinessCarouselBundle');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $this->assertEquals(1, count($this->bundlesAutoloader->getBundles()));
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/autoloaders/businesscarousel.json')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/config/businesscarousel.yml')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/routing/businesscarousel.yml')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/autoloaders/businessdropcap.json')));
    }

    public function testConfigAndRoutingFilesAreCopiedToRespectiveFolders()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselBundle/';
        $this->createBundle($bundleFolder, 'BusinessCarouselBundle');
        $configFolder = $bundleFolder . 'Resources/config';
        $this->createFolder($configFolder);
        $this->createFile($configFolder . '/config.yml');
        $this->createFile($configFolder . '/routing.yml');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $this->bundlesAutoloader->getBundles();

        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/autoloaders/businesscarousel.json')));
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/config/businesscarousel.yml')));
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/routing/businesscarousel.yml')));
    }

    /**
     * @expectedException \AlphaLemon\BootstrapBundle\Core\Exception\InvalidAutoloaderException
     * @expectedExceptionMessage The bundle class AlphaLemon\Block\BusinessCarouselBundle\BusinessCarousellBundle does not exist. Check the bundle's autoload.json to fix the problem
     */
    public function testAnExceptionIsThrownWhenTheClassGIvenInTheAutoloaderHasNotBeenFound()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselBundle/';

        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\Block\\\\BusinessCarouselBundle\\\\BusinessCarousellBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["all"]' . PHP_EOL;
        $autoload .= '        }' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessCarouselBundle', $autoload);

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $this->bundlesAutoloader->getBundles();
    }

    public function testABundleIsNotInstantiatedWhenItIsNotRequiredForTheCurrentEnvironment()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselBundle/';

        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\Block\\\\BusinessCarouselBundle\\\\BusinessCarouselBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["dev"]' . PHP_EOL;
        $autoload .= '        }' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessCarouselBundle', $autoload);

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'prod', array(), $this->scriptFactory);
        $bundlesAutoloader->getBundles();

        $this->assertEquals(0, count($bundlesAutoloader->getBundles()));
    }

    public function testInstallationByEnvironmentWithMoreBundles()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselBundle/';

        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\Block\\\\BusinessCarouselBundle\\\\BusinessCarouselBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["dev"],' . PHP_EOL;
        $autoload .= '           "overrides" : ["BusinessDropCapBundle"]' . PHP_EOL;
        $autoload .= '        }' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessCarouselBundle', $autoload);

        $bundleFolder = 'root/vendor/alphalemon/app-business-dropcap-bundle/AlphaLemon/Block/BusinessDropCapBundle/';
        $this->createBundle($bundleFolder, 'BusinessDropCapBundle');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'prod', array(), $this->scriptFactory);
        $bundlesAutoloader->getBundles();

        $this->assertEquals(1, count($bundlesAutoloader->getBundles()));
    }

    public function testAnOverridedBundleIsPladedAfterTheOneHowOverrideIt()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselBundle/';

        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\Block\\\\BusinessCarouselBundle\\\\BusinessCarouselBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["all"],' . PHP_EOL;
        $autoload .= '           "overrides" : ["BusinessDropCapBundle"]' . PHP_EOL;
        $autoload .= '        }' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessCarouselBundle', $autoload);

        $bundleFolder = 'root/vendor/alphalemon/app-business-dropcap-bundle/AlphaLemon/Block/BusinessDropCapBundle/';
        $this->createBundle($bundleFolder, 'BusinessDropCapBundle');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'prod', array(), $this->scriptFactory);
        $bundles = $bundlesAutoloader->getBundles();
        $this->assertEquals(2, count($bundles));
        $this->assertEquals(array('BusinessDropCapBundle', 'BusinessCarouselBundle'), array_keys($bundles));
    }

    public function testUninstall()
    {
        $this->createAutoloadNamespacesFile();

        // Autoloads a bundle
        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselBundle/';
        $this->createBundle($bundleFolder, 'BusinessCarouselBundle');
        $configFolder = $bundleFolder . 'Resources/config';
        $this->createFolder($configFolder);
        $this->createFile($configFolder . '/config.yml');
        $this->createFile($configFolder . '/routing.yml');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $this->assertEquals(1, count($this->bundlesAutoloader->getBundles()));
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/autoloaders/businesscarousel.json')));
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/config/businesscarousel.yml')));
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/routing/businesscarousel.yml')));

        // Next call
        $fs = new \Symfony\Component\Filesystem\Filesystem();
        $fs->remove(vfsStream::url($bundleFolder));

        $this->addClassManager('root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselBundle/Core/ActionManager/', 'ActionManagerBusinessCarousel.php', 'BusinessCarouselBundle');

        $script = $this->getMock('AlphaLemon\BootstrapBundle\Core\Script\ScriptInterface');
        $script->expects($this->exactly(2))
            ->method('executeActions');

        $scriptFactory = $this->getMock('AlphaLemon\BootstrapBundle\Core\Script\Factory\ScriptFactoryInterface');
        $scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($script));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $scriptFactory);
        $this->assertEquals(0, count($bundlesAutoloader->getBundles()));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/autoloaders/businesscarousel.json')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/config/businesscarousel.yml')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/routing/businesscarousel.yml')));
    }

    protected function createAutoloadNamespacesFile($autoloadNamespaces = null)
    {
        if(null === $autoloadNamespaces) {
            $autoloadNamespaces = '<?php' . PHP_EOL;
            $autoloadNamespaces .= '$vendorDir = dirname(__DIR__);' . PHP_EOL;
            $autoloadNamespaces .= '$baseDir = dirname($vendorDir);' . PHP_EOL;
            $autoloadNamespaces .= 'return array(' . PHP_EOL;
            $autoloadNamespaces .= '    \'AlphaLemon\\Block\\BusinessCarouselBundle\' => $vendorDir . \'/alphalemon/app-business-carousel-bundle/\',' . PHP_EOL;
            $autoloadNamespaces .= '    \'AlphaLemon\\Block\\BusinessDropCapBundle\' => $vendorDir . \'/alphalemon/app-business-dropcap-bundle/\',' . PHP_EOL;
            $autoloadNamespaces .= ');' . PHP_EOL;
        }

        $this->createFile('root/vendor/composer/autoload_namespaces.php', $autoloadNamespaces);
    }

    private function createScript()
    {
        $script = $this->getMock('AlphaLemon\BootstrapBundle\Core\Script\ScriptInterface');
        $script->expects($this->exactly(2))
            ->method('executeActions');

        return $script;
    }
}