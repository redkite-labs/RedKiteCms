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

        //$this->bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $this->scriptFactory);
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
    
    
    public function testFoldersHaveBeenCreated()
    {
        //$this->createAutoloadNamespacesFile();
        
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

        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle');

        $bundleFolder = 'root/vendor/alphalemon/app-business-dropcap-bundle/AlphaLemon/Block/BusinessDropCapFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessDropCapFakeBundle', false); // This bundle has any autoload.json file

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $this->scriptFactory);        
        $this->assertEquals(1, count($bundlesAutoloader->getBundles()));
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/autoloaders/businesscarouselfake.json')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/config/dev/businesscarouselfake.yml')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/routing/businesscarouselfake.yml')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/autoloaders/businessdropcap.json')));
    }

    public function testOnlyBundlesWithAnAutoloadFileAreAutoloaded1()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselFakeBundle/';

        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["all"]' . PHP_EOL;
        $autoload .= '        }' . PHP_EOL;
        $autoload .= '    },' . PHP_EOL;
        $autoload .= '    "actionManager" : "\\\\AlphaLemon\\\\Block\\\\BusinessCarouselFakeBundle\\\\Core\\\\ActionManager\\\\ActionManagerBusinessCarousel"';
        $autoload .= '}';

        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle', $autoload);
        $this->addClassManager('root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselFakeBundle/Core/ActionManager/', 'ActionManagerBusinessCarousel.php', 'BusinessCarouselFakeBundle');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $this->scriptFactory);        
        $this->assertEquals(1, count($bundlesAutoloader->getBundles()));
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/autoloaders/businesscarouselfake.json')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/config/dev/businesscarouselfake.yml')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/routing/businesscarouselfake.yml')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/autoloaders/businessdropcap.json')));
    }

    public function testConfigAndRoutingFilesAreCopiedToRespectiveFolders()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselFakeBundle/';
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

        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/autoloaders/businesscarouselfake.json')));
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/config/dev/businesscarouselfake.yml')));
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/routing/businesscarouselfake.yml')));
    }

    /**
     * @expectedException \AlphaLemon\BootstrapBundle\Core\Exception\InvalidAutoloaderException
     * @expectedExceptionMessage The bundle class AlphaLemon\Block\BusinessCarouselFakeBundle\BusinessCarousellBundle does not exist. Check the bundle's autoload.json to fix the problem
     */
    public function testAnExceptionIsThrownWhenTheClassGIvenInTheAutoloaderHasNotBeenFound()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselFakeBundle/';

        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarousellBundle" : {' . PHP_EOL;
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

        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselFakeBundle/';

        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
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

        $this->assertEquals(0, count($bundlesAutoloader->getBundles()));
    }

    public function testInstallationByEnvironmentWithMoreBundles()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselFakeBundle/';

        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["dev"],' . PHP_EOL;
        $autoload .= '           "overrides" : ["BusinessDropCapFakeBundle"]' . PHP_EOL;
        $autoload .= '        }' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle', $autoload);

        $bundleFolder = 'root/vendor/alphalemon/app-business-dropcap-bundle/AlphaLemon/Block/BusinessDropCapFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessDropCapFakeBundle');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'prod', array(), $this->scriptFactory);
        $bundlesAutoloader->getBundles();

        $this->assertEquals(1, count($bundlesAutoloader->getBundles()));
    }

    public function testAnOverridedBundleIsPlacedAfterTheOneHowOverrideIt()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselFakeBundle/';

        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["all"],' . PHP_EOL;
        $autoload .= '           "overrides" : ["BusinessDropCapFakeBundle"]' . PHP_EOL;
        $autoload .= '        }' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle', $autoload);

        $bundleFolder = 'root/vendor/alphalemon/app-business-dropcap-bundle/AlphaLemon/Block/BusinessDropCapFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessDropCapFakeBundle');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'prod', array(), $this->scriptFactory);
        $bundles = $bundlesAutoloader->getBundles();
        $this->assertEquals(2, count($bundles));
        $this->assertEquals(array('BusinessDropCapFakeBundle', 'BusinessCarouselFakeBundle'), array_keys($bundles));
    }
    
    public function testAutoloadingABundleWithoutAutoloader()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselFakeBundle/';
        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["all"]' . PHP_EOL;
        $autoload .= '        },' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\AlphaLemonCms\\\\AlphaLemonCmsFakeBundle\\\\AlphaLemonCmsFakeBundle" : ""' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle', $autoload);
                
        $bundleFolder = 'root/vendor/alphalemon/alphalemon-cms-bundle/AlphaLemon/AlphaLemonCms/AlphaLemonCmsFakeBundle/';
        $this->createBundle($bundleFolder, 'AlphaLemonCmsFakeBundle', false, 'AlphaLemon\AlphaLemonCms');
        
        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'prod', array(), $this->scriptFactory);
        $bundles = $bundlesAutoloader->getBundles();
        $this->assertEquals(2, count($bundles));
        $this->assertEquals(array('BusinessCarouselFakeBundle', 'AlphaLemonCmsFakeBundle'), array_keys($bundles));
    }
    
    public function testABundleDelaredInSeveralAutoloadersIsInstantiatedOnce()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselFakeBundle/';
        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["all"]' . PHP_EOL;
        $autoload .= '        }' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle', $autoload);

        $bundleFolder = 'root/vendor/alphalemon/app-business-dropcap-bundle/AlphaLemon/Block/BusinessDropCapFakeBundle/';
        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\Block\\\\BusinessDropCapFakeBundle\\\\BusinessDropCapFakeBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["all"]' . PHP_EOL;     
        $autoload .= '        },' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
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
        $this->assertEquals(2, count($bundles));
        $this->assertEquals(array('BusinessCarouselFakeBundle', 'BusinessDropCapFakeBundle'), array_keys($bundles));
    }
    
    public function testABundleDelaredInSeveralAutoloadersWithoutAutoloaderIsInstantiatedOnce()
    {
        $this->createAutoloadNamespacesFile();

        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselFakeBundle/';
        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["all"]' . PHP_EOL;
        $autoload .= '        },' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\AlphaLemonCms\\\\AlphaLemonCmsFakeBundle\\\\AlphaLemonCmsFakeBundle" : ""' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle', $autoload);

        $bundleFolder = 'root/vendor/alphalemon/app-business-dropcap-bundle/AlphaLemon/Block/BusinessDropCapFakeBundle/';
        $autoload = '{' . PHP_EOL;
        $autoload .= '    "bundles" : {' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\Block\\\\BusinessDropCapFakeBundle\\\\BusinessDropCapFakeBundle" : {' . PHP_EOL;
        $autoload .= '           "environments" : ["all"]' . PHP_EOL;     
        $autoload .= '        },' . PHP_EOL;
        $autoload .= '        "AlphaLemon\\\\AlphaLemonCms\\\\AlphaLemonCmsFakeBundle\\\\AlphaLemonCmsFakeBundle" : ""' . PHP_EOL;
        $autoload .= '    }' . PHP_EOL;
        $autoload .= '}';
        $this->createBundle($bundleFolder, 'BusinessDropCapFakeBundle', $autoload);
                
        $bundleFolder = 'root/vendor/alphalemon/alphalemon-cms-bundle/AlphaLemon/AlphaLemonCms/AlphaLemonCmsFakeBundle/';
        $this->createBundle($bundleFolder, 'AlphaLemonCmsFakeBundle', false, 'AlphaLemon\AlphaLemonCms');
        
        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'prod', array(), $this->scriptFactory);
        $bundles = $bundlesAutoloader->getBundles();
        $this->assertEquals(3, count($bundles));
        $this->assertEquals(array('BusinessCarouselFakeBundle', 'AlphaLemonCmsFakeBundle', 'BusinessDropCapFakeBundle'), array_keys($bundles));
    }

    public function testUninstall()
    {
        $this->createAutoloadNamespacesFile();

        // Autoloads a bundle
        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle');
        $configFolder = $bundleFolder . 'Resources/config';
        $this->createFolder($configFolder);
        $this->createFile($configFolder . '/config.yml');
        $this->createFile($configFolder . '/routing.yml');

        $this->scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($this->createScript()));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $this->scriptFactory);        
        $this->assertEquals(1, count($bundlesAutoloader->getBundles()));
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/autoloaders/businesscarouselfake.json')));
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/config/dev/businesscarouselfake.yml')));
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/routing/businesscarouselfake.yml')));

        // Next call
        $fs = new \Symfony\Component\Filesystem\Filesystem();
        $fs->remove(vfsStream::url($bundleFolder));

        $this->addClassManager('root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselFakeBundle/Core/ActionManager/', 'ActionManagerBusinessCarousel.php', 'BusinessCarouselFakeBundle');

        $script = $this->getMock('AlphaLemon\BootstrapBundle\Core\Script\ScriptInterface');
        $script->expects($this->exactly(2))
            ->method('executeActions');

        $scriptFactory = $this->getMock('AlphaLemon\BootstrapBundle\Core\Script\Factory\ScriptFactoryInterface');
        $scriptFactory->expects($this->exactly(2))
            ->method('createScript')
            ->will($this->returnValue($script));

        $bundlesAutoloader = new BundlesAutoloader(vfsStream::url('app'), 'dev', array(), $scriptFactory);
        $this->assertEquals(0, count($bundlesAutoloader->getBundles()));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/autoloaders/businesscarouselfake.json')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/config/dev/businesscarouselfake.yml')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/routing/businesscarouselfake.yml')));
    }

    protected function createAutoloadNamespacesFile($autoloadNamespaces = null)
    {
        if(null === $autoloadNamespaces) {
            $autoloadNamespaces = '<?php' . PHP_EOL;
            $autoloadNamespaces .= '$vendorDir = dirname(__DIR__);' . PHP_EOL;
            $autoloadNamespaces .= '$baseDir = dirname($vendorDir);' . PHP_EOL;
            $autoloadNamespaces .= 'return array(' . PHP_EOL;
            $autoloadNamespaces .= '    \'AlphaLemon\\Block\\BusinessCarouselFakeBundle\' => $vendorDir . \'/alphalemon/app-business-carousel-bundle/\',' . PHP_EOL;
            $autoloadNamespaces .= '    \'AlphaLemon\\Block\\BusinessDropCapFakeBundle\' => $vendorDir . \'/alphalemon/app-business-dropcap-bundle/\',' . PHP_EOL;
            $autoloadNamespaces .= '    \'AlphaLemon\\AlphaLemonCms\\AlphaLemonCmsFakeBundle\' => $vendorDir . \'/alphalemon/alphalemon-cms-bundle/\',' . PHP_EOL;
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