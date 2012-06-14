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

namespace AlphaLemon\ThemeEngineBundle\Tests\Unit\Core\Asset;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplateAssets;

/**
 * AlTemplateAssetTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlTemplateAssetTest extends TestCase
{    
    private $container;
    
    protected function setUp() 
    {
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->templateAssets = new AlTemplateAssets($this->container);
    }
    
    /**
     * @expectedException \AlphaLemon\ThemeEngineBundle\Core\Exception\General\InvalidParameterException
     */
    public function testThemeNameThrowsAnExceptionWhenANullValueIsGiven()
    {
        $this->templateAssets->setThemeName(null);
    }
    
    /**
     * @expectedException \AlphaLemon\ThemeEngineBundle\Core\Exception\General\InvalidParameterException
     */
    public function testThemeNameThrowsAnExceptionWhenANonStringValueIsGiven()
    {
        $this->templateAssets->setThemeName(3);
    }
    
    /**
     * @expectedException \AlphaLemon\ThemeEngineBundle\Core\Exception\General\InvalidParameterException
     */
    public function testTemplateNameThrowsAnExceptionWhenANullValueIsGiven()
    {
        $this->templateAssets->setTemplateName(null);
    }
    
    /**
     * @expectedException \AlphaLemon\ThemeEngineBundle\Core\Exception\General\InvalidParameterException
     */
    public function testTemplateNameThrowsAnExceptionWhenANonStringValueIsGiven()
    {
        $this->templateAssets->setTemplateName(array('fake'));
    }
    
    public function testAssetsAreNotRetrievedJustValorizingTheThemeName()
    {
        $this->templateAssets->setThemeName('BusinessWebsiteThemeBundle');
        $this->assertNull($this->templateAssets->getExternalStylesheets());
        $this->assertFalse($this->templateAssets->isBootstrapped());
    }
    
    public function testAssetsAreNotRetrievedJustValorizingTheTemplateName()
    {
        $this->templateAssets->setTemplateName('Home');
        $this->assertNull($this->templateAssets->getExternalStylesheets());
        $this->assertFalse($this->templateAssets->isBootstrapped());
    }
    
    public function testAssetsHaveBeenSetted()
    {
        $this->templateAssets
                ->setThemeName('BusinessWebsiteThemeBundle')
                ->setTemplateName('Home');
        $this->assertTrue(count($this->templateAssets->getExternalStylesheets()) == 0);
        $this->assertTrue($this->templateAssets->isBootstrapped());
    }
    
    public function testJustExternalStylesheetsHaveBeenSetted()
    {
        $assets = array('@BusinessWebsiteThemeBundle/Resources/public/css/reset.css');
        
        $this->container->expects($this->any())
            ->method('hasParameter')
            ->will($this->onConsecutiveCalls(true, false, false, false));
        
        $this->container->expects($this->any())
            ->method('getParameter')
            ->will($this->returnValue($assets));
        
        $this->templateAssets
                ->setThemeName('BusinessWebsiteThemeBundle')
                ->setTemplateName('Home');
        $this->assertEquals($assets, $this->templateAssets->getExternalStylesheets());        
        $this->assertTrue(count($this->templateAssets->getExternalStylesheets()) == 1);        
        $this->assertTrue(count($this->templateAssets->getInternalStylesheets()) == 0);        
        $this->assertTrue(count($this->templateAssets->getExternalJavascripts()) == 0);
        $this->assertTrue(count($this->templateAssets->getInternalJavascripts()) == 0);
    }
    
    
    public function testValorizedAllAssets()
    {
        $externalStylesheets = array('@BusinessWebsiteThemeBundle/Resources/public/css/reset.css');
        $internalStylesheets = array('Fake style');
        $externalJavascripts = array('@BusinessWebsiteThemeBundle/Resources/public/js/reset.js');
        $internalJavascripts = array('Fake code');
        
        $this->container->expects($this->any())
            ->method('hasParameter')
            ->will($this->returnValue(true));
        
        $this->container->expects($this->any())
            ->method('getParameter')
            ->will($this->onConsecutiveCalls($externalStylesheets,
                    $internalStylesheets,
                    $externalJavascripts,
                    $internalJavascripts));
        
        $this->templateAssets
                ->setThemeName('BusinessWebsiteThemeBundle')
                ->setTemplateName('Home');
        $this->assertEquals($externalStylesheets, $this->templateAssets->getExternalStylesheets());  
        $this->assertEquals($internalStylesheets, $this->templateAssets->getInternalStylesheets());  
        $this->assertEquals($externalJavascripts, $this->templateAssets->getExternalJavascripts());  
        $this->assertEquals($internalJavascripts, $this->templateAssets->getInternalJavascripts());  
        
        return $this->templateAssets;
    }
    
    /**
     *@depends testValorizedAllAssets
     */
    public function testAssetsAreSettedWithADirectCall($templateAssets)
    {
        $values = array('other stylesheets');        
        $templateAssets->setExternalStylesheetsRange($values);
        $this->assertEquals($values, $templateAssets->getExternalStylesheets());  
    }
    
    
    /**
     *@depends testValorizedAllAssets
     */
    public function testSomeAssetsAreAddedToAnExistingCollection($templateAssets)
    {
        $savedAssets = $templateAssets->getExternalStylesheets();
        $values = array('other stylesheets');        
        $templateAssets->addExternalStylesheetsRange($values);
        $this->assertEquals(array_merge($savedAssets, $values), $templateAssets->getExternalStylesheets());  
    }
}