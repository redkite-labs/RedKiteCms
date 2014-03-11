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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\PageTree\TemplateAssetsManager;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\TemplateAssetsManager\TemplateAssetsManagerDeploy;

/**
 * TemplateAssetsManagerDeployTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class TemplateAssetsManagerDeployTest extends TemplateAssetsManagerBase
{
    protected $container;
    private $assetsCollections = array();
    
    /**
     * @dataProvider templateAssetsManagerProvider
     */
    public function testSetUpTemplateAssetsManager($assetCollections, $listenersAssets, $containerAssets, $availableBlocks, $blockTypes, $expectedResult, $options = array())
    {
        $this->assetsCollections = $assetCollections;
        $container = $this->initContainer($listenersAssets, $containerAssets);
        $template = $this->createTemplate($assetCollections);
        $blockManagerFactory = $this->initBlockManagerFactory($availableBlocks);
        $pageBlocks = $this->initPageBlocks($blockTypes);
        
        
        $templateAssetsManager = new TemplateAssetsManagerDeploy($container, $blockManagerFactory);
        $templateAssetsManager->setPageBlocks($pageBlocks);
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
                        "bootstrapsliderblock.external_stylesheets" => array(
                            'style3.css',
                            'style4.css',
                        ),
                        "bootstrapsliderblock.external_stylesheets.cms" => array(
                            'style8.css',
                        ),        
                    ),
                ),
                array(
                    "BootstrapSliderBlock",
                    "Menu",
                ),
                array(
                    "BootstrapSliderBlock",
                ),
                array(
                    "externalStylesheets" => array("style.css", "style1.css", "style2.css", "style7.css", "style3.css", "style4.css", "style8.css"),
                    "externalJavascripts" => array(),
                    "internalStylesheets" => array(),
                    "internalJavascripts" => array(),
                ),
            ),
        );
    }
    
    private function initPageBlocks($blockTypes)
    {
        $pageBlocks = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocks')
                    ->disableOriginalConstructor()
                    ->getMock()
        ;
        
        $pageBlocks->expects($this->once())
            ->method('getBlockTypes')
            ->will($this->returnValue($blockTypes))
        ;
        
        return $pageBlocks;
    }
}