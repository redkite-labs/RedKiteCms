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

namespace RedKiteLabs\ThemeEngineBundle\Tests\Unit\Core\ThemeSlots;

use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;
use RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate;
use RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\AlThemeSlots;

/**
 * AlThemeSlotsTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlThemeSlotsTest extends TestCase
{
    protected function setUp()
    {
        $this->templateSlots = new AlThemeSlots();
    }

    public function testAddANewSlotAndRetrieveIt()
    {       
        $slot = $this->setUpSlot();
                
        $this->templateSlots->addSlot($slot);
        $this->assertEquals($slot, $this->templateSlots->getSlot('logo'));
    }
    
    public function testFetchANotAddedSlot()
    {       
        $slot = $this->setUpSlot();
                
        $this->templateSlots->addSlot($slot);
        $this->assertNull($this->templateSlots->getSlot('nav-menu'));
    }
    
    public function testFetchsTheSlots()
    {       
        $slot1 = $this->setUpSlot();
        $slot2 = $this->setUpSlot('nav-menu');
                
        $this->templateSlots->addSlot($slot1);
        $this->templateSlots->addSlot($slot2);
        $this->assertEquals(array('logo' => $slot1, 'nav-menu' => $slot2), $this->templateSlots->getSlots());
    }
    
    public function testConvertsTheSlotsToArrayRetrivingOnlyTheSlotName()
    {       
        $slot1 = $this->setUpSlot('logo', 2);
        $slot1->expects($this->once())
            ->method('getRepeated')
            ->will($this->returnValue('site'));
        
        $slot2 = $this->setUpSlot('nav-menu', 2);
        $slot2->expects($this->once())
            ->method('getRepeated')
            ->will($this->returnValue('language'));
        
        $slot3 = $this->setUpSlot('nav-menu-1', 2);
        $slot3->expects($this->once())
            ->method('getRepeated')
            ->will($this->returnValue('language'));
                
        $this->templateSlots->addSlot($slot1);
        $this->templateSlots->addSlot($slot2);        
        $this->templateSlots->addSlot($slot3);
        $this->assertEquals(array('site' => array('logo'), 'language' => array('nav-menu', 'nav-menu-1')), $this->templateSlots->toArray());
    }
    
    public function testConvertsTheSlotsToArray()
    {       
        $slot1 = $this->setUpSlot('logo');
        $slot1->expects($this->once())
            ->method('getRepeated')
            ->will($this->returnValue('site'));
        
        $slot1Array = array('slotName' => 'logo', 'repeated' => 'site', 'blockType' => 'text');
        $slot1->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue($slot1Array));
        
        $slot2 = $this->setUpSlot('nav-menu');
        $slot2->expects($this->once())
            ->method('getRepeated')
            ->will($this->returnValue('language'));
        
        $slot2Array = array('slotName' => 'nav-menu', 'repeated' => 'language', 'blockType' => 'menu');
        $slot2->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue($slot2Array));
        
        $slot3 = $this->setUpSlot('nav-menu-1');
        $slot3->expects($this->once())
            ->method('getRepeated')
            ->will($this->returnValue('language'));
        
        $slot3Array = array('slotName' => 'nav-menu-1', 'repeated' => 'language', 'blockType' => 'menu');
        $slot3->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue($slot3Array));
                
        $this->templateSlots->addSlot($slot1);
        $this->templateSlots->addSlot($slot2);        
        $this->templateSlots->addSlot($slot3);
        $this->assertEquals(array('site' => array($slot1Array), 'language' => array($slot2Array, $slot3Array)), $this->templateSlots->toArray(true));
    }
    
    public function testRetrivesTheSlotsRepeatedStatus()
    {       
        $slot = $this->setUpSlot();
        $slot->expects($this->once())
            ->method('getRepeated')
            ->will($this->returnValue('site'));
                
        $this->templateSlots->addSlot($slot);
        $this->assertEquals('site', $this->templateSlots->getRepeatedContentFromSlot('logo'));
        $this->assertNull($this->templateSlots->getRepeatedContentFromSlot('nav-menu'));
    }
    
    
    public function testRetrivesTheSlotsContentStatus()
    {       
        $slot = $this->setUpSlot();
        $slot->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue('fake'));
                
        $this->templateSlots->addSlot($slot);
        $this->assertEquals('fake', $this->templateSlots->getContentFromSlot('logo'));
        $this->assertNull($this->templateSlots->getContentFromSlot('nav-menu'));
    }
    
    private function setUpSlot($slotName = 'logo', $numberOfTimes = 1)
    {
        $slot = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\AlSlot')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $slot->expects($this->exactly($numberOfTimes))
            ->method('getSlotName')
            ->will($this->returnValue($slotName));
        
        return $slot;
    }    
}