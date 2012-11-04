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
use AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate;

/**
 * AlTemplateTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlTemplateTest extends TestCase
{
    private $templateAssets;
    private $kernel;
    private $templateSlots;

    protected function setUp()
    {
        $this->templateAssets = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplateAssets');
        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $this->templateSlots = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface');

        $this->template = new AlTemplate($this->kernel, $this->templateAssets, $this->templateSlots);
    }

    public function testAssetsAreNotRetrievedJustValorizingTheThemeName()
    {
        $themeName = "BusinessWebsiteThemeBundle";
        $this->setUpTemplateAssetsThemeNameParam($themeName);

        $this->template->setThemeName($themeName);
        $this->assertNull($this->template->getExternalStylesheets());
    }

    public function testAssetsAreNotRetrievedJustValorizingTheTemplateName()
    {
        $templateName = "Home";
        $this->setUpTemplateAssetsTemplateNameParam($templateName);

        $this->template->setTemplateName($templateName);
        $this->assertNull($this->template->getExternalStylesheets());
    }

    public function testTemplateHasBeenPopulatedWithEmptyAssets()
    {
        $themeName = "BusinessWebsiteThemeBundle";
        $this->setUpTemplateAssetsThemeNameParam($themeName, 2);

        $templateName = "Home";
        $this->templateAssets->expects($this->once())
            ->method('setTemplateName')
            ->with($templateName);

        $this->templateAssets->expects($this->exactly(2))
            ->method('getTemplateName')
            ->will($this->onConsecutiveCalls(null, $templateName));

        $this->templateAssets->expects($this->exactly(4))
            ->method('__call')
            ->will($this->returnValue(array()));

        $this->template
                ->setThemeName($themeName)
                ->setTemplateName($templateName);

        $this->verifyAssets(0);
    }
    
    public function testTemplateHasBeenPopulatedWithEmptyAssetsFromTheTemplateAssetsObject()
    {
        $templateName = "Home";
        $this->templateAssets->expects($this->exactly(1))
            ->method('getThemeName')
            ->will($this->returnValue("BusinessWebsiteThemeBundle"));
        
        $this->templateAssets->expects($this->exactly(1))
            ->method('getTemplateName')
            ->will($this->returnValue($templateName));

        $this->templateAssets->expects($this->exactly(4))
            ->method('__call')
            ->will($this->returnValue(array()));

        $this->template = new AlTemplate($this->kernel, $this->templateAssets, $this->templateSlots);

        $this->verifyAssets(0);
    }

    public function testTemplateHasBeenPopulatedWithSomeAssets()
    {
        $themeName = "BusinessWebsiteThemeBundle";
        $templateName = "Home";
        $this->initTemplateWithSomeAssets($themeName, $templateName);

        $this->template
                ->setThemeName($themeName)
                ->setTemplateName($templateName);

        $this->verifyAssets(1);
    }

    public function testTemplateSlotsReturnsAnEmptyArrayWhenTheTemplateSlotIsNotInitialized()
    {
        $themeName = "BusinessWebsiteThemeBundle";
        $templateName = "Home";
        $this->initTemplateWithSomeAssets($themeName, $templateName);

        $this->template
                ->setThemeName($themeName)
                ->setTemplateName($templateName);

        $this->assertTrue(count($this->template->getSlots()) == 0);
        $this->assertTrue(count($this->template->getSlot('logo')) == 0);
    }

    public function testFetchesTemplateSlots()
    {
        $themeName = "BusinessWebsiteThemeBundle";
        $templateName = "Home";
        $this->initTemplateWithSomeAssets($themeName, $templateName);

        $slot = array('repeated' => 'site');
        $slots = array('logo' => $slot);
        
        $this->templateSlots->expects($this->once())
            ->method('getSlots')
            ->will($this->returnValue($slots));

        $this->templateSlots->expects($this->once())
            ->method('getSlot')
            ->will($this->returnValue($slot));

        $this->template
                ->setThemeName($themeName)
                ->setTemplateName($templateName);

        $this->assertEquals($slots, $this->template->getSlots());
        $this->assertEquals($slot, $this->template->getSlot('logo'));
    }

    private function initTemplateWithSomeAssets($themeName, $templateName)
    {
        $this->setUpTemplateAssetsThemeNameParam($themeName, 2);

        $this->templateAssets->expects($this->once())
            ->method('setTemplateName')
            ->with($templateName);

        $this->templateAssets->expects($this->exactly(2))
            ->method('getTemplateName')
            ->will($this->onConsecutiveCalls(null, $templateName));

        $this->templateAssets->expects($this->exactly(4))
            ->method('__call')
            ->will($this->onConsecutiveCalls(
                    array('@BusinessWebsiteThemeBundle/Resources/public/css/reset.css'),
                    array('Fake style'),
                    array('@BusinessWebsiteThemeBundle/Resources/public/js/reset.js'),
                    array('Fake code')));
    }

    private function verifyAssets($expectedElements)
    {
        $this->assertTrue(count($this->template->getExternalStylesheets()) == $expectedElements);
        $this->assertTrue(count($this->template->getInternalStylesheets()) == $expectedElements);
        $this->assertTrue(count($this->template->getExternalJavascripts()) == $expectedElements);
        $this->assertTrue(count($this->template->getInternalJavascripts()) == $expectedElements);
        $this->assertInstanceOf('\AlphaLemon\ThemeEngineBundle\Core\Asset\AlAssetsCollectionInterface', $this->template->getExternalStylesheets());
        $this->assertInstanceOf('\AlphaLemon\ThemeEngineBundle\Core\Asset\AlAssetsCollectionInterface', $this->template->getInternalStylesheets());
        $this->assertInstanceOf('\AlphaLemon\ThemeEngineBundle\Core\Asset\AlAssetsCollectionInterface', $this->template->getExternalJavascripts());
        $this->assertInstanceOf('\AlphaLemon\ThemeEngineBundle\Core\Asset\AlAssetsCollectionInterface', $this->template->getInternalJavascripts());
    }

    private function setUpTemplateAssetsThemeNameParam($themeName, $times = 1)
    {
        $this->templateAssets->expects($this->once())
            ->method('setThemeName')
            ->with($themeName);

        $this->templateAssets->expects($this->exactly($times))
            ->method('getThemeName')
            ->will($this->returnValue($themeName));
    }

    private function setUpTemplateAssetsTemplateNameParam($themeName, $times = 1)
    {
        $this->templateAssets->expects($this->once())
            ->method('setTemplateName')
            ->with($themeName);

        $this->templateAssets->expects($this->exactly($times))
            ->method('getTemplateName')
            ->will($this->returnValue($themeName));
    }
}