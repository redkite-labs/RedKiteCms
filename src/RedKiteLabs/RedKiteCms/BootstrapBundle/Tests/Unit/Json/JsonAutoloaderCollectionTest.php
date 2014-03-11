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

namespace RedKiteLabs\RedKiteCms\BootstrapBundle\Tests\Unit\Autoloader;

use RedKiteLabs\RedKiteCms\BootstrapBundle\Tests\Unit\Base\BaseFilesystem;
use RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Json\JsonAutoloaderCollection;
use org\bovigo\vfs\vfsStream;

/**
 * BundlesAutoloaderTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class JsonAutoloaderCollectionTest extends BaseFilesystem
{
    /**
     * @expectedException \RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Exception\InvalidProjectException
     * @expectedExceptionMessage "composer" folder has not been found. Be sure to use this bundle on a project managed by Composer
     */
    public function testAnExceptionIsThrownWhenTheComposerFolderDoesNotExist()
    {
        $this->initFilesystem(array('app' => array()));

        $autoloaderCollection = new JsonAutoloaderCollection(vfsStream::url('root/vendor/composer'));
    }

    /**
     * @dataProvider bundlesProvider
     */
    public function testOnlyABundleWithAutoloadFileIsAutoloaded($bundles)
    {
        $this->initFilesystem(array('vendor/composer' => array()));
        $this->createAutoloadNamespacesFile();
        
        foreach($bundles as $bundle) {
            $this->createBundle($bundle["bundleFolder"], $bundle["bundleName"], $bundle["autoload"]);
        }

        $autoloaderCollection = new JsonAutoloaderCollection(vfsStream::url('root/vendor'));
        $this->assertEquals(1, $autoloaderCollection->count());
    }
    
    public function bundlesProvider()
    {
        return array(
            array(
                array(
                    array(
                        'bundleFolder' => 'root/vendor/redkite-cms/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/',
                        'bundleName' => 'BusinessCarouselFakeBundle',
                        'autoload' => null,
                    ),    
                ),
                array(
                    array(
                        'bundleFolder' => 'root/vendor/redkite-cms/app-business-dropcap-bundle/RedKiteLabs/Block/BusinessDropCapFakeBundle/',
                        'bundleName' => 'BusinessDropCapFakeBundle',
                        'autoload' => false,
                    ),
                ),
            ),
            array(
                array(
                    array(
                        'bundleFolder' => 'root/vendor/redkite-cms/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/',
                        'bundleName' => 'BusinessCarouselFakeBundle',
                        'autoload' => null,
                    ),    
                ),
                array(
                    array(
                        'bundleFolder' => 'root/vendor/redkite-cms/app-business-dropcap-bundle/RedKiteLabs/Block/BusinessDropCapFakeBundle',
                        'bundleName' => 'BusinessDropCapFakeBundle',
                        'autoload' => null,
                    ),
                ),
                array(
                    array(
                        'bundleFolder' => 'root/vendor/redkite-cms/redkite-cms-cms-bundle/RedKiteLabs/RedKiteLabsCms/RedKiteLabsCmsFakeBundle/',
                        'bundleName' => 'RedKiteLabsCmsFakeBundle',
                        'autoload' => null,
                    ),
                ),
            ),
        );
    }

    private function initFilesystem(array $folders)
    {
        $this->root = vfsStream::setup('root', null, $folders);
    }
}