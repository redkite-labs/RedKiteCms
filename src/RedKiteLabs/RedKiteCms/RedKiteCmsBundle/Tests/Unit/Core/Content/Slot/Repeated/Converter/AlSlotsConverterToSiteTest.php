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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Slot\Repeated\Converter;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\Factory\AlSlotsConverterFactory;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\AlSlotConverterToSite;

/**
 * AlSlotsConverterToSiteTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlSlotsConverterToSiteTest extends AlSlotsConverterBase
{
    public function testConvertReturnsNullWhenAnyBlockExists()
    {
        $this->pageContents->expects($this->once())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array()));

        $slot = new AlSlot('test', array('repeated' => 'page'));
        $converter = new AlSlotConverterToSite($slot, $this->pageContents, $this->factoryRepository);
        $this->assertNull($converter->convert());
    }

    public function testConvertFailsOnAnEmptySlotWhenDbSavingFails()
    {
        $this->pageContents->expects($this->once())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array($this->setUpBlock())));

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('rollback');

        $this->blockRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(false));

        $slot = new AlSlot('test', array('repeated' => 'page'));
        $converter = new AlSlotConverterToSite($slot, $this->pageContents, $this->factoryRepository);
        $this->assertFalse($converter->convert());
    }

    public function testConvertFailsWhenExistingBlocksRemovingFails()
    {
        $this->pageContents->expects($this->once())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array($this->setUpBlock())));

        $this->blockRepository->expects($this->exactly(2))
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('rollback');

        $this->blockRepository->expects($this->never())
            ->method('save');

        $this->blockRepository->expects($this->any())
            ->method('retrieveContentsBySlotName')
            ->will($this->returnValue(array($this->setUpBlock())));

        $this->blockRepository->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(false));

        $slot = new AlSlot('test', array('repeated' => 'page'));
        $converter = new AlSlotConverterToSite($slot, $this->pageContents, $this->factoryRepository);
        $this->assertFalse($converter->convert());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testConvertFailsWhenAnUnespectedExceptionIsThrowsWhenRemovingBlocks()
    {
        $this->pageContents->expects($this->once())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array($this->setUpBlock())));

        $this->blockRepository->expects($this->exactly(2))
            ->method('startTransaction');

        $this->blockRepository->expects($this->exactly(2))
            ->method('rollback');

        $this->blockRepository->expects($this->never())
            ->method('save');

        $this->blockRepository->expects($this->any())
            ->method('retrieveContentsBySlotName')
            ->will($this->returnValue(array($this->setUpBlock())));

        $this->blockRepository->expects($this->once())
            ->method('delete')
            ->will($this->throwException(new \RuntimeException));

        $slot = new AlSlot('test', array('repeated' => 'page'));
        $converter = new AlSlotConverterToSite($slot, $this->pageContents, $this->factoryRepository);
        $this->assertFalse($converter->convert());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testConvertFailsWhenAnUnespectedExceptionIsThrowsWhenSavingNewBlocks()
    {
        $block = $this->setUpBlock();
        $this->pageContents->expects($this->once())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array($block)));

        $this->blockRepository->expects($this->exactly(2))
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('commit');

        $this->blockRepository->expects($this->once())
            ->method('rollback');

        $this->blockRepository->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException));

        $this->blockRepository->expects($this->any())
            ->method('retrieveContentsBySlotName')
            ->will($this->returnValue(array($this->setUpBlock())));

        $this->blockRepository->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(true));

        $slot = new AlSlot('test', array('repeated' => 'page'));
        $converter = new AlSlotConverterToSite($slot, $this->pageContents, $this->factoryRepository);
        $this->assertTrue($converter->convert());
    }

    public function testSingleBlockSlotHasBeenConverted()
    {
        $block = $this->setUpBlock();
        $this->pageContents->expects($this->once())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array($block)));

        $this->blockRepository->expects($this->exactly(2))
            ->method('startTransaction');

        $this->blockRepository->expects($this->exactly(2))
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $this->blockRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));

        $this->blockRepository->expects($this->any())
            ->method('retrieveContentsBySlotName')
            ->will($this->returnValue(array($this->setUpBlock())));

        $this->blockRepository->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(true));

        $slot = new AlSlot('test', array('repeated' => 'page'));
        $converter = new AlSlotConverterToSite($slot, $this->pageContents, $this->factoryRepository);
        $this->assertTrue($converter->convert());
    }

    public function testMoreBlockSlotHasBeenConverted()
    {
        $block = $this->setUpBlock();
        $block1 = $this->setUpBlock();
        $block2 = $this->setUpBlock();
        $this->pageContents->expects($this->once())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array($block, $block1, $block2)));

        $this->blockRepository->expects($this->exactly(2))
            ->method('startTransaction');

        $this->blockRepository->expects($this->exactly(2))
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $this->blockRepository->expects($this->exactly(3))
            ->method('save')
            ->will($this->returnValue(true));

        $this->blockRepository->expects($this->any())
            ->method('retrieveContentsBySlotName')
            ->will($this->returnValue(array($this->setUpBlock())));

        $this->blockRepository->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(true));

        $slot = new AlSlot('test', array('repeated' => 'page'));
        $converter = new AlSlotConverterToSite($slot, $this->pageContents, $this->factoryRepository);
        $this->assertTrue($converter->convert());
    }

    private function setUpBlock()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
            ->method('toArray')
            ->will($this->returnValue(array("Id" => 2, "ClassName" => "Text")));

        return $block;
    }
}