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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Template;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;

/**
 * AlTemplateManagerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlTemplateManagerTest extends TestCase
{
    private $dispatcher;

    protected function setUp()
    {
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->templateSlots = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface');
        $this->template = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate')
                           ->disableOriginalConstructor()
                            ->getMock();
        $this->template->expects($this->any())
            ->method('getTemplateSlots')
            ->will($this->returnValue($this->templateSlots));

        $this->pageContents = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks')
                           ->disableOriginalConstructor()
                            ->getMock();

        $this->pageContents->expects($this->any())
            ->method('getIdPage')
            ->will($this->returnValue(2));

        $this->pageContents->expects($this->any())
            ->method('getIdLanguage')
            ->will($this->returnValue(2));

        $this->validator = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->blockManager = $this->getMockBuilder('AlphaLemon\Block\TextBundle\Core\Block\AlBlockManagerText')
                                ->disableOriginalConstructor()
                                ->getMock();
        $this->blockRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));

        $this->factory = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $this->factory->expects($this->any())
            ->method('createBlockManager')
            ->will($this->returnValue($this->blockManager));
    }
    
    public function testBlockManagerFactoryInjectedBySetters()
    {        
        $blockManagerFactory = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');        
        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, null, $this->pageContents, $this->factory, $this->validator);
        
        $this->assertEquals($templateManager, $templateManager->setBlockManagerFactory($blockManagerFactory));
        $this->assertEquals($blockManagerFactory, $templateManager->getBlockManagerFactory());
        $this->assertNotSame($templateManager, $templateManager->getBlockManagerFactory());
    }
    
    public function testBlockRepositoryInjectedBySetters()
    {
        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, null, $this->pageContents, $this->factory, $this->validator);
        $blockRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\BlockRepositoryInterface')
                                ->disableOriginalConstructor()
                                ->getMock();
        $this->assertEquals($templateManager, $templateManager->setBlockRepository($blockRepository));
        $this->assertEquals($blockRepository, $templateManager->getBlockRepository());
        $this->assertNotSame($templateManager, $templateManager->getBlockRepository());
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage slotToArray accepts only strings
     */
    public function testSlotsToArrayAcceptsOnlyStrings()
    {
        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, null, $this->pageContents, $this->factory, $this->validator);
        $templateManager->slotToArray(array());
    }
    
    public function testSlotsToArrayReturnsAnEmptyArrayWhenSlotDoesNotExist()
    {
        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, null, $this->pageContents, $this->factory, $this->validator);
        $this->assertEmpty($templateManager->slotToArray('fake'));
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterIsEmptyException
     */
    public function testAnExceptionIsThrownWhenTheTemplateSlotsObjectIsNull()
    {
        $template = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate')
                           ->disableOriginalConstructor()
                            ->getMock();
        $template->expects($this->any())
            ->method('getTemplateSlots')
            ->will($this->returnValue(null));

        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, $template, $this->pageContents, $this->factory, $this->validator);
        $templateManager->refresh();
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\Exception\EmptyTemplateSlotsException
     */
    public function testAnExceptionIsThrownWhenAnySlotIsGiven()
    {
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array()));

        $this->pageContents->expects($this->never())
                ->method('getSlotBlocks')
                ->will($this->returnValue(array()));

        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, $this->template, $this->pageContents, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
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

        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, $this->template, $this->pageContents, $this->factory, $this->validator);
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

        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, $this->template, $this->pageContents, $this->factory, $this->validator);
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

        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, $this->template, $this->pageContents, $this->factory, $this->validator);
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

        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, $this->template, $this->pageContents, $this->factory, $this->validator);
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

        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, $this->template, $this->pageContents, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();
        $templateManager->populate(2, 2);
    }

    public function testPopulate()
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

        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, $this->template, $this->pageContents, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();

        $result = $templateManager->populate(2, 2);
        $this->assertTrue($result);
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\Exception\EmptyTemplateSlotsException
     */
    public function testAnyBlockIsClearedWhenSlotsAreEmpty()
    {
        $this->templateSlots->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue(array()));

        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, $this->template, $this->pageContents, $this->factory, $this->validator);
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

        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, $this->template, $this->pageContents, $this->factory, $this->validator);
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

        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, $this->template, $this->pageContents, $this->factory, $this->validator);
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

        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, $this->template, $this->pageContents, $this->factory, $this->validator);
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

        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, $this->template, $this->pageContents, $this->factory, $this->validator);
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

        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, $this->template, $this->pageContents, $this->factory, $this->validator);
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

        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));

        $this->pageContents->expects($this->exactly(3))
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));

        $this->pageContents->expects($this->exactly(3))
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));

        $this->pageContents->expects($this->once())
                ->method('setIdLanguage')
                ->with(3)
                ->will($this->returnSelf());

        $this->pageContents->expects($this->once())
                ->method('setIdPage')
                ->with(3)
                ->will($this->returnSelf());

        $this->blockManager->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(false));

        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, $this->template, $this->pageContents, $this->factory, $this->validator);
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

        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));

        $this->pageContents->expects($this->exactly(2))
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));

        $this->pageContents->expects($this->exactly(2))
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));

        $this->pageContents->expects($this->once())
                ->method('setIdLanguage')
                ->with(3)
                ->will($this->returnSelf());

        $this->pageContents->expects($this->once())
                ->method('setIdPage')
                ->with(3)
                ->will($this->returnSelf());

        $this->blockManager->expects($this->once())
            ->method('delete')
            ->will($this->throwException(new \RuntimeException()));

        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, $this->template, $this->pageContents, $this->factory, $this->validator);
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

        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));

        $this->pageContents->expects($this->exactly(3))
                ->method('getSlotBlocks')
                ->will($this->returnValue(array($block)));

        $this->pageContents->expects($this->exactly(3))
                ->method('getBlocks')
                ->will($this->returnValue(array('test' => array($block))));

        $this->pageContents->expects($this->once())
                ->method('setIdLanguage')
                ->with(3)
                ->will($this->returnSelf());

        $this->pageContents->expects($this->once())
                ->method('setIdPage')
                ->with(3)
                ->will($this->returnSelf());

        $this->blockManager->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(true));

        $templateManager = new AlTemplateManager($this->dispatcher, $this->factoryRepository, $this->template, $this->pageContents, $this->factory, $this->validator);
        $templateManager->setTemplateSlots($this->templateSlots)
                ->refresh();

        $result = $templateManager->clearPageBlocks(3, 3);
        $this->assertTrue($result);
    }
}
