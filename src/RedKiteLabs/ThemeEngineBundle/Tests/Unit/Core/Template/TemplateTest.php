<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
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

namespace RedKiteLabs\ThemeEngineBundle\Tests\Unit\Core\Asset;

use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;
use RedKiteLabs\ThemeEngineBundle\Core\Template\Template;

/**
 * TemplateTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class TemplateTest extends TestCase
{
    private $templateAssets;
    private $kernel;
    private $templateSlots;

    protected function setUp()
    {
        $this->templateAssets = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\Template\TemplateAssets');
        
        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $this->templateSlots = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\ThemeSlotsInterface');
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
        $this->template = new Template($this->kernel, $this->templateAssets, $this->templateSlots);
        $this->template
                ->setThemeName($themeName)
                ->setTemplateName($templateName);         
        
        $this->assertEquals($themeName, $this->template->getThemeName());
        $this->assertEquals($templateName, $this->template->getTemplateName());
    }

    public function testTemplateHasBeenPopulatedWithEmptyAssets()
    {
        $this->initAssets();
        $this->template = new Template($this->kernel, $this->templateAssets, $this->templateSlots);
        
        $this->verifyAssets(0);
    }
    
    public function testTemplateHasBeenPopulatedWithSomeAssets()
    {
        $this->initTemplateWithSomeAssets();
        $this->template = new Template($this->kernel, $this->templateAssets, $this->templateSlots);
        
        $this->verifyAssets(1);
    }

    public function testTemplateSlotsReturnsAnEmptyArrayWhenTheTemplateSlotIsNotInitialized()
    {
        $this->initTemplateWithSomeAssets();        
        $this->template = new Template($this->kernel, $this->templateAssets, $this->templateSlots);

        $this->assertTrue(count($this->template->getSlots()) == 0);
    }

    public function testFetchesTemplateSlots()
    {
        $this->initTemplateWithSomeAssets();
        $slots = array('logo', 'menu');

        $this->template = new Template($this->kernel, $this->templateAssets, $this->templateSlots);
        $this->assertEmpty($this->template->getSlots());
        $this->template->setSlots($slots);
        $this->assertEquals($slots, $this->template->getSlots());
    }
    
    public function testAddAnAsset()
    {
        $this->initAssets();
        $this->template = new Template($this->kernel, $this->templateAssets, $this->templateSlots);
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
        $this->template = new Template($this->kernel, $this->templateAssets, $this->templateSlots);
        $this->template->addExternalJavascripts('temp.js');
    }
    
    public function testAddARangeOfAsset()
    {
        $this->initAssets();
        $this->template = new Template($this->kernel, $this->templateAssets, $this->templateSlots);
        $this->assertCount(0, $this->template->getExternalJavascripts());
        $this->template->addExternalJavascripts(array('temp.js', 'temp1.js'));
        $this->assertCount(2, $this->template->getExternalJavascripts());
    }
    
    public function testSetAssetsCollection()
    {
        $this->initAssets();
        $this->template = new Template($this->kernel, $this->templateAssets, $this->templateSlots);
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
        $this->template = new Template($this->kernel, $this->templateAssets, $this->templateSlots);
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
        $this->assertInstanceOf('\RedKiteLabs\ThemeEngineBundle\Core\Asset\AssetsCollectionInterface', $this->template->getExternalStylesheets());
        $this->assertInstanceOf('\RedKiteLabs\ThemeEngineBundle\Core\Asset\AssetsCollectionInterface', $this->template->getInternalStylesheets());
        $this->assertInstanceOf('\RedKiteLabs\ThemeEngineBundle\Core\Asset\AssetsCollectionInterface', $this->template->getExternalJavascripts());
        $this->assertInstanceOf('\RedKiteLabs\ThemeEngineBundle\Core\Asset\AssetsCollectionInterface', $this->template->getInternalJavascripts());
    }
}