<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\PageTree\TemplateAssetsManager;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\TemplateAssetsManager\TemplateAssetsManager;
use RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAssetCollection;

/**
 * TemplateAssetsManagerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class TemplateAssetsManagerTest extends TemplateAssetsManagerBase
{
    protected $container;
    private $assetsCollections = array();
    
    public function testEmptyObject()
    {
        $container = $this->getMock("Symfony\Component\DependencyInjection\ContainerInterface");
        $blockManagerFactory = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactory')
                    ->disableOriginalConstructor()
                    ->getMock()
        ;
        $templateAssetsManager = new TemplateAssetsManager($container, $blockManagerFactory);
        $this->assertNull($templateAssetsManager->getExternalStylesheets());
    }
    
    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage TemplateAssetsManager does not support the method: fakeMethod
     */
    public function testCallingNonExistentMethod()
    {
        $assetCollections = array(
            'getExternalStylesheets' => $this->createAssetsCollection(array(
                'style.css',
            )),
        );
        
        $this->assetsCollections = $assetCollections;
        $container = $this->initContainer(array(), array());
        $template = $this->createTemplate($assetCollections);
        $blockManagerFactory = $this->initBlockManagerFactory(array());
        
        $templateAssetsManager = new TemplateAssetsManager($container, $blockManagerFactory);
        $templateAssetsManager->setUp($template, array());
        $templateAssetsManager->fakeMethod();
    }

    /**
     * @dataProvider templateAssetsManagerProvider
     */
    public function testSetUpTemplateAssetsManager($assetCollections, $listenersAssets, $containerAssets, $availableBlocks, $expectedResult, $options = array(), $extraAssets = true)
    {
        $this->assetsCollections = $assetCollections;
        $container = $this->initContainer($listenersAssets, $containerAssets);
        $template = $this->createTemplate($assetCollections);
        $blockManagerFactory = $this->initBlockManagerFactory($availableBlocks);
        
        $templateAssetsManager = new TemplateAssetsManager($container, $blockManagerFactory);
        $templateAssetsManager->withExtraAssets($extraAssets);
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
                ),
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
                ),
                array(
                ),
                array(
                    "externalStylesheets" => array("style.css"),
                    "externalJavascripts" => array("script.js"),
                    "internalStylesheets" => array(".foo{bar}"),
                    "internalJavascripts" => array("function foo(){bar}"),
                ),
                array(
                ),
                false,
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
}