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
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\ThemeEngineBundle\Tests\Unit\Core\PageTree;

use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;
use RedKiteLabs\ThemeEngineBundle\Core\PageTree\AlPageTree;

/**
 * AlPageTreeTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
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
        $this->template = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate')
                                    ->disableOriginalConstructor()
                                    ->setMethods(
                                        array(
                                            'addExternalStylesheet',
                                            'addExternalStylesheets',
                                            'addExternalJavascript',
                                            'addExternalJavascripts',
                                            'getExternalStylesheets', 
                                            'getInternalStylesheets', 
                                            'getExternalJavascripts', 
                                            'getInternalJavascripts'
                                        )
                                    )                
                                    ->getMock();

        $this->pageBlocks = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocks')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->pageTree = new AlPageTree($this->container, $this->pageBlocks);
        $this->pageTree->setTemplate($this->template);
    }
   
    /**
     * @expectedException \RuntimeException
     */
    public function testAnExceptionIsThrowsWhenCalledMethodDoesNotExist()
    {
        $this->pageTree->fake();
    }
    
    public function testGetNotInitializedAssets()
    {
        $this->assertEmpty($this->pageTree->getExternalStylesheets());
    }
    
    /**
     * @dataProvider getAssetsProvider
     */
    public function testGetAssets($assets, $method, $blockAssets, $result)
    {
        $assetsCollection = $this->setUpAssetsCollection($assets);
        
        $this->template->expects($this->once())
            ->method($method)
            ->will($this->returnValue($assetsCollection));

        $this->pageBlocks->expects($this->once())
            ->method('getBlocks')
            ->will($this->returnValue($blockAssets));

        $this->assertEquals($result, $this->pageTree->$method());
    }
    
    public function getAssetsProvider()
    {
        return array(
            array(
                array('theme-stylesheet.css'),
                'getExternalStylesheets',
                array(),
                array('theme-stylesheet.css'),
            ),
            array(
                array('some stylesheets code'),
                'getInternalStylesheets',
                array(),
                'some stylesheets code',
            ),
            array(
                array('theme-javascript.js'),
                'getExternalJavascripts',
                array(),
                array('theme-javascript.js'),
            ),
            array(
                array('some javascript code'),
                'getInternalJavascripts',
                array(),
                'some javascript code',
            ),
        );
    }
    
    /**
     * @dataProvider addAssetsProvider
     */
    public function testAddAssets($assets, $method, $result = null)
    {
        if (null === $result) {
            $result = $assets;
        }
        
        $this->template->expects($this->once())
            ->method($method)
            ->with($assets);

        $this->pageTree->$method($result);
    }
    
    public function addAssetsProvider()
    {
        return array(            
            array(
                array('theme-stylesheet.css'),
                'addExternalStylesheet',
            ),
            array(
                array('theme-javascript.js'),
                'addExternalJavascript',
            ),
            array(
                array(array('theme-stylesheet.css','another-stylesheet.css')),
                'addExternalStylesheets',
                array('theme-stylesheet.css','another-stylesheet.css'),
            ),
            array(
                array(array('theme-javascript.js','another-javascript.js')),
                'addExternalJavascripts',
                array('theme-javascript.js','another-javascript.js'),
            ),
        );
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
        
        $title = "another title";
        $this->pageTree->setMetaTitle($title);
        $this->assertEquals($title, $this->pageTree->getMetaTitle());
        
        $desription = "another description";
        $this->pageTree->setMetaDescription($desription);
        $this->assertEquals($desription, $this->pageTree->getMetaDescription());
        
        $keywords = "another,keyword";
        $this->pageTree->setMetaKeywords($keywords);
        $this->assertEquals($keywords, $this->pageTree->getMetaKeywords());
    }
    
    public function testPageBlocks()
    {
        $pageBlock = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocksInterface');
        $this->assertEquals($this->pageTree, $this->pageTree->setPageBlocks($pageBlock));
        $this->assertEquals($pageBlock, $this->pageTree->getPageBlocks());
    }
    
    public function testTemplate()
    {
        $template = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate')
                         ->disableOriginalConstructor()
                         ->getMock();
        $this->assertEquals($this->pageTree, $this->pageTree->setTemplate($template));
        $this->assertEquals($template, $this->pageTree->getTemplate());
    }
    
    private function setUpAssetsCollection(array $storedAssets)
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $assetsCollection = new \RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAssetCollection($kernel, $storedAssets);
                
        return $assetsCollection;
    }
}