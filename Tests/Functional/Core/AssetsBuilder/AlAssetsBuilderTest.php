<?php
/*
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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core;


use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\AssetsBuilder\AlAssetsBuilder;
use Symfony\Component\HttpKernel\Util\Filesystem;


class AlAssetsBuilderTest extends TestCase
{
    private static $resourcesFolder = "ResourcesTest";
    private static $resourcesPath = null;
    private $testAlAssetsBuilder;
    
    protected function setUp()
    {
        parent::setUp();
        
        self::$resourcesPath = $this->getContainer()->getParameter('kernel.root_dir') . '/../vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/' . self::$resourcesFolder;

        if(!is_dir(self::$resourcesPath))
        {
            $fs = new Filesystem();
            $fs->mkdir(self::$resourcesPath);
        }
        
        
        $this->testAlAssetsBuilder = new AlAssetsBuilder(
            $this->getContainer()
        );
        $this->testAlAssetsBuilder->setOutputFolder(self::$resourcesFolder);
        $this->testAlAssetsBuilder->setOutputBundle('AlphaLemonCmsBundle');
    }
    
    public static function tearDownAfterClass()
    {
        $fileSystem = new Filesystem();
        $fileSystem->remove(self::$resourcesPath);
    }
    
    public function testAssetsAreEmptyWhenAssetsBUilderIsInitialized()
    {
        $this->assertEmpty($this->testAlAssetsBuilder->getAssets(), 'A new instance of AssetsBuilder is expecting an empty array of assets');
    }
    
    public function testAddAssets()
    {
        $this->testAlAssetsBuilder->addAssets(array('fake'));
        $this->assertEquals(1, count($this->testAlAssetsBuilder->getAssets()), '->addAssets() method has not added an asset');
        
        return $this->testAlAssetsBuilder;
    }
    
    /**
     * @depends testAddAssets
     */
    public function testAnExistingAssetIsNotAdded($testAlAssetsBuilder)
    {
        $testAlAssetsBuilder->addAssets(array('fake'));
        $this->assertEquals(1, count($testAlAssetsBuilder->getAssets()), '->addAssets() method has added an asset that had already added');
        
        return $testAlAssetsBuilder;
    }
    
    /**
     * @depends testAnExistingAssetIsNotAdded
     */
    public function testCleanAssets($testAlAssetsBuilder)
    {
        $testAlAssetsBuilder->cleanAssets();
        $this->assertEmpty($testAlAssetsBuilder->getAssets(), '->cleanAssets() has not cleaned the stored assets');
    }
    
        
    public function testAddAssetsForAnEntireFolder()
    {
        $this->testAlAssetsBuilder->addAssets(array('bundles/alphalemoncms/js/*'));
        $this->assertNotEmpty($this->testAlAssetsBuilder->getAssets(), '->addAssets() has not added one or more assets as expected');
        
        return $this->testAlAssetsBuilder;
    }
    
    /**
     * @depends testAddAssetsForAnEntireFolder
     */
    public function testAnExistingAssetIsNotAddedNeverless($testAlAssetsBuilder)
    {
        $assets = $testAlAssetsBuilder->getAssets();
        $assetsCount = count($assets);
        $testAlAssetsBuilder->addAssets(array('bundles/alphalemoncms/js/alphalemon.js'));
        $this->assertEquals($assetsCount, count($testAlAssetsBuilder->getAssets()), '->addAssets() method has added an asset that had already added');
        $testAlAssetsBuilder->addAssets(array('bundles/alphalemoncms/js/*'));
        $this->assertEquals($assetsCount, count($testAlAssetsBuilder->getAssets()), '->addAssets() method has added an asset that had already added');  
    }
    
    
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWritingAssetsFileWhenOutputBundleIsNullRaisesException()
    {
        $this->testAlAssetsBuilder->setOutputBundle(null);
        $this->testAlAssetsBuilder->writeAssetFile('none', 'temp');
    }
    
    public function testWritingAssetsFileWithRawAsset()
    {
        $contents = $this->writeAssetFile(array('fake'));
        $this->assertRegExp('/\'fake\'/', $contents);
    }
    
    public function testWritingAssetsFileWithRelativeAsset()
    {
        $contents = $this->writeAssetFile(array('@AlphaLemonCmsBundle/Resources/public/js/vendor/jquery/jquery-1.6.1.min.js'));
        $this->assertRegExp('/\'bundles\/alphalemoncms\/js\/vendor\/jquery\/jquery-1\.6\.1.min.js\'/', $contents, 'The asset path has not been normalized as expected when a valid relative path has been given');
        
    }
   
    public function testWritingAssetsFileWithAbsoluteAsset()
    {
        $contents = $this->writeAssetFile(array($this->getContainer()->getParameter('kernel.root_dir') . '/../vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Resources/public/js/vendor/jquery/jquery-1.6.1.min.js'));
        $this->assertRegExp('/\'bundles\/alphalemoncms\/js\/vendor\/jquery\/jquery-1\.6\.1.min.js\'/', $contents, 'The asset path has not been normalized as expected when a valid absolute path has been given');
        
        return $this->testAlAssetsBuilder;
    }
    
    private function writeAssetFile(array $assets, $testAlAssetsBuilder = null)
    {
        $assetsFile = "temp.twig.html";
        $assetsFilePath = self::$resourcesPath  . "/" .  $assetsFile;
        if(is_file($assetsFilePath)) unlink($assetsFilePath); 
     
        if(null === $testAlAssetsBuilder) $testAlAssetsBuilder = $this->testAlAssetsBuilder;
        $testAlAssetsBuilder->addAssets($assets);
        $testAlAssetsBuilder->writeAssetFile('stylesheets_skeleton', $assetsFile);
        $this->assertFileExists($assetsFilePath, 'The twig assets file has not been created as expected');
        
        return file_get_contents($assetsFilePath);
    }
    
    /**
     * @depends testWritingAssetsFileWithAbsoluteAsset
     */
    public function testAddingAnotherAssetAsset($testAlAssetsBuilder)
    {
        $contents = $this->writeAssetFile(array('fake'), $testAlAssetsBuilder);
        $this->assertRegExp('/\'bundles\/alphalemoncms\/js\/vendor\/jquery\/jquery-1\.6\.1.min.js\' \'fake\'/', $contents, 'The assets path has not been normalized as expected when mixed paths has been given');
    }
}