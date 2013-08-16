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

namespace RedKiteLabs\ThemeEngineBundle\Tests\Unit\Core\Rendering\SlotContent;

use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;
use RedKiteLabs\ThemeEngineBundle\Core\Rendering\SlotContent\AlSlotContent;

/**
 * AlSlotContentTester
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlSlotContentTester extends TestCase
{
    private $slotContent;
    
    protected function setUp()
    {
        $this->slotContent = new AlSlotContent();
    }

    public function testByDefaultAllPropertiesAreNull()
    {
        $this->assertNull($this->slotContent->getSlotName());
        $this->assertNull($this->slotContent->getContent());
        $this->assertNull($this->slotContent->isReplacing());
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The slot name passed to "RedKiteLabs\ThemeEngineBundle\Core\Rendering\SlotContent\AlSlotContent" must be a string
     */
    public function testSetSlotNameAcceptsOnlyStrings()
    {
        $this->slotContent->setSlotName(array());
    }
    
    public function testSlotName()
    {
        $slotName = 'test';
        $this->slotContent->setSlotName($slotName);
        $this->assertEquals($slotName, $this->slotContent->getSlotName());
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The content passed to "AlphaLemon\FrontendBundle\Core\SlotContent\AlSlotContent" must be a string
     */
    public function testSetContentAcceptsOnlyStrings()
    {
        $this->slotContent->setContent(array());
    }
    
    public function testContent()
    {
        $content = 'test';
        $this->slotContent->setContent($content);
        $this->assertEquals($content, $this->slotContent->getContent());
    }
    
    public function testReplace()
    {
        $this->slotContent->replace();
        $this->assertTrue($this->slotContent->isReplacing());
    }
    
    public function testInject()
    {
        $this->slotContent->inject();
        $this->assertFalse($this->slotContent->isReplacing());
    }
}
