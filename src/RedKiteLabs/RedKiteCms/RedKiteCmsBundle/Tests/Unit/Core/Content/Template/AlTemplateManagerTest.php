<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Template;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Base\AlContentManagerBase;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager;
use RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlSlot;

/**
 * AlTemplateManagerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlTemplateManagerTest extends AlContentManagerBase
{
    protected function setUp()
    {
        parent::setUp();
        
        $this->templateSlots = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface');
        $this->template = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate')
                           ->disableOriginalConstructor()
                            ->getMock();
        $this->template->expects($this->any())
            ->method('getTemplateSlots')
            ->will($this->returnValue($this->templateSlots));

        $this->pageBlocks = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocks')
                           ->disableOriginalConstructor()
                            ->getMock();

        $this->pageBlocks->expects($this->any())
            ->method('getIdPage')
            ->will($this->returnValue(2));

        $this->pageBlocks->expects($this->any())
            ->method('getIdLanguage')
            ->will($this->returnValue(2));

        $this->validator = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->blockManager = $this->getMockBuilder('AlphaLemon\Block\ImageBundle\Core\Block\AlBlockManagerImage')
                                ->disableOriginalConstructor()
                                ->getMock();
        $this->blockRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));

        $this->factory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $this->factory->expects($this->any())
            ->method('createBlockManager')
            ->will($this->returnValue($this->blockManager));
    }
    
    public function testClone()
    { 
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, null, $this->pageBlocks, $this->factory, $this->validator);
        
        $this->assertNotSame($templateManager, clone($templateManager));
    }
        
    public function testTemplateInjectedBySetters()
    {        
        $template = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate')
                         ->disableOriginalConstructor()
                         ->getMock();
        $template->expects($this->any())
            ->method('getTemplateSlots')
            ->will($this->returnValue($this->templateSlots));
        
        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $this->pageBlocks->expects($this->once())
                ->method('getSlotBlocks')
                ->will($this->returnValue(array()));
        
        $this->pageBlocks->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array()));
        
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, null, $this->pageBlocks, $this->factory, $this->validator);
        
        $this->assertEquals($templateManager, $templateManager->setTemplate($template));
        $this->assertEquals($template, $templateManager->getTemplate());
        $this->assertNotSame($this->template, $templateManager->getTemplate());
    }
    
    public function testNothingIsIntantiatedWhenTemplateIsNull()
    {   
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, null, $this->pageBlocks, $this->factory, $this->validator);
        $templateManager->refresh();
        $this->assertEmpty($templateManager->getSlotManagers());
    }
    
    public function testTemplateSlotsInjectedBySetters()
    {        
        $templateSlots = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface');
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, $this->template, $this->pageBlocks, $this->factory, $this->validator);
        
        $this->assertEquals($templateManager, $templateManager->setTemplateSlots($templateSlots));
        $this->assertEquals($templateSlots, $templateManager->getTemplateSlots());
    }
    
    public function testPageBlocksInjectedBySetters()
    {        
        $pageBlocks = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocks')
                           ->disableOriginalConstructor()
                           ->getMock();
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, null, $this->pageBlocks, $this->factory, $this->validator);
        
        $this->assertEquals($templateManager, $templateManager->setPageBlocks($pageBlocks));
        $this->assertEquals($pageBlocks, $templateManager->getPageBlocks());
        $this->assertNotSame($this->pageBlocks, $templateManager->getPageBlocks());
    }
    
    public function testBlockManagerFactoryInjectedBySetters()
    {        
        $blockManagerFactory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');        
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, null, $this->pageBlocks, $this->factory, $this->validator);
        
        $this->assertEquals($templateManager, $templateManager->setBlockManagerFactory($blockManagerFactory));
        $this->assertEquals($blockManagerFactory, $templateManager->getBlockManagerFactory());
        $this->assertNotSame($this->factoryRepository, $templateManager->getBlockManagerFactory());
    }
    
    public function testBlockRepositoryInjectedBySetters()
    {
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, null, $this->pageBlocks, $this->factory, $this->validator);
        $blockRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface')
                                ->disableOriginalConstructor()
                                ->getMock();
        $this->assertEquals($templateManager, $templateManager->setBlockRepository($blockRepository));
        $this->assertEquals($blockRepository, $templateManager->getBlockRepository());
        $this->assertNotSame($this->blockRepository, $templateManager->getBlockRepository());
    }
    
    public function testGetSlotManagerReturnsNullWhenTheArgumentIsNotAString()
    {
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, null, $this->pageBlocks, $this->factory, $this->validator);
        $this->assertNull($templateManager->getSlotManager(array()));
    }
    
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage slotToArray method accepts only strings
     */
    public function testSlotsToArrayAcceptsOnlyStrings()
    {
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, null, $this->pageBlocks, $this->factory, $this->validator);
        $templateManager->slotToArray(array());
    }
    
    public function testSlotsToArrayReturnsAnEmptyArrayWhenSlotDoesNotExist()
    {
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, null, $this->pageBlocks, $this->factory, $this->validator);
        $this->assertEmpty($templateManager->slotToArray('fake'));
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentIsEmptyException
     */
    public function testAnExceptionIsThrownWhenTheTemplateSlotsObjectIsNull()
    {
        $template = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate')
                           ->disableOriginalConstructor()
                            ->getMock();
        $template->expects($this->any())
            ->method('getTemplateSlots')
            ->will($this->returnValue(null));

        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, $template, $this->pageBlocks, $this->factory, $this->validator);
        $templateManager->refresh();
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\Exception\EmptyTemplateSlotsException
     */
    public function testAnExceptionIsThrownWhenAnySlotIsGiven()
    {
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array()));

        $this->pageBlocks->expects($this->never())
                ->method('getSlotBlocks')
                ->will($this->returnValue(array()));

        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, $this->template, $this->pageBlocks, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
    }

    public function testCreatesASlotManagerWithoutAnyBlockManagerInstantiated()
    {
        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));

        $this->pageBlocks->expects($this->once())
                ->method('getSlotBlocks')
                ->will($this->returnValue(array()));

        $this->pageBlocks->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array()));

        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, $this->template, $this->pageBlocks, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();

        $slotManager = $templateManager->getSlotManager('test');
        $this->assertEquals(1, count($templateManager->getSlotManagers()));
        $this->assertInstanceOf('\RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\AlSlotManager', $slotManager);
        $this->assertEquals(1, count($templateManager->slotsToArray()));
        $this->assertEmpty($templateManager->slotToArray('test'));
    }

    public function testCreatesASlotManagerWithABlockManagerInstantiated()
    {
        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));

        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));

        $this->pageBlocks->expects($this->once())
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));

        $this->pageBlocks->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));

        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, $this->template, $this->pageBlocks, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();

        $slotManager = $templateManager->getSlotManager('test');
        $this->assertInstanceOf('\RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManager', $slotManager->first());
        $this->assertEquals(1, count($templateManager->slotsToArray()));
        $this->assertEquals(1, count($templateManager->slotToArray('test')));
    }

    public function testCreatesASlotManagerFromPageContent()
    {
        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));

        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));

        $this->pageBlocks->expects($this->exactly(2))
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));

        $this->pageBlocks->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test1' => array($block))));

        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, $this->template, $this->pageBlocks, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();

        $slotManager = $templateManager->getSlotManager('test1');
        $this->assertInstanceOf('\RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManager', $slotManager->first());
    }

    public function testPopulateFailsWhenAddingANewBlockFails()
    {
        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')),
                       'test1' => new AlSlot('test1', array('repeated' => 'page')),
                       'test1' => new AlSlot('test1', array('repeated' => 'page')));

        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));

        $block1= $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block1->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));

        $block2 = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block2->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $this->pageBlocks->expects($this->exactly(2))
                ->method('getSlotBlocks')
                ->will($this->onConsecutiveCalls(array($block1), array($block2)));

        $this->pageBlocks->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => $block1, 'test1' => $block2)));

        $this->blockManager->expects($this->exactly(2))
            ->method('save')
            ->will($this->onConsecutiveCalls(true, false));
        
        $this->initEventsDispatcher('template_manager.before_populate', 'template_manager.after_populate', 'template_manager.before_populate_commit');
        
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, $this->template, $this->pageBlocks, $this->factory, $this->validator);
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
        $this->blockRepository->expects($this->exactly(2))
            ->method('startTransaction');

        $this->blockRepository->expects($this->exactly(2))
            ->method('rollBack');

        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));

        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));

        $this->pageBlocks->expects($this->once())
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));

        $this->pageBlocks->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));

        $this->blockManager->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException()));

        $this->initEventsDispatcher('template_manager.before_populate');
        
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, $this->template, $this->pageBlocks, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
        $templateManager->populate(2, 2);
    }
    
    /**
     * @dataProvider populateArguments
     */
    public function testPopulate($skip, $tt)
    {
        $times = 3 + $tt;
        $this->blockRepository->expects($this->exactly($times))
            ->method('startTransaction');

        $this->blockRepository->expects($this->exactly($times))
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $slots = array(
            'content' => new AlSlot('content', array('repeated' => 'page')),
            'logo' => new AlSlot('logo', array('repeated' => 'site')),
        );
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));

        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));

        $this->pageBlocks->expects($this->any())
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));

        $this->pageBlocks->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));

        $saveTimes = 2 + $tt;
        $this->blockManager->expects($this->exactly($saveTimes))
            ->method('save')
            ->will($this->returnValue(true));

        $this->initEventsDispatcher('template_manager.before_populate', 'template_manager.after_populate', 'template_manager.before_populate_commit');
        
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, $this->template, $this->pageBlocks, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();

        $result = $templateManager->populate(2, 2, $skip);
        $this->assertTrue($result);
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\Exception\EmptyTemplateSlotsException
     */
    public function testAnyBlockIsClearedWhenSlotsAreEmpty()
    {
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array()));

        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, $this->template, $this->pageBlocks, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testClearBlocksThrownAnUnespectedException()
    {
        $this->blockRepository->expects($this->exactly(2))
            ->method('startTransaction');

        $this->blockRepository->expects($this->exactly(2))
            ->method('rollback');

        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));

        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));

        $this->pageBlocks->expects($this->once())
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));

        $this->pageBlocks->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));

        $this->blockManager->expects($this->once())
            ->method('delete')
            ->will($this->throwException(new \RuntimeException()));

        $this->initEventsDispatcher('template_manager.before_clear_blocks');
        
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, $this->template, $this->pageBlocks, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();

        $result = $templateManager->clearBlocks();
        $this->assertFalse($result);
    }

    public function testClearBlocksFailsWhenDeleteFailsAtLast()
    {
        $this->blockRepository->expects($this->exactly(2))
            ->method('startTransaction');

        $this->blockRepository->expects($this->exactly(2))
            ->method('rollback');

        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));

        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));

        $this->pageBlocks->expects($this->once())
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));

        $this->pageBlocks->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));

        $this->blockManager->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(false));

        $this->initEventsDispatcher('template_manager.before_clear_blocks', 'template_manager.after_clear_blocks', 'template_manager.before_clear_blocks_commit');
        
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, $this->template, $this->pageBlocks, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();

        $result = $templateManager->clearBlocks();
        $this->assertFalse($result);
    }

    public function testClearBlocks()
    {
        $this->blockRepository->expects($this->exactly(2))
            ->method('startTransaction');

        $this->blockRepository->expects($this->exactly(2))
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));

        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));

        $this->pageBlocks->expects($this->once())
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));

        $this->pageBlocks->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));

        $this->blockManager->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(true));

        $this->initEventsDispatcher('template_manager.before_clear_blocks', 'template_manager.after_clear_blocks', 'template_manager.before_clear_blocks_commit');
        
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, $this->template, $this->pageBlocks, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();

        $result = $templateManager->clearBlocks();
        $this->assertTrue($result);
    }

    public function testClearBlocksForAllSlots()
    {
        $this->blockRepository->expects($this->exactly(5))
            ->method('startTransaction');

        $this->blockRepository->expects($this->exactly(5))
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $slots = array('test' => new AlSlot('test', array('repeated' => 'language')),
                       'test1' => new AlSlot('test1', array('repeated' => 'page')),
                       'test2' => new AlSlot('test2', array('repeated' => 'site')),
                       'test3' => new AlSlot('test3', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));

        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));

        $this->pageBlocks->expects($this->exactly(4))
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));

        $this->pageBlocks->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));

        $this->blockManager->expects($this->exactly(4))
            ->method('delete')
            ->will($this->returnValue(true));

        $this->initEventsDispatcher('template_manager.before_clear_blocks', 'template_manager.after_clear_blocks', 'template_manager.before_clear_blocks_commit');
        
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, $this->template, $this->pageBlocks, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();

        $result = $templateManager->clearBlocks(false);
        $this->assertTrue($result);
    }

    public function testClearBlocksIgnoringRepeatedSlots()
    {
        $this->blockRepository->expects($this->exactly(3))
            ->method('startTransaction');

        $this->blockRepository->expects($this->exactly(3))
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $slots = array('test' => new AlSlot('test', array('repeated' => 'language')),
                       'test1' => new AlSlot('test1', array('repeated' => 'page')),
                       'test2' => new AlSlot('test2', array('repeated' => 'site')),
                       'test3' => new AlSlot('test3', array('repeated' => 'page')));
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));

        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));

        $this->pageBlocks->expects($this->exactly(4))
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));

        $this->pageBlocks->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));

        $this->blockManager->expects($this->exactly(2))
            ->method('delete')
            ->will($this->returnValue(true));

        $this->initEventsDispatcher('template_manager.before_clear_blocks', 'template_manager.after_clear_blocks', 'template_manager.before_clear_blocks_commit');
        
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, $this->template, $this->pageBlocks, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
        
        $result = $templateManager->clearBlocks();
        $this->assertTrue($result);
    }

    public function testClearPageBlocksFailsWhenBlocksRemovingFails()
    {
         $this->blockRepository->expects($this->exactly(3))
            ->method('startTransaction');

        $this->blockRepository->expects($this->exactly(3))
            ->method('rollback');

        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->exactly(3))
                ->method('getSlots')
                ->will($this->returnValue($slots));

        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));

        $this->pageBlocks->expects($this->exactly(3))
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));

        $this->pageBlocks->expects($this->exactly(3))
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));

        $this->pageBlocks->expects($this->once())
                ->method('setIdLanguage')
                ->with(3)
                ->will($this->returnSelf());

        $this->pageBlocks->expects($this->once())
                ->method('setIdPage')
                ->with(3)
                ->will($this->returnSelf());

        $this->blockManager->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(false));

        $this->initEventsDispatcher('template_manager.before_clear_blocks', 'template_manager.after_clear_blocks', 'template_manager.before_clear_blocks_commit');
        
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, $this->template, $this->pageBlocks, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();

        $result = $templateManager->clearPageBlocks(3, 3);
        $this->assertFalse($result);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testClearPageBlocksFailsWhenAnUnexpectedExceptionIsThrown()
    {
        $this->blockRepository->expects($this->exactly(3))
            ->method('startTransaction');

        $this->blockRepository->expects($this->exactly(3))
            ->method('rollback');

        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->exactly(2))
                ->method('getSlots')
                ->will($this->returnValue($slots));

        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));

        $this->pageBlocks->expects($this->exactly(2))
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));

        $this->pageBlocks->expects($this->exactly(2))
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));

        $this->pageBlocks->expects($this->once())
                ->method('setIdLanguage')
                ->with(3)
                ->will($this->returnSelf());

        $this->pageBlocks->expects($this->once())
                ->method('setIdPage')
                ->with(3)
                ->will($this->returnSelf());

        $this->blockManager->expects($this->once())
            ->method('delete')
            ->will($this->throwException(new \RuntimeException()));

        $this->initEventsDispatcher('template_manager.before_clear_blocks');
        
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, $this->template, $this->pageBlocks, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();

        $result = $templateManager->clearPageBlocks(3, 3);
        $this->assertFalse($result);
    }

    public function testClearPageBlocks()
    {
         $this->blockRepository->expects($this->exactly(3))
            ->method('startTransaction');

        $this->blockRepository->expects($this->exactly(3))
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $slots = array('test' => new AlSlot('test', array('repeated' => 'page')));
        $this->templateSlots->expects($this->exactly(3))
                ->method('getSlots')
                ->will($this->returnValue($slots));

        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));

        $this->pageBlocks->expects($this->exactly(3))
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));

        $this->pageBlocks->expects($this->exactly(3))
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));

        $this->pageBlocks->expects($this->once())
                ->method('setIdLanguage')
                ->with(3)
                ->will($this->returnSelf());

        $this->pageBlocks->expects($this->once())
                ->method('setIdPage')
                ->with(3)
                ->will($this->returnSelf());

        $this->blockManager->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(true));

        $this->initEventsDispatcher('template_manager.before_clear_blocks', 'template_manager.after_clear_blocks', 'template_manager.before_clear_blocks_commit');
        
        $templateManager = new AlTemplateManager($this->eventsHandler, $this->factoryRepository, $this->template, $this->pageBlocks, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();

        $result = $templateManager->clearPageBlocks(3, 3);
        $this->assertTrue($result);
    }
    
    public function populateArguments()
    {
        return array(
            array(
                false,
                1,
            ),
            array(
                true,
                0,
            ),
        );
    }
    
    private function initEventsDispatcher($beforeEvent = null, $afterEvent = null, $beforeCommitEvent = null)
    {
        $dispatcher = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        
        if (null !== $beforeEvent) {
            $dispatcher->expects($this->at(0))
                    ->method('dispatch')
                    ->with($beforeEvent);
        }
        
        if (null !== $beforeCommitEvent) {
            $dispatcher->expects($this->at(1))
                    ->method('dispatch')
                    ->with($beforeCommitEvent);
        }
        
        if (null !== $afterEvent) {
            $dispatcher->expects($this->at(2))
                    ->method('dispatch')
                    ->with($afterEvent);
        }
        
        $this->eventsHandler->expects($this->once())
                ->method('getEventDispatcher')
                ->will($this->returnValue($dispatcher));
    }
}
