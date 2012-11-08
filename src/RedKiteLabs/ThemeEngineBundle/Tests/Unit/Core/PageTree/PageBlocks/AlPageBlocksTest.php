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

namespace AlphaLemon\ThemeEngineBundle\Tests\Unit\Core\PageBlocks;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocks;

/**
 * AlPageBlocksTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlPageBlocksTest extends TestCase
{
    private $pageBlocks;

    protected function setUp()
    {
        parent::setUp();

        $this->pageBlocks = new AlPageBlocks();
    }

    public function testBlockIsAdded()
    {
        $this->assertEquals($this->pageBlocks, $this->pageBlocks->add("logo", array('Content' => 'My value')));

        $this->assertCount(1, $this->pageBlocks->getBlocks());
        $this->checkOneBlock('logo', 'My value');
    }

    public function testBlockIsEdited()
    {
        $this->pageBlocks->add("logo", array('Content' => 'My value'));
        $this->pageBlocks->add("logo", array('Content' => 'My new value'), 0);

        $this->assertCount(1, $this->pageBlocks->getBlocks());
        $this->checkOneBlock('logo', 'My new value');
    }

    public function testBlockIsAddedWhenAnInvalidPositionNumberIsGiven()
    {
        $this->pageBlocks->add("logo", array('Content' => 'My value'));
        $this->pageBlocks->add("logo", array('Content' => 'My new value'), 5);

        $this->assertCount(1, $this->pageBlocks->getBlocks());
        $block = $this->pageBlocks->getSlotBlocks('logo');
        $this->assertCount(2, $block);
    }
    
    public function testNullContents()
    {
        $this->pageBlocks->addRange(array("logo" => null));

        $this->assertCount(1, $this->pageBlocks->getBlocks());
        $block = $this->pageBlocks->getSlotBlocks('logo');
        $this->assertNull($block);
    }

    public function testARangeOfBlocksIsAdded()
    {
        $this->pageBlocks->addRange(array("logo" => array(array('Content' => 'My value'), array('Content' => 'My new value'))));

        $this->assertCount(1, $this->pageBlocks->getBlocks());
        $block = $this->pageBlocks->getSlotBlocks('logo');
        $this->assertCount(2, $block);
        $this->assertEquals('My value', $block[0]['Content']);
        $this->assertEquals('My new value', $block[1]['Content']);
    }
    
    public function testARangeOfBlocksIsOverriden()
    {
        $this->pageBlocks->addRange(array("logo" => array(array('Content' => 'My value'), array('Content' => 'My new value'))));
        $this->pageBlocks->addRange(array("logo" => array(array('Content' => 'Overrided value'))), true);

        $this->assertCount(1, $this->pageBlocks->getBlocks());
        $block = $this->pageBlocks->getSlotBlocks('logo');
        $this->assertCount(1, $block);
        $this->assertEquals('Overrided value', $block[0]['Content']);
    }

    public function testARangeOfBlocksIsAddedOnMoreSlots()
    {
        $this->pageBlocks->addRange(array("logo" => array(array('Content' => 'My value'), array('Content' => 'My new value')),
            "nav_menu" => array(array('Content' => 'My value'))));

        $this->assertCount(2, $this->pageBlocks->getBlocks());
    }

    /**
     * @expectedException AlphaLemon\ThemeEngineBundle\Core\Exception\InvalidArgumentException
     */
    public function testAnExeptionIsThrowsWhenTryingToClearANonExistentSlot()
    {
        $this->assertEquals($this->pageBlocks, $this->pageBlocks->clearSlotBlocks('logo'));
    }

    public function testASlotIsCleared()
    {
        $this->pageBlocks->addRange(array("logo" => array(array('Content' => 'My value'))));
        $this->assertCount(1, $this->pageBlocks->getSlotBlocks('logo'));

        $this->assertEquals($this->pageBlocks, $this->pageBlocks->clearSlotBlocks('logo'));
        $this->assertCount(0, $this->pageBlocks->getSlotBlocks('logo'));
    }

    public function testAllSlotsAreCleared()
    {
        $this->pageBlocks->addRange(array("logo" => array(array('Content' => 'My value')), "nav-menu" => array(array('Content' => 'My value'))));
        $this->assertCount(2, $this->pageBlocks->getBlocks());
        $this->assertCount(1, $this->pageBlocks->getSlotBlocks('logo'));
        $this->assertCount(1, $this->pageBlocks->getSlotBlocks('nav-menu'));

        $this->assertEquals($this->pageBlocks, $this->pageBlocks->clearSlots());
        $this->assertCount(2, $this->pageBlocks->getBlocks());
        $this->assertCount(0, $this->pageBlocks->getSlotBlocks('logo'));
        $this->assertCount(0, $this->pageBlocks->getSlotBlocks('nav-menu'));
    }

    /**
     * @expectedException AlphaLemon\ThemeEngineBundle\Core\Exception\InvalidArgumentException
     */
    public function testAnExeptionIsThrowsWhenTryingToRemoveANonExistentSlot()
    {
        $this->assertEquals($this->pageBlocks, $this->pageBlocks->removeSlot('logo'));
    }

    public function testASlotIsRemoved()
    {
        $this->pageBlocks->addRange(array("logo" => array(array('Content' => 'My value'))));
        $this->assertCount(1, $this->pageBlocks->getBlocks());

        $this->assertEquals($this->pageBlocks, $this->pageBlocks->removeSlot('logo'));
        $this->assertCount(0, $this->pageBlocks->getBlocks());
    }

    public function testAllSlotsAreRemoved()
    {
        $this->pageBlocks->addRange(array("logo" => array(array('Content' => 'My value')), "nav-menu" => array(array('Content' => 'My value'))));
        $this->assertCount(2, $this->pageBlocks->getBlocks());

        $this->assertEquals($this->pageBlocks, $this->pageBlocks->removeSlots());
        $this->assertCount(0, $this->pageBlocks->getBlocks());
    }

    private function checkOneBlock($slotName, $expectedContent)
    {
        $block = $this->pageBlocks->getSlotBlocks($slotName);
        $this->assertTrue(count($block) == 1);
        $this->assertEquals($expectedContent, $block[0]['Content']);
    }
}