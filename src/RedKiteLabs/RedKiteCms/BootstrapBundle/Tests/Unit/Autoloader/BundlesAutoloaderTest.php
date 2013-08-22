<?php
/*
 * This file is part of the RedKiteLabsBootstrapBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://redkite-labs.com
 *
 * @license    MIT License
 */

namespace RedKiteLabs\BootstrapBundle\Tests\Unit\Autoloader;

use RedKiteLabs\BootstrapBundle\Core\Autoloader\BundlesAutoloader;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;
use RedKiteLabs\BootstrapBundle\Tests\Unit\Base\BaseFilesystem;

/**
 * BundlesAutoloaderTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BundlesAutoloaderTest extends BaseFilesystem
{
    private $bundlesAutoloader = null;

    protected function setUp()
    {
        parent::setUp();
        $this->scriptFactory = $this->getMock('RedKiteLabs\BootstrapBundle\Core\Script\Factory\ScriptFactoryInterface');

        $folders = array('app' => array(),
                         'src' => array(
                             'Acme' => array(),
                             'RedKiteLabs' => 
                                array(
                                    'Block' => array(),
                                ),
                         ),
                         'vendor' => array(
                             'composer' => array(),
                         ),
                        );
        $this->root = vfsStream::setup('root', null, $folders);
    }

    /**
     * @expectedException \RedKiteLabs\BootstrapBundle\Core\Exception\InvalidProjectException
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


    public function testFoldersHaveBeenCreated()
    {
        $folders = array('app' => array(),
                         'vendor' => array('composer' => array('autoload_namespaces.php' => '<?php return array();')),
                        );
        $this->root = vfsStream::setup('root', null, $folders);

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
                                'vendor' => array('composer' => array('autoload_namespaces.php' => '<?php return array();')),
                                ));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $this->scriptFactory);
        $this->assertEquals($expectedResult, vfsStream::inspect(new vfsStreamStructureVisitor())->getStructure());
    }
    
    public function testOnlyBundlesWithAnAutoloadFileAreAutoloaded()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/redkite-cms/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle');

        $bundleFolder = 'root/vendor/redkite-cms/app-business-dropcap-bundle/RedKiteLabs/Block/BusinessDropCapFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessDropCapFakeBundle', false); // This bundle has any autoload.json file

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $this->scriptFactory);
        $this->assertCount(1, $bundlesAutoloader->getBundles());
        $this->assertFileExists(vfsStream::url('root/app/config/bundles/autoloaders/businesscarouselfake.json'));
        $this->assertFileNotExists(vfsStream::url('root/app/config/bundles/config/dev/businesscarouselfake.yml'));
        $this->assertFileNotExists(vfsStream::url('root/app/config/bundles/routing/businesscarouselfake.yml'));
        $this->assertFileNotExists(vfsStream::url('root/app/config/bundles/autoloaders/businessdropcap.json'));
    }

    public function testOnlyBundlesWithAnAutoloadFileAreAutoloaded1()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/redkite-cms/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/';

        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "RedKiteLabs\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["all"]' . PHP_EOL;
        $autoload .= '        }' . PHP_EOL;
        $autoload .= '    },' . PHP_EOL;
        $autoload .= '    "actionManager" : "\\\\RedKiteLabs\\\\Block\\\\BusinessCarouselFakeBundle\\\\Core\\\\ActionManager\\\\ActionManagerBusinessCarousel"';
        $autoload .= '}';

        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle', $autoload);
        $this->addClassManager('root/vendor/redkite-cms/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/Core/ActionManager/', 'ActionManagerBusinessCarousel.php', 'BusinessCarouselFakeBundle');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $this->scriptFactory);
        $this->assertCount(1, $bundlesAutoloader->getBundles());
        $this->assertFileExists(vfsStream::url('root/app/config/bundles/autoloaders/businesscarouselfake.json'));
        $this->assertFileNotExists(vfsStream::url('root/app/config/bundles/config/dev/businesscarouselfake.yml'));
        $this->assertFileNotExists(vfsStream::url('root/app/config/bundles/routing/businesscarouselfake.yml'));
        $this->assertFileNotExists(vfsStream::url('root/app/config/bundles/autoloaders/businessdropcap.json'));
    }

    public function testConfigAndRoutingFilesAreCopiedToRespectiveFolders()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/redkite-cms/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle');
        $configFolder = $bundleFolder . 'Resources/config';
        $this->createFolder($configFolder);
        $this->createFile($configFolder . '/config.yml');
        $this->createFile($configFolder . '/routing.yml');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $this->scriptFactory);
        $bundlesAutoloader->getBundles();

        $this->assertFileExists(vfsStream::url('root/app/config/bundles/autoloaders/businesscarouselfake.json'));
        $this->assertFileExists(vfsStream::url('root/app/config/bundles/config/dev/businesscarouselfake.yml'));
        $this->assertFileExists(vfsStream::url('root/app/config/bundles/routing/businesscarouselfake.yml'));
    }

    /**
     * @expectedException \RedKiteLabs\BootstrapBundle\Core\Exception\InvalidAutoloaderException
     * @expectedExceptionMessage The bundle class RedKiteLabs\Block\BusinessCarouselFakeBundle\BusinessCarousellBundle does not exist. Check the bundle's autoload.json to fix the problem
     */
    public function testAnExceptionIsThrownWhenTheClassGIvenInTheAutoloaderHasNotBeenFound()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/redkite-cms/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/';

        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "RedKiteLabs\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarousellBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["all"]' . PHP_EOL;
        $autoload .= '        }' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle', $autoload);

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $this->scriptFactory);
        $bundlesAutoloader->getBundles();
    }

    public function testABundleIsNotInstantiatedWhenItIsNotRequiredForTheCurrentEnvironment()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/redkite-cms/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/';

        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "RedKiteLabs\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["dev"]' . PHP_EOL;
        $autoload .= '        }' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle', $autoload);

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'prod', array(), $this->scriptFactory);
        $bundlesAutoloader->getBundles();

        $this->assertCount(0, $bundlesAutoloader->getBundles());
    }

    public function testInstallationByEnvironmentWithMoreBundles()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/redkite-cms/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/';

        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "RedKiteLabs\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["dev"],' . PHP_EOL;
        $autoload .= '           "overrides" : ["BusinessDropCapFakeBundle"]' . PHP_EOL;
        $autoload .= '        }' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle', $autoload);

        $bundleFolder = 'root/vendor/redkite-cms/app-business-dropcap-bundle/RedKiteLabs/Block/BusinessDropCapFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessDropCapFakeBundle');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'prod', array(), $this->scriptFactory);
        $bundlesAutoloader->getBundles();

        $this->assertCount(1, $bundlesAutoloader->getBundles());
    }

    public function testAnOverridedBundleIsPlacedAfterTheOneHowOverrideIt()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/redkite-cms/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/';

        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "RedKiteLabs\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["all"],' . PHP_EOL;
        $autoload .= '           "overrides" : ["BusinessDropCapFakeBundle"]' . PHP_EOL;
        $autoload .= '        }' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle', $autoload);

        $bundleFolder = 'root/vendor/redkite-cms/app-business-dropcap-bundle/RedKiteLabs/Block/BusinessDropCapFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessDropCapFakeBundle');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'prod', array(), $this->scriptFactory);
        $bundles = $bundlesAutoloader->getBundles();
        $this->assertCount(2, $bundles);
        $this->assertEquals(array('BusinessDropCapFakeBundle', 'BusinessCarouselFakeBundle'), array_keys($bundles));
    }

    public function testAutoloadingABundleWithoutAutoloader()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/redkite-cms/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/';
        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "RedKiteLabs\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["all"]' . PHP_EOL;
        $autoload .= '        },' . PHP_EOL;
        $autoload .= '        "RedKiteLabs\\\\RedKiteLabsCms\\\\RedKiteLabsCmsFakeBundle\\\\RedKiteLabsCmsFakeBundle" : ""' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle', $autoload);

        $bundleFolder = 'root/vendor/redkite-cms/redkite-cms-cms-bundle/RedKiteLabs/RedKiteLabsCms/RedKiteLabsCmsFakeBundle/';
        $this->createBundle($bundleFolder, 'RedKiteLabsCmsFakeBundle', false, 'RedKiteLabs\RedKiteLabsCms');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'prod', array(), $this->scriptFactory);
        $bundles = $bundlesAutoloader->getBundles();
        $this->assertCount(2, $bundles);
        $this->assertEquals(array('BusinessCarouselFakeBundle', 'RedKiteLabsCmsFakeBundle'), array_keys($bundles));
    }

    public function testABundleDelaredInSeveralAutoloadersIsInstantiatedOnce()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/redkite-cms/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/';
        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "RedKiteLabs\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["all"]' . PHP_EOL;
        $autoload .= '        }' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle', $autoload);

        $bundleFolder = 'root/vendor/redkite-cms/app-business-dropcap-bundle/RedKiteLabs/Block/BusinessDropCapFakeBundle/';
        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "RedKiteLabs\\\\Block\\\\BusinessDropCapFakeBundle\\\\BusinessDropCapFakeBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["all"]' . PHP_EOL;
        $autoload .= '        },' . PHP_EOL;
        $autoload .= '        "RedKiteLabs\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["dev"]' . PHP_EOL;
        $autoload .= '        }' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessDropCapFakeBundle', $autoload);

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $this->scriptFactory);
        $bundles = $bundlesAutoloader->getBundles();
        $this->assertCount(2, $bundles);
        $this->assertEquals(array('BusinessCarouselFakeBundle', 'BusinessDropCapFakeBundle'), array_keys($bundles));
    }

    public function testABundleDelaredInSeveralAutoloadersWithoutAutoloaderIsInstantiatedOnce()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/redkite-cms/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/';
        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "RedKiteLabs\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["all"]' . PHP_EOL;
        $autoload .= '        },' . PHP_EOL;
        $autoload .= '        "RedKiteLabs\\\\RedKiteLabsCms\\\\RedKiteLabsCmsFakeBundle\\\\RedKiteLabsCmsFakeBundle" : ""' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle', $autoload);

        $bundleFolder = 'root/vendor/redkite-cms/app-business-dropcap-bundle/RedKiteLabs/Block/BusinessDropCapFakeBundle/';
        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "RedKiteLabs\\\\Block\\\\BusinessDropCapFakeBundle\\\\BusinessDropCapFakeBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["all"]' . PHP_EOL;
        $autoload .= '        },' . PHP_EOL;
        $autoload .= '        "RedKiteLabs\\\\RedKiteLabsCms\\\\RedKiteLabsCmsFakeBundle\\\\RedKiteLabsCmsFakeBundle" : ""' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessDropCapFakeBundle', $autoload);

        $bundleFolder = 'root/vendor/redkite-cms/redkite-cms-cms-bundle/RedKiteLabs/RedKiteLabsCms/RedKiteLabsCmsFakeBundle/';
        $this->createBundle($bundleFolder, 'RedKiteLabsCmsFakeBundle', false, 'RedKiteLabs\RedKiteLabsCms');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'prod', array(), $this->scriptFactory);
        $bundles = $bundlesAutoloader->getBundles();
        $this->assertCount(3, $bundles);
        $this->assertEquals(array('BusinessCarouselFakeBundle', 'RedKiteLabsCmsFakeBundle', 'BusinessDropCapFakeBundle'), array_keys($bundles));
    }

    public function testUninstall()
    {
        $this->createAutoloadNamespacesFile();

        // Autoloads a bundle
        $bundleFolder = 'root/vendor/redkite-cms/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle');
        $configFolder = $bundleFolder . 'Resources/config';
        $this->createFolder($configFolder);
        $this->createFile($configFolder . '/config.yml');
        $this->createFile($configFolder . '/routing.yml');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $this->scriptFactory);
        $this->assertCount(1, $bundlesAutoloader->getBundles());
        $this->assertFileExists(vfsStream::url('root/app/config/bundles/autoloaders/businesscarouselfake.json'));
        $this->assertFileExists(vfsStream::url('root/app/config/bundles/config/dev/businesscarouselfake.yml'));
        $this->assertFileExists(vfsStream::url('root/app/config/bundles/routing/businesscarouselfake.yml'));

        // Next call
        $fs = new \Symfony\Component\Filesystem\Filesystem();
        $fs->remove(vfsStream::url($bundleFolder));

        $this->addClassManager('root/vendor/redkite-cms/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/Core/ActionManager/', 'ActionManagerBusinessCarousel.php', 'BusinessCarouselFakeBundle');

        $script = $this->getMock('RedKiteLabs\BootstrapBundle\Core\Script\ScriptInterface');
        $script->expects($this->exactly(2))
            ->method('executeActions');

        $scriptFactory = $this->getMock('RedKiteLabs\BootstrapBundle\Core\Script\Factory\ScriptFactoryInterface');
        $scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($script));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $scriptFactory);
        $this->assertCount(0, $bundlesAutoloader->getBundles());
        $this->assertFileNotExists(vfsStream::url('root/app/config/bundles/autoloaders/businesscarouselfake.json'));
        $this->assertFileNotExists(vfsStream::url('root/app/config/bundles/config/dev/businesscarouselfake.yml'));
        $this->assertFileNotExists(vfsStream::url('root/app/config/bundles/routing/businesscarouselfake.yml'));
    }
    
    public function testBundlesSavedUnderTheStandardPathAreAutoloaded()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/src/RedKiteCms/Block/BusinessSliderFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessSliderFakeBundle');
        
        $bundleFolder = 'root/vendor/redkite-cms/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $this->scriptFactory);        
        $this->assertCount(2, $bundlesAutoloader->getBundles());
        
        $this->assertFileExists(vfsStream::url('root/app/config/bundles/autoloaders/businesscarouselfake.json'));
    }
    
    public function testBundlesNotSavedUnderTheStandardPathAreNotAutoloaded()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/src/Acme/Blocks/BusinessMenuFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessMenuFakeBundle');
        
        $bundleFolder = 'root/vendor/redkite-cms/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $this->scriptFactory);
        $this->assertCount(1, $bundlesAutoloader->getBundles());
        
        $this->assertFileNotExists(vfsStream::url('root/app/config/bundles/autoloaders/businessmenufake.json'));
    }
    
    public function testOverridesTheStandardPath()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/src/Acme/Blocks/BusinessMenuFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessMenuFakeBundle');
        
        $bundleFolder = 'root/vendor/redkite-cms/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $this->scriptFactory, array(vfsStream::url('root/src/Acme/Blocks')));
        $this->assertCount(2, $bundlesAutoloader->getBundles());        
        $this->assertFileExists(vfsStream::url('root/app/config/bundles/autoloaders/businessmenufakebundle.json'));
    }

    private function createScript()
    {
        $script = $this->getMock('RedKiteLabs\BootstrapBundle\Core\Script\ScriptInterface');
        $script->expects($this->exactly(2))
            ->method('executeActions');

        return $script;
    }
}