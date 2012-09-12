<?php
/*
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

    /**
     * @expectedException AlphaLemon\ThemeEngineBundle\Core\PageTree\Exception\AnyValidArgumentGivenException
     */
    public function testBlockIsNotAddedWhenGivenValuesDoesNotContainAnyValidOptionParam()
    {
        $this->pageBlocks->add("logo", array('Fake' => 'My value'));
    }

    public function testBlockIsAdded()
    {
        $this->assertEquals($this->pageBlocks, $this->pageBlocks->add("logo", array('HtmlContent' => 'My value')));

        $this->assertTrue(count($this->pageBlocks->getBlocks()) == 1);
        $this->checkOneBlock('logo', 'My value');
    }

    public function testBlockIsEdited()
    {
        $this->pageBlocks->add("logo", array('HtmlContent' => 'My value'));
        $this->pageBlocks->add("logo", array('HtmlContent' => 'My new value'), 0);

        $this->assertTrue(count($this->pageBlocks->getBlocks()) == 1);
        $this->checkOneBlock('logo', 'My new value');
    }

    public function testBlockIsAddedWhenAnInvalidPositionNumberIsGiven()
    {
        $this->pageBlocks->add("logo", array('HtmlContent' => 'My value'));
        $this->pageBlocks->add("logo", array('HtmlContent' => 'My new value'), 5);

        $this->assertTrue(count($this->pageBlocks->getBlocks()) == 1);
        $block = $this->pageBlocks->getSlotBlocks('logo');
        $this->assertTrue(count($block) == 2);
    }

    /**
     * @expectedException AlphaLemon\ThemeEngineBundle\Core\PageTree\Exception\AnyValidArgumentGivenException
     */
    public function testOneBlockIsNotAddedWhenBecauseItContainsAnInvalidOptionParam()
    {
        $this->assertEquals($this->pageBlocks, $this->pageBlocks->addRange(array("logo" => array(array('HtmlContent' => 'My value'), array('Fake' => 'My value')))));
        $this->checkOneBlock('logo', 'My value');
    }

    public function testARangeOfBlocksIsAdded()
    {
        $this->pageBlocks->addRange(array("logo" => array(array('HtmlContent' => 'My value'), array('HtmlContent' => 'My new value'))));

        $this->assertTrue(count($this->pageBlocks->getBlocks()) == 1);
        $block = $this->pageBlocks->getSlotBlocks('logo');
        $this->assertTrue(count($block) == 2);
        $this->assertEquals('My value', $block[0]['HtmlContent']);
        $this->assertEquals('My new value', $block[1]['HtmlContent']);
    }

    public function testARangeOfBlocksIsAddedOnMoreSlots()
    {
        $this->pageBlocks->addRange(array("logo" => array(array('HtmlContent' => 'My value'), array('HtmlContent' => 'My new value')),
            "nav_menu" => array(array('HtmlContent' => 'My value'))));

        $this->assertTrue(count($this->pageBlocks->getBlocks()) == 2);
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
        $this->pageBlocks->addRange(array("logo" => array(array('HtmlContent' => 'My value'))));
        $this->assertTrue(count($this->pageBlocks->getSlotBlocks('logo')) == 1);

        $this->assertEquals($this->pageBlocks, $this->pageBlocks->clearSlotBlocks('logo'));
        $this->assertTrue(count($this->pageBlocks->getSlotBlocks('logo')) == 0);
    }

    public function testAllSlotsAreCleared()
    {
        $this->pageBlocks->addRange(array("logo" => array(array('HtmlContent' => 'My value')), "nav-menu" => array(array('HtmlContent' => 'My value'))));
        $this->assertTrue(count($this->pageBlocks->getBlocks()) == 2);
        $this->assertTrue(count($this->pageBlocks->getSlotBlocks('logo')) == 1);
        $this->assertTrue(count($this->pageBlocks->getSlotBlocks('nav-menu')) == 1);

        $this->assertEquals($this->pageBlocks, $this->pageBlocks->clearSlots());
        $this->assertTrue(count($this->pageBlocks->getBlocks()) == 2);
        $this->assertTrue(count($this->pageBlocks->getSlotBlocks('logo')) == 0);
        $this->assertTrue(count($this->pageBlocks->getSlotBlocks('nav-menu')) == 0);
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
        $this->pageBlocks->addRange(array("logo" => array(array('HtmlContent' => 'My value'))));
        $this->assertTrue(count($this->pageBlocks->getBlocks()) == 1);

        $this->assertEquals($this->pageBlocks, $this->pageBlocks->removeSlot('logo'));
        $this->assertTrue(count($this->pageBlocks->getBlocks()) == 0);
    }

    public function testAllSlotsAreRemoved()
    {
        $this->pageBlocks->addRange(array("logo" => array(array('HtmlContent' => 'My value')), "nav-menu" => array(array('HtmlContent' => 'My value'))));
        $this->assertTrue(count($this->pageBlocks->getBlocks()) == 2);

        $this->assertEquals($this->pageBlocks, $this->pageBlocks->removeSlots());
        $this->assertTrue(count($this->pageBlocks->getBlocks()) == 0);
    }

    private function checkOneBlock($slotName, $expectedContent)
    {
        $block = $this->pageBlocks->getSlotBlocks($slotName);
        $this->assertTrue(count($block) == 1);
        $this->assertEquals($expectedContent, $block[0]['HtmlContent']);
    }
}