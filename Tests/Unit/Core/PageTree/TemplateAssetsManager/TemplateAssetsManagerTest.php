<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\PageTree\TemplateAssetsManager;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\PageTree\TemplateAssetsManager\TemplateAssetsManager;
use RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAssetCollection;

/**
 * TemplateAssetsManagerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class TemplateAssetsManagerTest extends TestCase
{
    protected $container;
    private $assetsCollections = array();
    private $assetCollectionAt;

    protected function setUp()
    {
        parent::setUp();
        
        
    }
    
    /**
     * @dataProvider templateAssetsManagerProvider
     */
    public function testSetUpTemplateAssetsManager($assetCollections, $listenersAssets, $containerAssets, $availableBlocks, $expectedResult, $options = array())
    {
        $this->assetsCollections = $assetCollections;
        $container = $this->initContainer($listenersAssets, $containerAssets);
        $template = $this->createTemplate($assetCollections);
        $blockManagerFactory = $this->initBlockManagerFactory($availableBlocks);
        
        $templateAssetsManager = new TemplateAssetsManager($container, $blockManagerFactory);
        $templateAssetsManager->setUp($template, $options);
        $this->assertEquals($expectedResult["externalStylesheets"], $templateAssetsManager->getExternalStylesheets());
        $this->assertEquals($expectedResult["externalJavascripts"], $templateAssetsManager->getExternalJavascripts());
        $this->assertEquals($expectedResult["internalStylesheets"], $templateAssetsManager->getInternalStylesheets());
        $this->assertEquals($expectedResult["internalJavascripts"], $templateAssetsManager->getInternalJavascripts());
    }
    
    public function templateAssetsManagerProvider()
    {       
        return array(
            array(
                array(
                    'getExternalStylesheets' => $this->createAssetsCollection(array(
                        'style.css',
                    )),
                    'getExternalJavascripts' => $this->createAssetsCollection(array(
                        'script.js',
                    )),
                    'getInternalStylesheets' => $this->createAssetsCollection(array(
                        '.foo{bar}',
                    )),
                    'getInternalJavascripts' => $this->createAssetsCollection(array(
                        'function foo(){bar}',
                    )),
                ),
                array(
                ),
                array(
                ),
                array(
                ),
                array(
                    "externalStylesheets" => array("style.css"),
                    "externalJavascripts" => array("script.js"),
                    "internalStylesheets" => array(".foo{bar}"),
                    "internalJavascripts" => array("function foo(){bar}"),
                )
            ),
            array(
                array(
                    'getExternalStylesheets' => $this->createAssetsCollection(array(
                        'style.css',
                    )),
                    'getExternalJavascripts' => $this->createAssetsCollection(array(
                        'script.js',
                    )),
                    'getInternalStylesheets' => $this->createAssetsCollection(array(
                        '.foo{bar}',
                    )),
                    'getInternalJavascripts' => $this->createAssetsCollection(array(
                        'function foo(){bar}',
                    )),
                ),
                array(
                ),
                array(
                    'getExternalStylesheets' => array(
                        'bootbusiness_theme.home.external_stylesheets.cms' => array(
                            'style1.css',
                        ),  
                    ),
                    'getExternalJavascripts' => array(
                        'bootbusiness_theme.home.external_javascripts.cms' => array(
                            'script1.js',
                        ),  
                    ),
                    'getInternalStylesheets' => array(
                        'bootbusiness_theme.home.internal_stylesheets.cms' => array(
                            '.bar{foo}',
                        ),  
                    ),
                    'getInternalJavascripts' => array(
                        'bootbusiness_theme.home.internal_javascripts.cms' => array(
                            'function bar(){foo}',
                        ),  
                    ),
                ),
                array(
                ),
                array(
                    "externalStylesheets" => array("style.css", "style1.css"),
                    "externalJavascripts" => array("script.js", "script1.js"),
                    "internalStylesheets" => array(".foo{bar}", ".bar{foo}"),
                    "internalJavascripts" => array("function foo(){bar}", "function bar(){foo}"),
                )
            ),
            array(
                array(
                    'getExternalStylesheets' => $this->createAssetsCollection(array(
                        'style.css',
                        'style1.css',
                    )),
                ),
                array(
                    "acme_web_site.download_listener",
                ),
                array(
                    'getExternalStylesheets' => array(
                        'bootbusiness_theme.home.external_stylesheets.cms' => array(
                            'style2.css',
                        ),
                        "acme_web_site.download_listener.page.external_stylesheets" => array(
                            'style7.css',
                        ), 
                        "bootstrapsliderblock.external_stylesheets" => array(
                            'style3.css',
                            'style4.css',
                        ),
                        "bootstrapsliderblock.external_stylesheets.cms" => array(),
                        "menu.external_stylesheets" => array(
                            'style5.css',
                            'style6.css',
                        ),
                        "menu.external_stylesheets.cms" => array(),              
                    ),
                ),
                array(
                    "BootstrapSliderBlock",
                    "Menu",
                ),
                array(
                    "externalStylesheets" => array("style.css", "style1.css", "style2.css", "style7.css", "style3.css", "style4.css", "style5.css", "style6.css"),
                    "externalJavascripts" => array(),
                    "internalStylesheets" => array(),
                    "internalJavascripts" => array(),
                ),
            ),
            array(
                array(
                    'getExternalStylesheets' => $this->createAssetsCollection(array(
                        'style.css',
                        'style1.css',
                    )),
                ),
                array(
                    "acme_web_site.download_listener",
                ),
                array(
                    'getExternalStylesheets' => array(
                        'bootbusiness_theme.home.external_stylesheets.cms' => array(
                            'style2.css',
                        ),
                        "acme_web_site.download_listener.page.external_stylesheets" => array(
                            'style7.css',
                        ), 
                        "bootstrapsliderblock.external_stylesheets" => array(
                            'style3.css',
                            'style4.css',
                        ),
                        "bootstrapsliderblock.external_stylesheets.cms" => array(
                            'style8.css',
                        ),
                        "menu.external_stylesheets" => array(
                            'style5.css',
                            'style6.css',
                        ),
                        "menu.external_stylesheets.cms" => array(
                            'style9.css',                            
                            'style6.css',  // This already exists so it is not added again
                        ),              
                    ),
                ),
                array(
                    "BootstrapSliderBlock",
                    "Menu",
                ),
                array(
                    "externalStylesheets" => array("style.css", "style1.css", "style2.css", "style7.css", "style3.css", "style4.css", "style8.css", "style5.css", "style6.css", "style9.css"),
                    "externalJavascripts" => array(),
                    "internalStylesheets" => array(),
                    "internalJavascripts" => array(),
                ),
            ),
            array(
                array(
                    'getExternalStylesheets' => $this->createAssetsCollection(array(
                        'style.css',
                        'style1.css',
                    )),
                ),
                array(
                    "acme_web_site.download_listener",
                ),
                array(
                    'getExternalStylesheets' => array(
                        'bootbusiness_theme.home.external_stylesheets.cms' => array(
                            'style2.css',
                        ),
                        "acme_web_site.download_listener.page.external_stylesheets" => array(
                            'style7.css',
                        ), 
                        "acme_web_site.download_listener.en.external_stylesheets" => array(
                            'style5.css',
                        ), 
                        "acme_web_site.download_listener.index.external_stylesheets" => array(
                            'style6.css',
                        ), 
                        "bootstrapsliderblock.external_stylesheets" => array(
                            'style3.css',
                            'style4.css',
                        ),
                        "bootstrapsliderblock.external_stylesheets.cms" => array(),
                    ),
                ),
                array(
                    "BootstrapSliderBlock",
                    "Menu",
                ),
                array(
                    "externalStylesheets" => array("style.css", "style1.css", "style2.css", "style7.css", "style5.css", "style6.css", "style3.css", "style4.css"),
                    "externalJavascripts" => array(),
                    "internalStylesheets" => array(),
                    "internalJavascripts" => array(),
                ),
                array(
                    'language' => 'en',
                    'page' => 'index'
                ),
            ),
        );
    }
    
    private function initBlockManagerFactory($availableBlocks)
    {
        $blockManagerFactory = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactory')
                    ->disableOriginalConstructor()
                    ->getMock()
        ;
        
        $blockManagerFactory->expects($this->once())
            ->method('getAvailableBlocks')
            ->will($this->returnValue($availableBlocks))
        ;
        
        return $blockManagerFactory;
    }

    private function initContainer($listenersAssets, $containerAssets)
    {
        $container = $this->getMock("Symfony\Component\DependencyInjection\ContainerInterface");        
        $container->expects($this->at(0))
            ->method('get')
            ->with('red_kite_labs_theme_engine.registed_listeners')
            ->will($this->returnValue($this->createListenersCollection($listenersAssets)))
        ;
        
        $at = 1;
        foreach($containerAssets as $assetsCollectionMethod => $assets) {
            foreach($assets as $parameter => $parameterAssets) {
                $container->expects($this->at($at))
                    ->method('hasParameter')
                    //->with($parameter)
                    ->will($this->returnValue(true))
                ;
                $at++;
                
                $container->expects($this->at($at))
                    ->method('getParameter')
                    //->with($parameter)
                    ->will($this->returnValue($parameterAssets))
                ;
                $at++;
            }
        }
        
        return $container;
    }
    
    private function createListenersCollection($assets)
    {
        $listenersCollection = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Rendering\Compiler\ThemeEngineListenersCollection\AlThemeEngineListenersCollection')
                    ->disableOriginalConstructor()
                    ->getMock()
        ;
        
        $at = 1;
        foreach($assets as $asset) {
            $listenersCollection->expects($this->at($at))
                 ->method('valid')
                 ->will($this->returnValue(true));
            $at++;
            
            $listenersCollection->expects($this->at($at))
                 ->method('current')
                 ->will($this->returnValue($asset));
            $at++;
        }
        
        return $listenersCollection;
    }
    
    private function createAssetsCollection($assets)
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');        
        $assetsCollection = new AlAssetCollection($kernel, $assets);
        
        return $assetsCollection;
    }
    
    private function createAsset($file)
    {
        $asset = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAsset')
                    ->disableOriginalConstructor()
                    ->getMock()
        ;
        
        $asset->expects($this->once())
            ->method('getAbsolutePath')
            ->will($this->returnValue('/asset/absolute/path'))
        ;
        
        $asset->expects($this->once())
            ->method('getAsset')
            ->will($this->returnValue($file))
        ;
        
        return $asset;
    }
    
    private function createTemplate($assetCollections)
    {
        $template = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate')
                    ->setMethods(array('getExternalStylesheets', 'getInternalStylesheets', 'getExternalJavascripts', 'getInternalJavascripts', 'getThemeName', 'getTemplateName', 'getSlots'))
                    ->disableOriginalConstructor()
                    ->getMock()
        ;
        
        $at = 0;
        foreach($assetCollections as $method => $assetCollection) {
            $template->expects($this->at($at))
                ->method($method)
                ->will($this->returnValue($assetCollection))
            ;
            
            $at += 3;
        }
        
        $template->expects($this->any())
            ->method("getThemeName")
            ->will($this->returnValue("BootbusinessThemeBundle"))
        ;
        
        $template->expects($this->any())
            ->method("getTemplateName")
            ->will($this->returnValue("home"))
        ;
        
        return $template;
    }
    
    private function createBlock($slotName)
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        
        $block->expects($this->once())
            ->method('getSlotName')
            ->will($this->returnValue($slotName))
        ;
        /*
        // Following statemes are declared just for the test and not for the SUT
        $block->expects($this->once())
            ->method('getHtml')
            ->will($this->returnValue($html))
        ;
        
        $block->expects($this->once())
            ->method('getMetaTags')
            ->will($this->returnValue($metatags))
        ;*/
        
        return $block;
    }
}