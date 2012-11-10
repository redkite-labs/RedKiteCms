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
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;

/**
 * AlSlotTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlSlotTest extends TestCase
{
    /**
     * @expectedException \AlphaLemon\ThemeEngineBundle\Core\Exception\InvalidArgumentException
     */
    public function testAlSlotDoesNotAdmitNullValuesForTheSlotNameParam()
    {
        $slot = new AlSlot(null);
    }

    /**
     * @expectedException \AlphaLemon\ThemeEngineBundle\Core\Exception\InvalidArgumentException
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

    public function testAlSlotInizializedWithGivenValues()
    {
        $slot = new AlSlot('nav-menu', array('repeated' => 'site',
                                        'blockType' => 'Script',
                                        'htmlContent' => 'my fancy content'));
        $this->assertEquals('nav-menu', $slot->getSlotName());
        $this->assertEquals('my fancy content', $slot->getContent());
        $this->assertEquals('Script', $slot->getBlockType());
        $this->assertEquals('site', $slot->getRepeated());
    }

    public function testAlSlotToArray()
    {
        $values = array('repeated' => 'site',
                        'blockType' => 'Script',
                        'htmlContent' => 'my fancy content');
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