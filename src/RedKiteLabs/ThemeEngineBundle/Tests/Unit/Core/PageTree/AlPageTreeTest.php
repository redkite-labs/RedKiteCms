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

namespace AlphaLemon\ThemeEngineBundle\Tests\Unit\Core\PageTree;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\PageTree\AlPageTree;

/**
 * AlPageTreeTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlPageTreeTest extends TestCase
{
    private $container;
    private $template;
    private $pageBlocks;

    protected function setUp()
    {
        parent::setUp();

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->template = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate')
                                    ->disableOriginalConstructor()
                                    ->setMethods(
                                        array(
                                            'getExternalStylesheets', 
                                            'getInternalStylesheets', 
                                            'getExternalJavascripts', 
                                            'getInternalJavascripts'
                                        )
                                    )                
                                    ->getMock();

        $this->pageBlocks = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocks')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->pageTree = new AlPageTree($this->container, $this->pageBlocks);
        $this->pageTree->setTemplate($this->template);
    }

    public function testExternalStylesheets()
    {
        $assets = array('theme-stylesheet.css');
        $assetsCollection = $this->setUpAssetsCollection($assets);
        
        $this->template->expects($this->once())
            ->method('getExternalStylesheets')
            ->will($this->returnValue($assetsCollection));

        $this->pageBlocks->expects($this->once())
            ->method('getBlocks')
            ->will($this->returnValue(array()));

        $this->assertEquals($assets, $this->pageTree->getExternalStylesheets());
    }
    
    public function testInternalStylesheets()
    {
        $assets = array('an-awesome-styleshet');
        $assetsCollection = $this->setUpAssetsCollection($assets);
        
        $this->template->expects($this->once())
            ->method('getInternalStylesheets')
            ->will($this->returnValue($assetsCollection));

        $this->pageBlocks->expects($this->once())
            ->method('getBlocks')
            ->will($this->returnValue(array()));

        $this->assertEquals($assets[0], $this->pageTree->getInternalStylesheets());
    }
    public function testExternalJavascripts()
    {
        $assets = array('theme-javascript.js');
        $assetsCollection = $this->setUpAssetsCollection($assets);
        
        $this->template->expects($this->once())
            ->method('getExternalJavascripts')
            ->will($this->returnValue($assetsCollection));

        $this->pageBlocks->expects($this->once())
            ->method('getBlocks')
            ->will($this->returnValue(array()));

        $this->assertEquals($assets, $this->pageTree->getExternalJavascripts());
    }
    
    public function testInternalJavascripts()
    {
        $assets = array('an-awesome-javascript');
        $assetsCollection = $this->setUpAssetsCollection($assets);
        
        $this->template->expects($this->once())
            ->method('getInternalJavascripts')
            ->will($this->returnValue($assetsCollection));

        $this->pageBlocks->expects($this->once())
            ->method('getBlocks')
            ->will($this->returnValue(array()));

        $this->assertEquals($assets[0], $this->pageTree->getInternalJavascripts());
    }
    
    public function testMetatags()
    {
        $metas = array(
            'title' => 'An awesome page title',
            'description' => 'An awesome page description',
            'keywords' => 'some,awesome,keywords',
        );
        
        $this->pageTree->setMetatags($metas);
        $this->assertEquals($metas['title'], $this->pageTree->getMetaTitle());
        $this->assertEquals($metas['description'], $this->pageTree->getMetaDescription());
        $this->assertEquals($metas['keywords'], $this->pageTree->getMetaKeywords());
    }
    
    public function testPageBlocks()
    {
        $pageBlock = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocksInterface');
        $this->assertEquals($this->pageTree, $this->pageTree->setPageBlocks($pageBlock));
        $this->assertEquals($pageBlock, $this->pageTree->getPageBlocks());
    }
    
    public function testTemplate()
    {
        $template = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate')
                         ->disableOriginalConstructor()
                         ->getMock();
        $this->assertEquals($this->pageTree, $this->pageTree->setTemplate($template));
        $this->assertEquals($template, $this->pageTree->getTemplate());
    }
    
    private function setUpAssetsCollection(array $storedAssets)
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $assetsCollection = new \AlphaLemon\ThemeEngineBundle\Core\Asset\AlAssetCollection($kernel, $storedAssets);
                
        return $assetsCollection;
    }

}