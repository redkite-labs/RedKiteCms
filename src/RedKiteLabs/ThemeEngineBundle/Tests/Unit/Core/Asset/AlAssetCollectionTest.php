<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
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

namespace RedKiteLabs\ThemeEngineBundle\Tests\Unit\Core\Asset;

use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;
use RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAssetCollection;
use org\bovigo\vfs\vfsStream;

/**
 * AlAssetTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlAssetCollectionTest extends TestCase
{
    private $kernel;

    protected function setUp()
    {
        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
    }
    
    public function testAnEmptyCollectionIsInstantiated()
    {
        $alAssetCollection = new AlAssetCollection($this->kernel);
        $this->assertEquals(0, count($alAssetCollection));
    }

    public function testAnythingIsAddedWhenGivenParamIsNullOrBlank()
    {
        $alAssetCollection = new AlAssetCollection($this->kernel);
        $alAssetCollection->add(null);
        $this->assertEquals(0, count($alAssetCollection));
        $alAssetCollection->add('');
        $this->assertEquals(0, count($alAssetCollection));
    }

    public function testAPopulateCollectionIsInstantiated()
    {
        $alAssetCollection = new AlAssetCollection($this->kernel, array('@BusinessWebsiteThemeBundle/Resources/public/css/reset.css'));
        $this->assertEquals(1, count($alAssetCollection));
    }

    public function testAnAssetIsAdded()
    {
        $alAssetCollection = new AlAssetCollection($this->kernel);
        $alAssetCollection->add('@BusinessWebsiteThemeBundle/Resources/public/css/reset.css');
        $this->assertEquals(1, count($alAssetCollection));
    }
    
    public function testAddAllFolderAssets()
    {
        $this->root = vfsStream::setup('root', null, array(
            'app' => array(),
            'foo' => array(
                'public' => array(
                    'asset1.js' => '',
                    'asset2.js' => '',
                ),
            ),
        ));
        
        $asset = '@FakeBundle/foo/public/*';
        
        $this->kernel->expects($this->any())
            ->method('locateResource')
            ->will($this->returnValue(vfsStream::url('root')))
        ;
        
        $alAssetCollection = new AlAssetCollection($this->kernel);
        $alAssetCollection->add($asset);
        $this->assertEquals(2, count($alAssetCollection));
    }
    
    public function testAssetIsNotAddedTwiceWithRealFiles()
    {
        $this->root = vfsStream::setup('root', null, array(
            'app' => array(),
            'foo' => array(
                'public' => array(
                    'asset.js' => '',
                ),
            ),
        ));
        
        $asset = '@FakeBundle/foo/public/asset.js';
        
        $this->kernel->expects($this->any())
            ->method('locateResource')
            ->will($this->returnValue(vfsStream::url('root')))
        ;
        
        $alAssetCollection = new AlAssetCollection($this->kernel);
        $alAssetCollection->add($asset);
        $alAssetCollection->add($asset);
        $this->assertEquals(1, count($alAssetCollection));
    }

    public function testAnAssetIsNotAddedTwiceVirtualFiles()
    {
        $alAssetCollection = new AlAssetCollection($this->kernel);
        $alAssetCollection->add('@BusinessWebsiteThemeBundle/Resources/public/css/reset.css');
        $this->assertEquals(1, count($alAssetCollection));
        $alAssetCollection->add('@BusinessWebsiteThemeBundle/Resources/public/css/reset.css');
        $this->assertEquals(1, count($alAssetCollection));
    }

    public function testARangeOfAssetsIsAdded()
    {
        $alAssetCollection = new AlAssetCollection($this->kernel);
        $alAssetCollection->addRange(array('@BusinessWebsiteThemeBundle/Resources/public/css/reset.css',
            '@BusinessWebsiteThemeBundle/Resources/public/css/style.css'));
        $this->assertEquals(2, count($alAssetCollection));
    }

    public function testAddRangeDoesNotAddAnExistingAssetTwice()
    {
        $alAssetCollection = new AlAssetCollection($this->kernel);
        $alAssetCollection->addRange(array('@BusinessWebsiteThemeBundle/Resources/public/css/reset.css',
            '@BusinessWebsiteThemeBundle/Resources/public/css/style.css',
            '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css',));
        $this->assertEquals(2, count($alAssetCollection));
    }
    
    public function testTheAssetDoesNotExistInTheCollectionSoAnythingIsRemoved()
    {
        $alAssetCollection = new AlAssetCollection($this->kernel);
        $alAssetCollection->add('@BusinessWebsiteThemeBundle/Resources/public/css/reset.css');
        $this->assertEquals(1, count($alAssetCollection));
        $alAssetCollection->remove('style.css');
        $this->assertEquals(1, count($alAssetCollection));
    }

    public function testAssetIsRemovedFromTheRelativePath()
    {
        $alAssetCollection = new AlAssetCollection($this->kernel);
        $alAssetCollection->addRange(
            array(
                '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css',
                '@BusinessWebsiteThemeBundle/Resources/public/css/style.css',
            )
        );
        $this->assertEquals(2, count($alAssetCollection));
        $alAssetCollection->remove('@BusinessWebsiteThemeBundle/Resources/public/css/reset.css');
        $this->assertEquals(1, count($alAssetCollection));
    }

    public function testAssetIsRemovedFromTheAssetName()
    {
        $alAssetCollection = new AlAssetCollection($this->kernel);
        $alAssetCollection->addRange(
            array(
                '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css',
                '@BusinessWebsiteThemeBundle/Resources/public/css/style.css',
            )
        );
        $this->assertEquals(2, count($alAssetCollection));return;
        $alAssetCollection->remove('style.css');
        $this->assertEquals(1, count($alAssetCollection));
    }
}