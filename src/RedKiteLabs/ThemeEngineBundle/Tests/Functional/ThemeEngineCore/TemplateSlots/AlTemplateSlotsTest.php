<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * (c) Since 2011 AlphaLemon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 * 
 */

namespace AlphaLemon\ThemeEngineBundle\Tests\Functional\ThemeEngineCore\TemplateSlots;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use ThemeEngineCore\TemplateSlots;
use Themes\AlphaLemonThemeBundle\src\Slots\AlphaLemonThemeBundleHomeSlots;

class AlphaLemonThemeBundleHome1Slot extends TemplateSlots\AlTemplateSlots
{
    public function configure()
    {
        return array('fake_slot_1' => new TemplateSlots\AlSlot('fake_slot_1', array('repeated' => 'page')),
                     'fake_slot_2' => new TemplateSlots\AlSlot('fake_slot_2', array('repeated' => 'language')),
                     'fake_slot_3' => new TemplateSlots\AlSlot('fake_slot_3', array('repeated' => 'site')),
                    );
    }
}

class AlTemplateSlotsTest extends TestCase 
{    
    public function testBaseTemplateSlots()
    {
        $testTemplateSlots = new AlphaLemonThemeBundleHomeSlots();
        $this->assertNotEquals(0, $testTemplateSlots->getSlots());
        $this->assertNull($testTemplateSlots->getRepeatedContentFromSlot('foo'));    
        $this->assertEquals('page', $testTemplateSlots->getRepeatedContentFromSlot('header'));     
        $this->assertEquals('language', $testTemplateSlots->getRepeatedContentFromSlot('nav_menu'));     
        $this->assertEquals('site', $testTemplateSlots->getRepeatedContentFromSlot('logo'));   
        $this->assertNull($testTemplateSlots->getTextFromSlot('foo')); 
        $this->assertEquals('This is the default text for the slot logo', $testTemplateSlots->getTextFromSlot('logo'));  
        $this->assertArrayHasKey('page', $testTemplateSlots->toArray());
        $this->assertArrayHasKey('language', $testTemplateSlots->toArray());
        $this->assertArrayHasKey('site', $testTemplateSlots->toArray());
    }
    
    public function testCustomTemplateSlots()
    {
        $testTemplateSlots = new AlphaLemonThemeBundleHome1Slot();
        $this->assertEquals('page', $testTemplateSlots->getRepeatedContentFromSlot('header'));     
        $this->assertEquals('language', $testTemplateSlots->getRepeatedContentFromSlot('nav_menu'));     
        $this->assertEquals('site', $testTemplateSlots->getRepeatedContentFromSlot('logo'));   
        $this->assertEquals('page', $testTemplateSlots->getRepeatedContentFromSlot('fake_slot_1'));     
        $this->assertEquals('language', $testTemplateSlots->getRepeatedContentFromSlot('fake_slot_2'));     
        $this->assertEquals('site', $testTemplateSlots->getRepeatedContentFromSlot('fake_slot_3'));   
    }
}