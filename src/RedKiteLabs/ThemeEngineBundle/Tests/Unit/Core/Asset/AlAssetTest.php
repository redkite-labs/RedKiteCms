<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\ThemeEngineBundle\Tests\Unit\Core\Asset;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\Asset\AlAsset;

/**
 * AlAssetTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlAssetTest extends TestCase
{
    private $kernel;

    protected function setUp()
    {
        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
    }

    public function testANullOrBlankAssetDidNothing()
    {
        $alAsset = new AlAsset($this->kernel, null);
        $this->assertNull($alAsset->getRealPath());
        $this->assertNull($alAsset->getAbsolutePath());

        $alAsset = new AlAsset($this->kernel, "");
        $this->assertNull($alAsset->getRealPath());
        $this->assertNull($alAsset->getAbsolutePath());
    }

    public function testANullAbsolutePathIsCalculateWhenAssetPointsToANonStandardSymfony2Path()
    {
        $asset = '/path/to/asset/asset.js';
        $alAsset = new AlAsset($this->kernel, $asset);
        $this->assertEquals($asset, $alAsset->getAsset());
        $this->assertEquals($asset, $alAsset->getRealPath());
        $this->assertNull($alAsset->getAbsolutePath());
    }

    public function testAssetPathAreCalculatedFromARelativePath()
    {
        $asset = '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css';
        $bundleAssetPath = '/path/to/bundle/folder';
        $this->setUpKernel($bundleAssetPath);

        $alAsset = new AlAsset($this->kernel, $asset);
        $this->assertEquals($asset, $alAsset->getAsset());
        $this->assertEquals($bundleAssetPath . '/Resources/public/css/reset.css', $alAsset->getRealPath());
        $this->assertEquals('bundles/businesswebsitetheme/css/reset.css', $alAsset->getAbsolutePath());
    }

    public function testAssetPathAreCalculatedFromARealPath()
    {
        $asset = '/path/to/web/folder/bundles/businesswebsitetheme/css/style.css';
        $this->setUpKernel($asset, 0);

        $alAsset = new AlAsset($this->kernel, $asset);
        $this->assertEquals($asset, $alAsset->getAsset());
        $this->assertEquals($asset, $alAsset->getRealPath());
        $this->assertEquals('bundles/businesswebsitetheme/css/style.css', $alAsset->getAbsolutePath());
    }

    public function testAssetPathsAreAlwaysNormalized()
    {
        $asset = '\\path\\to\\web\\folder\\bundles\\businesswebsitetheme\\css\\style.css';
        $normalizedAsset = '/path/to/web/folder/bundles/businesswebsitetheme/css/style.css';
        $this->setUpKernel($normalizedAsset, 0);

        $alAsset = new AlAsset($this->kernel, $asset);
        $this->assertEquals($normalizedAsset, $alAsset->getAsset());
        $this->assertEquals($normalizedAsset, $alAsset->getRealPath());
        $this->assertEquals('bundles/businesswebsitetheme/css/style.css', $alAsset->getAbsolutePath());
    }

    public function testAssetIsRecognizedAsBundle()
    {
        $asset = 'FakeBundle';
        $this->setUpKernel($asset);

        $alAsset = new AlAsset($this->kernel, $asset);
        $this->assertEquals($asset, $alAsset->getRealPath());
        $this->assertEquals('bundles/fake', $alAsset->getAbsolutePath());
    }

    private function setUpKernel($asset, $numberOfCalls = 1)
    {
        $this->kernel->expects($this->exactly($numberOfCalls))
            ->method('locateResource')
            ->will($this->returnValue($asset));
    }
}