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

use AlphaLemon\BootstrapBundle\Tests\Unit\Base\BaseFilesystem;
use AlphaLemon\BootstrapBundle\Core\Json\JsonAutoloaderCollection;
use org\bovigo\vfs\vfsStream;

/**
 * BundlesAutoloaderTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class JsonAutoloaderCollectionTest extends BaseFilesystem
{
    /**
     * @expectedException \AlphaLemon\BootstrapBundle\Core\Exception\InvalidProjectException
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
        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle');

        $bundleFolder = 'root/vendor/alphalemon/app-business-dropcap-bundle/AlphaLemon/Block/BusinessDropCapFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessDropCapFakeBundle', false);

        $autoloaderCollection = new JsonAutoloaderCollection(vfsStream::url('root/vendor'));
        $this->assertEquals(1, $autoloaderCollection->count());
    }

    public function testAllBundlesAreLoaded()
    {
        $this->initFilesystem(array('vendor/composer' => array()));
        $this->createAutoloadNamespacesFile();
        $bundleFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessCarouselFakeBundle');

        $bundleFolder = 'root/vendor/alphalemon/app-business-dropcap-bundle/AlphaLemon/Block/BusinessDropCapFakeBundle/';
        $this->createBundle($bundleFolder, 'BusinessDropCapFakeBundle');

        $bundleFolder = 'root/vendor/alphalemon/alphalemon-cms-bundle/AlphaLemon/AlphaLemonCms/AlphaLemonCmsFakeBundle/';
        $this->createBundle($bundleFolder, 'AlphaLemonCmsFakeBundle');

        $autoloaderCollection = new JsonAutoloaderCollection(vfsStream::url('root/vendor'));
        $this->assertEquals(3, $autoloaderCollection->count());
    }

    private function initFilesystem(array $folders)
    {
        $this->root = vfsStream::setup('root', null, $folders);
    }
}