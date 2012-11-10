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
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;

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
    
    public function testTheResourceCannotBeLocated()
    {
        $asset = '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css';        
        $this->kernel
             ->expects($this->once())
             ->method('locateResource')
             ->will($this->throwException(new \RuntimeException()))
        ;

        $alAsset = new AlAsset($this->kernel, $asset);
        $this->assertEquals($asset, $alAsset->getAsset());
        $this->assertEquals('@BusinessWebsiteThemeBundle/Resources/public/css/reset.css', $alAsset->getRealPath());
    }
    
    /*
    public function testComposerNamespace()
    {
        $root = vfsStream::setup('root', null, array('app' => array(), 'vendor' => array('composer' => array())));
        $autoloadNamespaces = '<?php' . PHP_EOL;
        $autoloadNamespaces .= '$vendorDir = dirname(__DIR__);' . PHP_EOL;
        $autoloadNamespaces .= '$baseDir = dirname($vendorDir);' . PHP_EOL;
        $autoloadNamespaces .= 'return array(' . PHP_EOL;
        $autoloadNamespaces .= '    \'AlphaLemon\\Theme\\BusinessWebsiteThemeBundle\' => $vendorDir . \'/alphalemon/business-website-theme-bundle/\',' . PHP_EOL;
        $autoloadNamespaces .= ');' . PHP_EOL;
        file_put_contents(vfsStream::url('root/vendor/composer/autoload_namespaces.php'), $autoloadNamespaces);
        
        //print_R(vfsStream::inspect(new vfsStreamStructureVisitor())->getStructure());exit;
        
        $asset = '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css';
        $asset = 'vfs://root/app/../vendor/alphalemon/business-website-theme-bundle/pippo.jpg';
        $bundleAssetPath = '/path/to/bundle/folder';
        $this->setUpKernel($bundleAssetPath);
        
        $this->kernel
             ->expects($this->exactly(2))
             ->method('getRootDir')
             ->will($this->returnValue(vfsStream::url('root/app')))
        ;

        
        
        $alAsset = new AlAsset($this->kernel, $asset);
        $this->assertEquals($asset, $alAsset->getAsset());
        $this->assertEquals($bundleAssetPath . '/Resources/public/css/reset.css', $alAsset->getRealPath());
        $this->assertEquals('bundles/businesswebsitetheme/css/reset.css', $alAsset->getAbsolutePath());
        $this->assertEquals('/path/to/kernel/root/dir/../web/bundles/businesswebsitetheme/css/reset.css', $alAsset->getWebFolderRealPath());        
    }*/

    public function testAssetPathAreCalculatedFromARelativePath()
    {
        $asset = '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css';
        $bundleAssetPath = '/path/to/bundle/folder';
        $this->setUpKernel($bundleAssetPath);
        
        $this->kernel
             ->expects($this->exactly(2))
             ->method('getRootDir')
             ->will($this->returnValue('/path/to/kernel/root/dir'))
        ;

        $alAsset = new AlAsset($this->kernel, $asset);
        $this->assertEquals($asset, $alAsset->getAsset());
        $this->assertEquals($bundleAssetPath . '/Resources/public/css/reset.css', $alAsset->getRealPath());
        $this->assertEquals('bundles/businesswebsitetheme/css/reset.css', $alAsset->getAbsolutePath());
        $this->assertEquals('/path/to/kernel/root/dir/../web/bundles/businesswebsitetheme/css/reset.css', $alAsset->getWebFolderRealPath());        
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