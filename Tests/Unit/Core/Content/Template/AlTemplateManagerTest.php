<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infblockModelation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 * 
 * @license    GPL LICENSE Version 2.0
 * 
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Template;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;

class AlTemplateManagerTest extends TestCase 
{    
    private $dispatcher;
    private $translator;
    
    protected function setUp() 
    {
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $this->templateSlots = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface');
        $this->pageContents = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageContentsContainer\AlPageContentsContainerInterface');        
        $this->validator = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->blockManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText')
                                ->disableOriginalConstructor()
                                ->getMock();
        $this->blockModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlBlockModelPropel')
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $this->factory = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $this->factory->expects($this->any())
            ->method('createBlock')
            ->will($this->returnValue($this->blockManager));
    }
    
    public function testAnySlotManagerIsInstantiatedWhenAnySlotISGiven()
    {
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array()));
        
        $this->pageContents->expects($this->never())
                ->method('getSlotBlocks')
                ->will($this->returnValue(array()));
        
        $templateManager = new AlTemplateManager($this->dispatcher, $this->translator, $this->pageContents, $this->blockModel, $this->validator, $this->factory);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
        
        $this->assertEquals(0, count($templateManager->getSlotManagers()));
        $slotManager = $templateManager->getSlotManager('test');
        $this->assertNull($slotManager);
    }
    
    public function testCreatesASlotManagerWithoutAnyBlockManagerInstantiated()
    {
        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $this->pageContents->expects($this->once())
                ->method('getSlotBlocks')
                ->will($this->returnValue(array()));
        
        $this->pageContents->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array()));
        
        $templateManager = new AlTemplateManager($this->dispatcher, $this->translator, $this->pageContents, $this->blockModel, $this->validator, $this->factory);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
        
        $slotManager = $templateManager->getSlotManager('test');
        $this->assertEquals(1, count($templateManager->getSlotManagers())); 
        $this->assertInstanceOf('\AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager', $slotManager);
        $this->assertEquals(1, count($templateManager->slotsToArray())); 
        $this->assertEmpty($templateManager->slotToArray('test'));
    }
    
    public function testCreatesASlotManagerWithABlockManagerInstantiated()
    {
        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');  
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1)); 
        
        $this->pageContents->expects($this->once())
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));
        
        $this->pageContents->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));
        
        $templateManager = new AlTemplateManager($this->dispatcher, $this->translator, $this->pageContents, $this->blockModel, $this->validator, $this->factory);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
        
        $slotManager = $templateManager->getSlotManager('test');
        $this->assertInstanceOf('\AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager', $slotManager->first());        
        $this->assertEquals(1, count($templateManager->slotsToArray())); 
        $this->assertEquals(1, count($templateManager->slotToArray('test')));         
    }
    
    public function testCreatesASlotManagerFromPageContent()
    {
        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');  
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1)); 
        
        $this->pageContents->expects($this->exactly(2))
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));
        
        $this->pageContents->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test1' => array($block))));
        
        $templateManager = new AlTemplateManager($this->dispatcher, $this->translator, $this->pageContents, $this->blockModel, $this->validator, $this->factory);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
        
        $slotManager = $templateManager->getSlotManager('test1');
        $this->assertInstanceOf('\AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager', $slotManager->first());
    }
    
    public function testPopulateFailsWhenAddingANewBlockFails()
    {
        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')), 
                       'test1' => new AlSlot('test1', array('repeated' => 'page')));
        
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $block1= $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');  
        $block1->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1)); 
        
        $block2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');  
        $block2->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2)); 
        
        $this->pageContents->expects($this->exactly(2))
                ->method('getSlotBlocks')
                ->will($this->onConsecutiveCalls(array($block1), array($block2)));
        
        $this->pageContents->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => $block1, 'test1' => $block2)));
        
        $this->blockManager->expects($this->exactly(2))
            ->method('save')
            ->will($this->onConsecutiveCalls(true, false));
        
        $templateManager = new AlTemplateManager($this->dispatcher, $this->translator, $this->pageContents, $this->blockModel, $this->validator, $this->factory);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
        
        $result = $templateManager->populate(2, 2);
        $this->assertFalse($result);
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testPopulateThrownAnUnespectedException()
    {
        $this->blockModel->expects($this->exactly(2))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(2))
            ->method('rollBack');
        
        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');  
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1)); 
        
        $this->pageContents->expects($this->once())
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));
        
        $this->pageContents->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));
        
        $this->blockManager->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException()));
        
        $templateManager = new AlTemplateManager($this->dispatcher, $this->translator, $this->pageContents, $this->blockModel, $this->validator, $this->factory);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
        $templateManager->populate(2, 2);
    }
    
    public function testPopulate()
    {
        $this->blockModel->expects($this->exactly(2))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(2))
            ->method('commit');
        
        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');  
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1)); 
        
        $this->pageContents->expects($this->once())
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));
        
        $this->pageContents->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));
        
        $this->blockManager->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));
        
        $templateManager = new AlTemplateManager($this->dispatcher, $this->translator, $this->pageContents, $this->blockModel, $this->validator, $this->factory);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
        
        $result = $templateManager->populate(2, 2);
        $this->assertTrue($result);
    }
    
    public function testAnyBlockIsClearedWhenSlotsAreEmpty()
    {
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array()));
        
        $templateManager = new AlTemplateManager($this->dispatcher, $this->translator, $this->pageContents, $this->blockModel, $this->validator, $this->factory);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
        
        $result = $templateManager->clearBlocks();
        $this->assertNull($result);
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testClearBlocksThrownAnUnespectedException()
    {
        $this->blockModel->expects($this->exactly(2))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(2))
            ->method('rollback');
        
        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');  
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1)); 
        
        $this->pageContents->expects($this->once())
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));
        
        $this->pageContents->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));
        
        $this->blockManager->expects($this->once())
            ->method('delete')
            ->will($this->throwException(new \RuntimeException()));
        
        $templateManager = new AlTemplateManager($this->dispatcher, $this->translator, $this->pageContents, $this->blockModel, $this->validator, $this->factory);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
        
        $result = $templateManager->clearBlocks();
        $this->assertFalse($result);
    }
    
    public function testClearBlocksFailsWhenDeleteFailsAtLast()
    {
        $this->blockModel->expects($this->exactly(2))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(2))
            ->method('rollback');
        
        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');  
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1)); 
        
        $this->pageContents->expects($this->once())
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));
        
        $this->pageContents->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));
        
        $this->blockManager->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(false));
        
        $templateManager = new AlTemplateManager($this->dispatcher, $this->translator, $this->pageContents, $this->blockModel, $this->validator, $this->factory);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
        
        $result = $templateManager->clearBlocks();
        $this->assertFalse($result);
    }
    
    public function testClearBlocks()
    {
        $this->blockModel->expects($this->exactly(2))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(2))
            ->method('commit');
        
        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');  
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1)); 
        
        $this->pageContents->expects($this->once())
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));
        
        $this->pageContents->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));
        
        $this->blockManager->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(true));
        
        $templateManager = new AlTemplateManager($this->dispatcher, $this->translator, $this->pageContents, $this->blockModel, $this->validator, $this->factory);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
        
        $result = $templateManager->clearBlocks();
        $this->assertTrue($result);
    }
    
    public function testClearBlocksForAllSlots()
    {
        $this->blockModel->expects($this->exactly(5))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(5))
            ->method('commit');
        
        $slots = array('test' => new AlSlot('test', array('repeated' => 'language')),
                       'test1' => new AlSlot('test1', array('repeated' => 'page')),
                       'test2' => new AlSlot('test2', array('repeated' => 'site')),
                       'test3' => new AlSlot('test3', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');  
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1)); 
        
        $this->pageContents->expects($this->exactly(4))
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));
        
        $this->pageContents->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));
        
        $this->blockManager->expects($this->exactly(4))
            ->method('delete')
            ->will($this->returnValue(true));
        
        $templateManager = new AlTemplateManager($this->dispatcher, $this->translator, $this->pageContents, $this->blockModel, $this->validator, $this->factory);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
        
        $result = $templateManager->clearBlocks(false);
        $this->assertTrue($result);
    }
    
    public function testClearBlocksIgnoringRepeatedSlots()
    {
        $this->blockModel->expects($this->exactly(3))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(3))
            ->method('commit');
        
        $slots = array('test' => new AlSlot('test', array('repeated' => 'language')),
                       'test1' => new AlSlot('test1', array('repeated' => 'page')),
                       'test2' => new AlSlot('test2', array('repeated' => 'site')),
                       'test3' => new AlSlot('test3', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');  
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1)); 
        
        $this->pageContents->expects($this->exactly(4))
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));
        
        $this->pageContents->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));
        
        $this->blockManager->expects($this->exactly(2))
            ->method('delete')
            ->will($this->returnValue(true));
        
        $templateManager = new AlTemplateManager($this->dispatcher, $this->translator, $this->pageContents, $this->blockModel, $this->validator, $this->factory);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
        
        $result = $templateManager->clearBlocks();
        $this->assertTrue($result);
    }
    
    public function testClearPageBlocks()
    {
        $this->markTestSkipped(
            'Test page contents container before this one'
        );
    }
}