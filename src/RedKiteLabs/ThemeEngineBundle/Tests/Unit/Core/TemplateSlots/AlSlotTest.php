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
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\ThemeEngineBundle\Tests\Unit\Core\Asset;

use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;
use RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlSlot;

/**
 * AlSlotTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlSlotTest extends TestCase
{
    /**
     * @expectedException \RedKiteLabs\ThemeEngineBundle\Core\Exception\InvalidArgumentException
     */
    public function testAlSlotDoesNotAdmitNullValuesForTheSlotNameParam()
    {
        $slot = new AlSlot(null);
    }

    /**
     * @expectedException \RedKiteLabs\ThemeEngineBundle\Core\Exception\InvalidArgumentException
     */
    public function testAlSlotRequiresAStringValueForTheSlotNameParam()
    {
        $slot = new AlSlot(array('logo'));
    }

    public function testAlSlotInizializedWithDefaultValues()
    {
        $slot = new AlSlot('logo');
        $this->assertEquals('logo', $slot->getSlotName());
        $this->assertNull($slot->getContent());
        $this->assertEquals('Text', $slot->getBlockType());
        $this->assertEquals('page', $slot->getRepeated());
    }
    
    public function testForceRepeatedDuringDeploying()
    {
        $slot = new AlSlot('nav-menu', array(
            'repeated' => 'site|page',
            'blockType' => 'Script',
            'htmlContent' => 'my fancy content',
        ));
        $this->assertEquals('site', $slot->getRepeated());
        $this->assertEquals('page', $slot->getForceRepeatedDuringDeploying());
    }

    public function testAlSlotInizializedWithGivenValues()
    {
        $slot = new AlSlot('nav-menu', array(
            'repeated' => 'site',
            'blockType' => 'Script',
            'htmlContent' => 'my fancy content',
        ));
        $this->assertEquals('nav-menu', $slot->getSlotName());
        $this->assertEquals('my fancy content', $slot->getContent());
        $this->assertEquals('Script', $slot->getBlockType());
        $this->assertEquals('site', $slot->getRepeated());
    }
    
    public function testAlSlotToArray()
    {
        $values = array(
            'repeated' => 'site',
            'blockType' => 'Script',
            'htmlContent' => 'my fancy content',
        );
        $slot = new AlSlot('logo', $values);
        $values['slotName'] = 'logo';
        $values['blockType'] = 'Script';
        $this->assertEquals($values, $slot->toArray());
    }
    
    public function testSetSlotPropertiesBySetters()
    {
        $slot = new AlSlot('logo');
        $slot->setRepeated('language');
        $slot->setBlockType('Script');
        $this->assertEquals('language', $slot->getRepeated());
        $this->assertEquals('Script', $slot->getBlockType());
    }
}