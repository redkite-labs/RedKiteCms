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
use RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\Slot;

/**
 * SlotTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class SlotTest extends TestCase
{
    /**
     * @expectedException \RedKiteLabs\ThemeEngineBundle\Core\Exception\InvalidArgumentException
     */
    public function testSlotDoesNotAdmitNullValuesForTheSlotNameParam()
    {
        $slot = new Slot(null);
    }

    /**
     * @expectedException \RedKiteLabs\ThemeEngineBundle\Core\Exception\InvalidArgumentException
     */
    public function testSlotRequiresAStringValueForTheSlotNameParam()
    {
        $slot = new Slot(array('logo'));
    }

    public function testSlotInizializedWithDefaultValues()
    {
        $slot = new Slot('logo');
        $this->assertEquals('logo', $slot->getSlotName());
        $this->assertNull($slot->getContent());
        $this->assertEquals('Text', $slot->getBlockType());
        $this->assertEquals('page', $slot->getRepeated());
    }
    
    public function testForceRepeatedDuringDeploying()
    {
        $slot = new Slot('nav-menu', array(
            'repeated' => 'site|page',
            'blockType' => 'Script',
            'htmlContent' => 'my fancy content',
        ));
        $this->assertEquals('site', $slot->getRepeated());
        $this->assertEquals('page', $slot->getForceRepeatedDuringDeploying());
    }

    public function testSlotInizializedWithGivenValues()
    {
        $slot = new Slot('nav-menu', array(
            'repeated' => 'site',
            'blockType' => 'Script',
            'htmlContent' => 'my fancy content',
        ));
        $this->assertEquals('nav-menu', $slot->getSlotName());
        $this->assertEquals('my fancy content', $slot->getContent());
        $this->assertEquals('Script', $slot->getBlockType());
        $this->assertEquals('site', $slot->getRepeated());
    }
    
    public function testSlotToArray()
    {
        $values = array(
            'repeated' => 'site',
            'blockType' => 'Script',
            'htmlContent' => 'my fancy content',
            'blockDefinition' => 'definition'
        );
        $slot = new Slot('logo', $values);
        $values['slotName'] = 'logo';
        $values['blockType'] = 'Script';
        $this->assertEquals($values, $slot->toArray());
    }
    
    public function testSetSlotPropertiesBySetters()
    {
        $slot = new Slot('logo');
        $slot->setRepeated('language');
        $slot->setBlockType('Script');
        $this->assertEquals('language', $slot->getRepeated());
        $this->assertEquals('Script', $slot->getBlockType());
    }
}