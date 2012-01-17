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

namespace AlphaLemon\ThemeEngineBundle\Tests\Functional\AlphaLemon\ThemeEngineBundle\Core\AlSlot;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;

class AlSlotTest extends TestCase 
{    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseExceptionWhenTheConstructorReceivesANullParam()
    {
        $testAlSlot = new AlSlot(null);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseExceptionWhenTheConstructorReceivesANonStringParam()
    {
        $testAlSlot = new AlSlot(array('logo'));
    }
    
    public function testAlSlotWithoutOptionalParams()
    {
        $testAlSlot = new AlSlot('foo');
        $this->assertEquals('foo', $testAlSlot->getSlotName('foo'));
        $this->assertEquals('This is the default text for the slot foo', $testAlSlot->getDefaultText('foo'));
        $this->assertEquals('page', $testAlSlot->getRepeated('foo'));
        $this->assertEquals('Text', $testAlSlot->getContentType('foo'));
        
        return $testAlSlot;
    }
    
    /**
     * @depends testAlSlotWithoutOptionalParams
     */
    public function testAlSlotWithANonValidOptionalParam(AlSlot $testAlSlot)
    {
        $testAlSlot1 = new AlSlot('foo', array('foo' => 'bar'));
        $this->assertEquals($testAlSlot, $testAlSlot1, 'A not valid param is not ignored as supposed');
    }
    
    public function testAlSlotWithOneValidOptionalParam()
    {
        $testAlSlot = new AlSlot('foo', array('repeated' => 'site'));
        $this->assertEquals('site', $testAlSlot->getRepeated('foo'));
    }
    
    public function testAlSlotWithAllOptionalParams()
    {
        $testAlSlot = new AlSlot('foo', array('repeated' => 'language', 'defaultText' => 'Custom text', 'contentType' => 'image'));
        $this->assertEquals('language', $testAlSlot->getRepeated('foo'));
        $this->assertEquals('Custom text', $testAlSlot->getDefaultText('foo'));
        $this->assertEquals('Image', $testAlSlot->getContentType('foo'));
        $testAlSlot->setContentType('text');
        $this->assertEquals('Text', $testAlSlot->getContentType('foo'));
    }
}