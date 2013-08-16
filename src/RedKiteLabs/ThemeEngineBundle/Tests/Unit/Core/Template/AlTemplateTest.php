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
use RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate;

/**
 * AlTemplateTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlTemplateTest extends TestCase
{
    private $templateAssets;
    private $kernel;
    private $templateSlots;

    protected function setUp()
    {
        $this->templateAssets = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplateAssets');      
        
        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $this->templateSlots = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface');
    }

    public function testBlockManagerFactoryInjectedBySetters()
    {        
        $this->initAssets();
        $templateSlots = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface');  
        
        $this->template = new AlTemplate($this->kernel, $this->templateAssets, $this->templateSlots);           
        $this->assertEquals($this->template, $this->template->setTemplateSlots($templateSlots));
        $this->assertEquals($templateSlots, $this->template->getTemplateSlots());
        $this->assertNotSame($this->template, $this->template->getTemplateSlots());
    }
    
    public function testSetThemeAndTemplateName()
    {
        $themeName = "BusinessWebsiteThemeBundle";
        $templateName = "home";
        
        $this->templateAssets->expects($this->once())
            ->method('setThemeName')
            ->with($themeName);

        $this->templateAssets->expects($this->once())
            ->method('setTemplateName')
            ->with($templateName);
        
        $this->templateAssets->expects($this->once())
            ->method('getThemeName')
            ->will($this->returnValue($themeName));
        
        $this->templateAssets->expects($this->once())
            ->method('getTemplateName')
            ->will($this->returnValue($templateName));
        
        $this->initAssets();
        $this->template = new AlTemplate($this->kernel, $this->templateAssets, $this->templateSlots);
        $this->template
                ->setThemeName($themeName)
                ->setTemplateName($templateName);         
        
        $this->assertEquals($themeName, $this->template->getThemeName());
        $this->assertEquals($templateName, $this->template->getTemplateName());
    }

    public function testTemplateHasBeenPopulatedWithEmptyAssets()
    {
        $this->initAssets();
        $this->template = new AlTemplate($this->kernel, $this->templateAssets, $this->templateSlots); 
        
        $this->verifyAssets(0);
    }
    
    public function testTemplateHasBeenPopulatedWithSomeAssets()
    {
        $this->initTemplateWithSomeAssets();
        $this->template = new AlTemplate($this->kernel, $this->templateAssets, $this->templateSlots);   
        
        $this->verifyAssets(1);
    }

    public function testTemplateSlotsReturnsAnEmptyArrayWhenTheTemplateSlotIsNotInitialized()
    {
        $this->initTemplateWithSomeAssets();        
        $this->template = new AlTemplate($this->kernel, $this->templateAssets, $this->templateSlots);   

        $this->assertTrue(count($this->template->getSlots()) == 0);
        $this->assertTrue(count($this->template->getSlot('logo')) == 0);
    }

    public function testFetchesTemplateSlots()
    {
        $this->initTemplateWithSomeAssets();
        $slot = array('repeated' => 'site');
        $slots = array('logo' => $slot);
        
        $this->templateSlots->expects($this->once())
            ->method('getSlots')
            ->will($this->returnValue($slots));

        $this->templateSlots->expects($this->once())
            ->method('getSlot')
            ->will($this->returnValue($slot));

        $this->template = new AlTemplate($this->kernel, $this->templateAssets, $this->templateSlots);           
        
        $this->assertEquals($slots, $this->template->getSlots());
        $this->assertEquals($slot, $this->template->getSlot('logo'));
    }
    
    public function testAddAnAsset()
    {
        $this->initAssets();
        $this->template = new AlTemplate($this->kernel, $this->templateAssets, $this->templateSlots);      
        $this->assertCount(0, $this->template->getExternalStylesheets());
        $this->template->addExternalStylesheet('temp.css');
        $this->assertCount(1, $this->template->getExternalStylesheets());
    }
    
    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage addExternalJavascripts method requires an array as argument, string given
     */
    public function testAddARangeOfAssetThrowsAnExceptionWhenTheGivenArgumentIsNotAnArray()
    {
        $this->initAssets();
        $this->template = new AlTemplate($this->kernel, $this->templateAssets, $this->templateSlots);      
        $this->template->addExternalJavascripts('temp.js');
    }
    
    public function testAddARangeOfAsset()
    {
        $this->initAssets();
        $this->template = new AlTemplate($this->kernel, $this->templateAssets, $this->templateSlots);      
        $this->assertCount(0, $this->template->getExternalJavascripts());
        $this->template->addExternalJavascripts(array('temp.js', 'temp1.js'));
        $this->assertCount(2, $this->template->getExternalJavascripts());
    }
    
    public function testSetAssetsCollection()
    {
        $this->initAssets();
        $this->template = new AlTemplate($this->kernel, $this->templateAssets, $this->templateSlots);      
        $this->assertCount(0, $this->template->getExternalJavascripts());        
        
        $assetsCollection = clone($this->template->getExternalJavascripts());
        $assetsCollection->add('temp.js');
        $this->assertCount(0, $this->template->getExternalJavascripts());      
        
        $this->template->setExternalJavascripts($assetsCollection);
        $this->assertCount(1, $this->template->getExternalJavascripts());
    }
    
    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Call to undefined method: getExternalImages
     */
    public function testCallAnUndefinedMethod()
    {
        $this->initAssets();
        $this->template = new AlTemplate($this->kernel, $this->templateAssets, $this->templateSlots);      
        $this->template->getExternalImages();
    }

    private function initTemplateWithSomeAssets()
    {
        $this->initAssets(
                array('@BusinessWebsiteThemeBundle/Resources/public/css/reset.css'),
                array('Fake style'),
                array('@BusinessWebsiteThemeBundle/Resources/public/js/reset.js'),
                array('Fake code')
        );
    }
    
    private function initAssets($externalStylesheets = array(), $internalStylesheets = array(), $externalJavascripts = array(), $internalJavascripts = array())
    {
        $this->templateAssets->expects($this->exactly(4))
            ->method('__call')
            ->will($this->onConsecutiveCalls(
                    $externalStylesheets,
                    $internalStylesheets,
                    $externalJavascripts,
                    $internalJavascripts));
    }

    private function verifyAssets($expectedElements)
    {
        $this->assertTrue(count($this->template->getExternalStylesheets()) == $expectedElements);
        $this->assertTrue(count($this->template->getInternalStylesheets()) == $expectedElements);
        $this->assertTrue(count($this->template->getExternalJavascripts()) == $expectedElements);
        $this->assertTrue(count($this->template->getInternalJavascripts()) == $expectedElements);
        $this->assertInstanceOf('\RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAssetsCollectionInterface', $this->template->getExternalStylesheets());
        $this->assertInstanceOf('\RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAssetsCollectionInterface', $this->template->getInternalStylesheets());
        $this->assertInstanceOf('\RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAssetsCollectionInterface', $this->template->getExternalJavascripts());
        $this->assertInstanceOf('\RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAssetsCollectionInterface', $this->template->getInternalJavascripts());
    }
}