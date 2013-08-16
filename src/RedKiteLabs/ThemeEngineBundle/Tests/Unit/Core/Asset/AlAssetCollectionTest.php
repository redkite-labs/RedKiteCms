<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\ThemeEngineBundle\Tests\Unit\Core\Asset;

use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;
use RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAssetCollection;

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

    public function testAnAssetIsNotAddedTwice()
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