<?php
/*
 * This file is part of the RedKiteLabsBootstrapBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://redkite-labs.com
 *
 * @license    MIT License
 */

namespace RedKiteLabs\BootstrapBundle\Tests\Unit\Autoloader;

use RedKiteLabs\BootstrapBundle\Tests\Unit\Base\BaseFilesystem;
use RedKiteLabs\BootstrapBundle\Core\Json\JsonAutoloaderCollection;
use org\bovigo\vfs\vfsStream;

/**
 * BundlesAutoloaderTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class JsonAutoloaderCollectionTest extends BaseFilesystem
{
    /**
     * @expectedException \RedKiteLabs\BootstrapBundle\Core\Exception\InvalidProjectException
     * @expectedExceptionMessage "composer" folder has not been found. Be sure to use this bundle on a project managed by Composer
     */
    public function testAnExceptionIsThrownWhenTheComposerFolderDoesNotExist()
    {
        $this->initFilesystem(array('app' => array()));

        $autoloaderCollection = new JsonAutoloaderCollection(vfsStream::url('root/vendor/composer'));
    }

    public function testOnlyABundleWithAutoloadFileIsAutoloaded()
    {
        $this->initFilesystem(array('vendor/composer' => array()));
        $this->createAutoloadNamespacesFile();
        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle');

        $bundleFolder = 'root/vendor/alphalemon/app-business-dropcap-bundle/RedKiteLabs/Block/BusinessDropCapFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessDropCapFakeBundle', false);

        $autoloaderCollection = new JsonAutoloaderCollection(vfsStream::url('root/vendor'));
        $this->assertEquals(1, $autoloaderCollection->count());
    }

    public function testAllBundlesAreLoaded()
    {
        $this->initFilesystem(array('vendor/composer' => array()));
        $this->createAutoloadNamespacesFile();
        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle');

        $bundleFolder = 'root/vendor/alphalemon/app-business-dropcap-bundle/RedKiteLabs/Block/BusinessDropCapFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessDropCapFakeBundle');

        $bundleFolder = 'root/vendor/alphalemon/alphalemon-cms-bundle/RedKiteLabs/RedKiteLabsCms/RedKiteLabsCmsFakeBundle/';
        $this->createBundle($bundleFolder, 'RedKiteLabsCmsFakeBundle');

        $autoloaderCollection = new JsonAutoloaderCollection(vfsStream::url('root/vendor'));
        $this->assertEquals(3, $autoloaderCollection->count());
    }

    private function initFilesystem(array $folders)
    {
        $this->root = vfsStream::setup('root', null, $folders);
    }
}