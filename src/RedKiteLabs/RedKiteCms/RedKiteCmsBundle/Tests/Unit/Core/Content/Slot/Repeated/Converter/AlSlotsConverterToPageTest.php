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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Slot\Repeated\Converter\Factory;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\Factory\AlSlotsConverterFactory;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\AlSlotConverterToPage;

class AlSlotsConverterToLanguageTest extends TestCase 
{    
    protected function setUp() 
    {
        parent::setUp();
        
        $this->pageContents = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageContentsContainer\AlPageContentsContainer')
                           ->disableOriginalConstructor()
                            ->getMock();
        
        
        
        $this->languageModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlLanguageModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->pageModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlPageModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->blockModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlBlockModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->blockModel->expects($this->any())
            ->method('getModelObjectClassName')
            ->will($this->returnValue('\AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock'));
        
        $this->blockModel->expects($this->any())
            ->method('setModelObject')
            ->will($this->returnSelf());
    }
    
    public function testConvertReturnsNullWhenAnyBlockExists()
    {
        $this->pageContents->expects($this->once())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array()));
        
        $slot = new AlSlot('test', array('repeated' => 'page'));
        $converter = new AlSlotConverterToPage($slot, $this->pageContents, $this->languageModel, $this->pageModel, $this->blockModel);
        $this->assertNull($converter->convert());
    }
    
    public function testConvertReturnsNullWhenAnyLanguageExists()
    {
        $this->pageContents->expects($this->once())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array($this->setUpBlock())));
        
        $this->blockModel->expects($this->once())
            ->method('startTransaction');
        
        $this->blockModel->expects($this->once())
            ->method('rollback');
        
        $this->blockModel->expects($this->once())
            ->method('save');
        
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($this->setUpLanguage(2))));
        
        $this->pageModel->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(array($this->setUpPage(2))));
        
        $slot = new AlSlot('test', array('repeated' => 'page'));
        $converter = new AlSlotConverterToPage($slot, $this->pageContents, $this->languageModel, $this->pageModel, $this->blockModel);
        $this->assertNull($converter->convert());
    }
    
    public function testConvertFailsOnAnEmptySlotWhenDbSavingFails()
    {
        $this->pageContents->expects($this->once())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array($this->setUpBlock())));
        
        $this->blockModel->expects($this->once())
            ->method('startTransaction');
        
        $this->blockModel->expects($this->once())
            ->method('rollback');
        
        $this->blockModel->expects($this->once())
            ->method('save')
            ->will($this->returnValue(false));
        
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($this->setUpLanguage(2))));
        
        $this->pageModel->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(array($this->setUpPage(2))));
        
        $slot = new AlSlot('test', array('repeated' => 'page'));
        $converter = new AlSlotConverterToPage($slot, $this->pageContents, $this->languageModel, $this->pageModel, $this->blockModel);
        $this->assertFalse($converter->convert());
    }
    
    public function testConvertFailsWhenExistingBlocksRemovingFails()
    {
        $this->pageContents->expects($this->once())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array($this->setUpBlock())));
        
        $this->blockModel->expects($this->exactly(2))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->once())
            ->method('rollback');
        
        $this->blockModel->expects($this->never())
            ->method('save');
        
        $this->blockModel->expects($this->any())
            ->method('retrieveContentsBySlotName')
            ->will($this->returnValue(array($this->setUpBlock())));
        
        $this->blockModel->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(false));
        
        $slot = new AlSlot('test', array('repeated' => 'page'));
        $converter = new AlSlotConverterToPage($slot, $this->pageContents, $this->languageModel, $this->pageModel, $this->blockModel);
        $this->assertFalse($converter->convert());
    }
    
    public function testSingleBlockSlotWhenSinglePageAndLanguageHasBeenConverted()
    {
        $block = $this->setUpBlock();
        $this->pageContents->expects($this->once())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array($block)));
        
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($this->setUpLanguage(2))));
        
        $this->pageModel->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(array($this->setUpPage(2))));
        
        $this->blockModel->expects($this->exactly(2))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(2))
            ->method('commit');
        
        $this->blockModel->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));
        
        $this->blockModel->expects($this->any())
            ->method('retrieveContentsBySlotName')
            ->will($this->returnValue(array($this->setUpBlock())));
        
        $this->blockModel->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(true));
        
        $slot = new AlSlot('test', array('repeated' => 'page'));
        $converter = new AlSlotConverterToPage($slot, $this->pageContents, $this->languageModel, $this->pageModel, $this->blockModel);
        $this->assertTrue($converter->convert());
    }
    
    public function testMoreBlockSlotWhenSingleLanguageHasBeenConverted()
    {
        $block = $this->setUpBlock();
        $block1 = $this->setUpBlock();
        $block2 = $this->setUpBlock();
        $this->pageContents->expects($this->once())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array($block, $block1, $block2)));
        
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($this->setUpLanguage(2))));
        
        $this->pageModel->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(array($this->setUpPage(2))));
        
        $this->blockModel->expects($this->exactly(2))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(2))
            ->method('commit');
        
        $this->blockModel->expects($this->exactly(3))
            ->method('save')
            ->will($this->returnValue(true));
        
        $this->blockModel->expects($this->any())
            ->method('retrieveContentsBySlotName')
            ->will($this->returnValue(array($this->setUpBlock())));
        
        $this->blockModel->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(true));
        
        $slot = new AlSlot('test', array('repeated' => 'page'));
        $converter = new AlSlotConverterToPage($slot, $this->pageContents, $this->languageModel, $this->pageModel, $this->blockModel);
        $this->assertTrue($converter->convert());
    }
    
    public function testSingleBlockSlotWhenMorePagesAndSingleLanguageHasBeenConverted()
    {
        $block = $this->setUpBlock();
        $block1 = $this->setUpBlock();
        $block2 = $this->setUpBlock();
        $this->pageContents->expects($this->once())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array($block, $block1, $block2)));
        
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($this->setUpLanguage(2))));
        
        $this->pageModel->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(array($this->setUpPage(2), $this->setUpPage(3))));
        
        $this->blockModel->expects($this->exactly(2))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(2))
            ->method('commit');
        
        $this->blockModel->expects($this->exactly(6))
            ->method('save')
            ->will($this->returnValue(true));
        
        $this->blockModel->expects($this->any())
            ->method('retrieveContentsBySlotName')
            ->will($this->returnValue(array($this->setUpBlock())));
        
        $this->blockModel->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(true));
        
        $slot = new AlSlot('test', array('repeated' => 'page'));
        $converter = new AlSlotConverterToPage($slot, $this->pageContents, $this->languageModel, $this->pageModel, $this->blockModel);
        $this->assertTrue($converter->convert());
    }
    
    public function testSingleBlockSlotWhenMoreLanguagesHasBeenConverted()
    {
        $block = $this->setUpBlock();
        $this->pageContents->expects($this->once())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array($block)));
        
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($this->setUpLanguage(2), $this->setUpLanguage(3))));
        
        $this->pageModel->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(array($this->setUpPage(2))));
        
        $this->blockModel->expects($this->exactly(2))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(2))
            ->method('commit');
        
        $this->blockModel->expects($this->exactly(2))
            ->method('save')
            ->will($this->returnValue(true));
        
        $this->blockModel->expects($this->any())
            ->method('retrieveContentsBySlotName')
            ->will($this->returnValue(array($this->setUpBlock())));
        
        $this->blockModel->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(true));
        
        $slot = new AlSlot('test', array('repeated' => 'page'));
        $converter = new AlSlotConverterToPage($slot, $this->pageContents, $this->languageModel, $this->pageModel, $this->blockModel);
        $this->assertTrue($converter->convert());
    }
    
    public function testMoreBlockSlotWhenMoreLanguagesHasBeenConverted()
    {
        $block = $this->setUpBlock();
        $block1 = $this->setUpBlock();
        $block2 = $this->setUpBlock();
        $this->pageContents->expects($this->once())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array($block, $block1, $block2)));
        
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($this->setUpLanguage(2),$this->setUpLanguage(3))));
        
        $this->pageModel->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(array($this->setUpPage(2))));
        
        $this->blockModel->expects($this->exactly(2))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(2))
            ->method('commit');
        
        $this->blockModel->expects($this->exactly(6))
            ->method('save')
            ->will($this->returnValue(true));
        
        $this->blockModel->expects($this->any())
            ->method('retrieveContentsBySlotName')
            ->will($this->returnValue(array($this->setUpBlock())));
        
        $this->blockModel->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(true));
        
        $slot = new AlSlot('test', array('repeated' => 'page'));
        $converter = new AlSlotConverterToPage($slot, $this->pageContents, $this->languageModel, $this->pageModel, $this->blockModel);
        $this->assertTrue($converter->convert());
    }
    
    public function testSingleBlockSlotWhenMorePagesAndLanguagesHasBeenConverted()
    {
        $block = $this->setUpBlock();
        $block1 = $this->setUpBlock();
        $block2 = $this->setUpBlock();
        $this->pageContents->expects($this->once())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array($block, $block1, $block2)));
        
        $this->languageModel->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($this->setUpLanguage(2), $this->setUpLanguage(3))));
        
        $this->pageModel->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(array($this->setUpPage(2), $this->setUpPage(3))));
        
        $this->blockModel->expects($this->exactly(2))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(2))
            ->method('commit');
        
        $this->blockModel->expects($this->exactly(12))
            ->method('save')
            ->will($this->returnValue(true));
        
        $this->blockModel->expects($this->any())
            ->method('retrieveContentsBySlotName')
            ->will($this->returnValue(array($this->setUpBlock())));
        
        $this->blockModel->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(true));
        
        $slot = new AlSlot('test', array('repeated' => 'page'));
        $converter = new AlSlotConverterToPage($slot, $this->pageContents, $this->languageModel, $this->pageModel, $this->blockModel);
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
    
    private function setUpLanguage($id)
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');        
        $block->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));
        
        return $block;
    }
    
    private function setUpPage($id)
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');        
        $block->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));
        
        return $block;
    }
}