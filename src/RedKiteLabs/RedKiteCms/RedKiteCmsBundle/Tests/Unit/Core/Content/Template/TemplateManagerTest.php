<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Template;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Base\ContentManagerBase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Template\TemplateManager;
use RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\Slot;

/**
 * TemplateManagerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class TemplateManagerTest extends ContentManagerBase
{
    protected function setUp()
    {
        parent::setUp();
        
        $this->themeSlots = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\ThemeSlotsInterface');
        $this->template = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Template\Template')
                           ->disableOriginalConstructor()
                            ->getMock();

        $this->pageBlocks = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\PageBlocks\PageBlocks')
                           ->disableOriginalConstructor()
                            ->getMock();

        $this->pageBlocks->expects($this->any())
            ->method('getIdPage')
            ->will($this->returnValue(2));

        $this->pageBlocks->expects($this->any())
            ->method('getIdLanguage')
            ->will($this->returnValue(2));

        $this->validator = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\ParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->blockManager = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBaseBlocksBundle\Core\Block\Image\BlockManagerImage')
                                ->disableOriginalConstructor()
                                ->getMock();
        $this->blockRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\BlockRepositoryPropel')
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));

        $this->factory = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManagerFactoryInterface');
        $this->factory->expects($this->any())
            ->method('createBlockManager')
            ->will($this->returnValue($this->blockManager));
    }
    
    public function testClone()
    { 
        $this->themeSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array()))
        ;

        $this->template->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array()))
        ;
            
        $this->pageBlocks->expects($this->once())
                        ->method('getBlocks')
                        ->will($this->returnValue(array()))
        ;                    
                    
        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        $templateManager->refresh($this->themeSlots, $this->template, $this->pageBlocks);
        
        $this->assertNotSame($templateManager, clone($templateManager));
    }
    
    public function testBlockManagerFactoryInjectedBySetters()
    {        
        $blockManagerFactory = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManagerFactoryInterface');
        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        
        $this->assertEquals($templateManager, $templateManager->setBlockManagerFactory($blockManagerFactory));
        $this->assertEquals($blockManagerFactory, $templateManager->getBlockManagerFactory());
        $this->assertNotSame($this->factoryRepository, $templateManager->getBlockManagerFactory());
    }
    
    public function testBlockRepositoryInjectedBySetters()
    {
        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        $blockRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface')
                                ->disableOriginalConstructor()
                                ->getMock();
        $this->assertEquals($templateManager, $templateManager->setBlockRepository($blockRepository));
        $this->assertEquals($blockRepository, $templateManager->getBlockRepository());
        $this->assertNotSame($this->blockRepository, $templateManager->getBlockRepository());
    }
    
    public function testGetSlotManagerReturnsNullWhenTheArgumentIsNotAString()
    {
        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        $this->assertNull($templateManager->getSlotManager(array()));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage exception_slotToArray_accepts_only_strings
     */
    public function testSlotsToArrayAcceptsOnlyStrings()
    {
        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        $templateManager->slotToArray(array());
    }
    
    public function testSlotsToArrayReturnsAnEmptyArrayWhenSlotDoesNotExist()
    {
        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        $this->assertEmpty($templateManager->slotToArray('fake'));
    }
    
    public function testSlotManagersAreNotInstantiatedBecauseTheTemplateHasNotBeenSet()
    {
        $this->themeSlots->expects($this->never())
                ->method('getSlots')
        ;
        
        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        $templateManager->refresh($this->themeSlots);
    }
    
    /**
     * @dataProvider slotManagerProvider
     */
    public function testCreatesASlotManagerWhenAnyBlockManagerHasBeenInstantiated($slots, $templateSlots, $slotBlocks, $blocks, $generatedSlotManagers = 1)
    {
        $this->themeSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));

        $this->template->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($templateSlots));
            
        $pageBlocks = null;
        if (null !== $slotBlocks) {
            $c = 0;
            foreach ($slotBlocks as $slotName) {
                if (null !== $slotName) {                
                    $this->pageBlocks->expects($this->at($c))
                            ->method('getSlotBlocks')
                            ->with($slotName)
                            ->will($this->returnValue(array()));                    
                }
                $c++;
            }
            
            $this->pageBlocks->expects($this->once())
                    ->method('getBlocks')
                    ->will($this->returnValue($blocks));
            $pageBlocks = $this->pageBlocks;
        }

        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        $templateManager->refresh($this->themeSlots, $this->template, $pageBlocks);
        $this->assertSame($this->themeSlots, $templateManager->getThemeSlots());
        $this->assertSame($this->template, $templateManager->getTemplate());
        $this->assertSame($pageBlocks, $templateManager->getPageBlocks());
        
        $slotManager = $templateManager->getSlotManager('logo');
        $this->assertInstanceOf('\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\SlotManager', $slotManager);
        $this->assertCount($generatedSlotManagers, $templateManager->getSlotManagers());
        $this->assertCount($generatedSlotManagers, $templateManager->slotsToArray());
        $this->assertEquals(array(), $templateManager->slotToArray('logo'));
    }
    
    public function slotManagerProvider()
    {
        return array(
            array(
                array(
                    'logo' => new Slot('logo', array('repeated' => 'page'))
                ),
                array('logo'),
                array('logo'),
                array(),
            ),
            array(
                array(
                    'logo' => new Slot('logo', array('repeated' => 'page')),
                    'logo_internal' => new Slot('logo_internal', array('repeated' => 'page'))
                ),
                array('logo'),
                array('logo'),
                array(),
            ),
            array(
                array(
                    'logo' => new Slot('logo', array('repeated' => 'page')),
                    'menu' => new Slot('menu', array('repeated' => 'site'))
                ),
                array('logo'),
                array('logo', 'menu'),
                array(),
                2,
            ),
            array(
                array(
                    'logo' => new Slot('logo', array('repeated' => 'page')),
                ),
                array('logo'),
                null,
                null,
            ),
            array(
                array(
                    'logo' => new Slot('logo', array('repeated' => 'page')),
                    'menu' => new Slot('menu', array('repeated' => 'site'))
                ),
                array('logo'),
                array('logo', 'menu', null, '6-0'), // null requires an addictional cycle where pageBlock->getSlotBlocks() is not called because of this call: $this->pageBlocks->getBlocks()
                array('6-0' => array()),
                3,
            ),
        );
    }

    public function testPopulateFailsWhenAddingANewBlockFails()
    {
        $slots = array('test' => new Slot('test', array('repeated' => 'page')),
                       'test1' => new Slot('test1', array('repeated' => 'page')),
                       'test2' => new Slot('test2', array('repeated' => 'page')));

        $this->template->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array('test', 'test1', 'test2', )));
        
        $this->themeSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));

        
        $block1= $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block1->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));

        $block2 = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block2->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));

        $this->blockManager->expects($this->exactly(2))
            ->method('save')
            ->will($this->onConsecutiveCalls(true, false));
        
        $this->blockManager->expects($this->at(0))
            ->method('get')
            ->will($this->returnValue($block1));
        
        $this->blockManager->expects($this->at(1))
            ->method('get')
            ->will($this->returnValue($block2));
        
        $this->blockRepository->expects($this->any())
            ->method('setRepositoryObject')
            ->will($this->returnSelf());
        
        $this->initEventsDispatcher('template_manager.before_populate', 'template_manager.after_populate', 'template_manager.before_populate_commit');
        
        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        $templateManager->refresh($this->themeSlots, $this->template);

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
        
        $slots = array('test' => new Slot('test', array('repeated' => 'page')));
        $this->themeSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $this->template->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array('test',)));

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));

        $this->blockManager->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException()));
        
        $this->blockManager->expects($this->any())
            ->method('get')
            ->will($this->returnValue($block));
        
        $this->blockRepository->expects($this->any())
            ->method('setRepositoryObject')
            ->will($this->returnSelf());

        $this->initEventsDispatcher('template_manager.before_populate');
        
        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        $templateManager->refresh($this->themeSlots, $this->template);
        $templateManager->populate(2, 2);
    }
    
    /**
     * @dataProvider populateArguments
     */
    public function testPopulate($skip, $times)
    {        
        $this->blockRepository->expects($this->exactly($times))
            ->method('startTransaction');

        $this->blockRepository->expects($this->exactly($times))
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $slots = array(
            'content' => new Slot('content', array('repeated' => 'page')),
            'logo' => new Slot('logo', array('repeated' => 'site')),
        );
        $this->themeSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $this->template->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array('content','logo')));

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));
        
        $this->blockManager->expects($this->any())
            ->method('get')
            ->will($this->returnValue($block));
        
        $this->blockRepository->expects($this->any())
            ->method('setRepositoryObject')
            ->will($this->returnSelf());
        
        $this->blockManager->expects($this->any())
            ->method('save')
            ->will($this->returnValue(true));

        $this->initEventsDispatcher('template_manager.before_populate', 'template_manager.after_populate', 'template_manager.before_populate_commit');
        
        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        $templateManager->refresh($this->themeSlots, $this->template);

        $result = $templateManager->populate(2, 2, $skip);
        $this->assertTrue($result);
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

        $slots = array('test' => new Slot('test', array('repeated' => 'page')));
        $this->themeSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $this->template->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array('test')));

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
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
        
        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        $templateManager->refresh($this->themeSlots, $this->template, $this->pageBlocks);

        $templateManager->clearBlocks();
    }

    public function testClearBlocksFailsWhenDeleteFailsAtLast()
    {
        $this->blockRepository->expects($this->exactly(2))
            ->method('startTransaction');

        $this->blockRepository->expects($this->exactly(2))
            ->method('rollback');

        $slots = array('test' => new Slot('test', array('repeated' => 'page')));
        $this->themeSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $this->template->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array('test')));

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
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
        
        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        $templateManager->refresh($this->themeSlots, $this->template, $this->pageBlocks);

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

        $slots = array('test' => new Slot('test', array('repeated' => 'page')));
        $this->themeSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $this->template->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array('test')));

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
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
        
        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        $templateManager->refresh($this->themeSlots, $this->template, $this->pageBlocks);

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

        $slots = array('test' => new Slot('test', array('repeated' => 'language')),
                       'test1' => new Slot('test1', array('repeated' => 'page')),
                       'test2' => new Slot('test2', array('repeated' => 'site')),
                       'test3' => new Slot('test3', array('repeated' => 'page')));
        $this->themeSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $this->template->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array('test', 'test1', 'test2', 'test3')));

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
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
        
        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        $templateManager->refresh($this->themeSlots, $this->template, $this->pageBlocks);

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

        $slots = array('test' => new Slot('test', array('repeated' => 'language')),
                       'test1' => new Slot('test1', array('repeated' => 'page')),
                       'test2' => new Slot('test2', array('repeated' => 'site')),
                       'test3' => new Slot('test3', array('repeated' => 'page')));
        $this->themeSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $this->template->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array('test', 'test1', 'test2', 'test3')));

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
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
        
        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        $templateManager->refresh($this->themeSlots, $this->template, $this->pageBlocks);
        
        $result = $templateManager->clearBlocks();
        $this->assertTrue($result);
    }

    public function testClearPageBlocksFailsWhenBlocksRemovingFails()
    {
        $this->blockRepository->expects($this->exactly(3))
            ->method('startTransaction');

        $this->blockRepository->expects($this->exactly(3))
            ->method('rollback');

        $slots = array('test' => new Slot('test', array('repeated' => 'page')));
        $this->themeSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $this->template->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array('test')));

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
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
        
        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        $templateManager->refresh($this->themeSlots, $this->template, $this->pageBlocks);

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

        $slots = array('test' => new Slot('test', array('repeated' => 'page')));
        $this->themeSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $this->template->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array('test')));

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
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
        
        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        $templateManager->refresh($this->themeSlots, $this->template, $this->pageBlocks);

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

        $slots = array('test' => new Slot('test', array('repeated' => 'page')));
        $this->themeSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($slots));
        
        $this->template->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array('test')));

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
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
        
        $templateManager = new TemplateManager($this->eventsHandler, $this->factoryRepository, $this->factory, $this->validator);
        $templateManager->refresh($this->themeSlots, $this->template, $this->pageBlocks);

        $result = $templateManager->clearPageBlocks(3, 3);
        $this->assertTrue($result);
    }
    
    public function populateArguments()
    {
        return array(
            array(
                false,
                3,
            ),
            array(
                true,
                2,
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
