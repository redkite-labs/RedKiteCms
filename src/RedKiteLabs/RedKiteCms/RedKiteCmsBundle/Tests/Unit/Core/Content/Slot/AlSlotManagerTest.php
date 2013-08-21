<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Slot;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Base\AlContentManagerBase;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\AlSlotManager;
use RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlSlot;

/**
 * AlSlotsConverterFactoryTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlSlotManagerTest extends AlContentManagerBase
{
    protected function setUp()
    {
        parent::setUp();

        $this->validator = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        $factory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');

        $this->blockRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;

        $slot = new AlSlot('test', array('repeated' => 'page'));
        $this->slotManager = new AlSlotManager($this->eventsHandler, $slot, $this->blockRepository, $factory, $this->validator);
    }
    
    public function testAlSlotInjectedBySetters()
    {
        $slot = 
            $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlSlot')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;        
        
        $slot->expects($this->once())
             ->method('getSlotName')
             ->will($this->returnValue('logo'))
        ;
        
        $this->assertEquals($this->slotManager, $this->slotManager->setSlot($slot));
        $this->assertEquals($slot, $this->slotManager->getSlot());
        $this->assertEquals('logo', $this->slotManager->getSlotName());
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage setForceSlotAttributes method accepts only boolean values
     */
    public function testSetForceSlotAttributesWantsAbooleanAsArgument()
    {
        $this->slotManager->setForceSlotAttributes('fake');
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage setSkipSiteLevelBlocks method accepts only boolean values
     */
    public function testSetSkipSiteLevelBlocksWantsAbooleanAsArgument()
    {
        $this->slotManager->setSkipSiteLevelBlocks('fake');
    }
    
    public function testGetRepeated()
    {
        $this->assertEquals('page', $this->slotManager->getRepeated());
    }
    
    public function testGetForceSlotAttributes()
    {
        $this->assertFalse($this->slotManager->getForceSlotAttributes());
        $this->slotManager->setForceSlotAttributes(true);
        $this->assertTrue($this->slotManager->getForceSlotAttributes());
    }
    
    public function testBlockRepositoryInjectedBySetters()
    {
        $blockRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        $this->assertEquals($this->slotManager, $this->slotManager->setBlockRepository($blockRepository));
        $this->assertEquals($blockRepository, $this->slotManager->getBlockRepository());
        $this->assertNotSame($this->slotManager, $this->slotManager->getBlockRepository());
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     */
    public function testAddBlockFailsWhenReceivesAnInvalidLanguageId()
    {
        $this
            ->eventsHandler
            ->expects($this->never())
            ->method('createEvent')
        ;

        $factory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory
            ->expects($this->any())
            ->method('createBlockManager')
            ->will($this->throwException(new \InvalidArgumentException));

        $this->slotManager->addBlock('fake', 2);
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     */
    public function testAddBlockThrowsAnExceptionWhenReceivesAnInvalidPageId()
    {
        $this
            ->eventsHandler
            ->expects($this->never())
            ->method('createEvent')
        ;

        $factory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory
            ->expects($this->any())
            ->method('createBlockManager')
            ->will($this->throwException(new \InvalidArgumentException));

        $this->slotManager->addBlock(2, 'fake');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddBlockThrowsAnExceptionWhenReceivesAnInvalidType()
    {
        $this
            ->eventsHandler
            ->expects($this->never())
            ->method('createEvent')
        ;

        $factory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory
            ->expects($this->any())
            ->method('createBlockManager')
            ->will($this->throwException(new \InvalidArgumentException))
        ;

        $this->slotManager->addBlock(2, 2, 'fake');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAddNewBlockThrownAnUnespectedException()
    {
        $block = $this->initBlock();
        $this->setUpRepositoryBehavior(0, 1);

        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $block);
        $this->slotManager->setBlockManagerFactory($factory);

        $this
            ->blockManager
            ->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException()))
        ;

        $this->slotManager->addBlock(2, 2);
    }
    
    public function testAddNewBlockFailsWhenContentManagerSavingFails()
    {
        $block = $this->initBlock();
        $this->setUpRepositoryBehavior(0, 1);

        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $block, 'save', false);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertFalse($this->slotManager->addBlock(2, 2));
    }

    public function testAddNewBlockWithoutGivingTheClassType()
    {
        $block = $this->initBlock(null, 'Text', 2, 2);
        $this->setUpRepositoryBehavior();

        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2));
        
        $blockManagers = $this->slotManager->getBlockManagers();
        $this->assertCount(1, $blockManagers);
        $this->assertInstanceOf('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $blockManagers[0]);
        $this->assertEquals(1, $this->slotManager->length());
        $this->assertEquals(2, $this->slotManager->first()->get()->getLanguageId());
        $this->assertEquals(2, $this->slotManager->first()->get()->getPageId());

        $blockManager = $this->slotManager->last();
        $this->assertEquals("Text", $blockManager->get()->getType());
    }

    public function testAddNewBlockGivingTheClassType()
    {
        $block = $this->initBlock();
        $this->setUpRepositoryBehavior();

        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Script\AlBlockManagerScript', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, "Script"));
        $this->assertEquals(1 ,$this->slotManager->length());
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testAddNewBlockInSecondPositionFailsBecauseSomethingWasWrongAdjustingThePosition1()
    {
        $this->blockRepository
            ->expects($this->once())
            ->method('setRepositoryObject')
            ->will($this->returnSelf())
        ;

        $this->blockRepository
            ->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException()))
        ;

        $block = $this->initBlock(1, array('Text', 0), null, null, array(1, 0));
        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, "Text"));
        
        $block = $this->initBlock(null, array('Script', 0), null, null, array(3, 2));
        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Script\AlBlockManagerScript', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, "Script"));

        $blockManager = $this->getMockBuilder('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Menu\AlBlockManagerMenu')
                            ->disableOriginalConstructor()
                            ->getMock();

        $factory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory
            ->expects($this->any())
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager))
        ;
        $this->assertFalse($this->slotManager->addBlock(2, 2, "Menu", 1));
    }
    
    public function testAddNewBlockInSecondPositionFailsBecauseSomethingWasWrongAdjustingThePosition()
    {
        $this->blockRepository
            ->expects($this->once())
            ->method('setRepositoryObject')
            ->will($this->returnSelf())
        ;

        $this->blockRepository
            ->expects($this->once())
            ->method('save')
            ->will($this->returnValue(false))
        ;

        $block = $this->initBlock(1, 'Text', null, null, 1);
        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, "Text"));
        
        $block = $this->initBlock(null, 'Script', null, null, array(3, 3));
        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Script\AlBlockManagerScript', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, "Script"));

        $blockManager = $this->getMockBuilder('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Menu\AlBlockManagerMenu')
                            ->disableOriginalConstructor()
                            ->getMock();

        $factory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory
            ->expects($this->any())
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager))
        ;
        $this->assertFalse($this->slotManager->addBlock(2, 2, "Menu", 1));
        
        $this->assertEquals(2, $this->slotManager->length());
        $this->assertEquals('Text', $this->slotManager->first()->get()->getType());
        $this->assertEquals('Script', $this->slotManager->indexAt(1)->get()->getType());
        $this->assertEquals(1, $this->slotManager->first()->get()->getContentPosition());
        $this->assertEquals(3, $this->slotManager->last()->get()->getContentPosition());
    }
    
    public function testAddNewBlockInSecondPosition()
    {
        $this->setUpRepositoryBehavior(4);

        $this->blockRepository
            ->expects($this->once())
            ->method('setRepositoryObject')
            ->will($this->returnSelf())
        ;

        $this->blockRepository
            ->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true))
        ;

        $block = $this->initBlock(1, 'Text', null, null, 1);
        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, "Text"));
        
        $block = $this->initBlock(null, 'Script', null, null, array(3, 3));
        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Script\AlBlockManagerScript', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, "Script"));

        $block = $this->initBlock(null, 'Menu', null, null, 2);        
        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Menu\AlBlockManagerMenu', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, "Menu", 1));

        $this->assertEquals(3, $this->slotManager->length());
        $this->assertEquals('Text', $this->slotManager->first()->get()->getType());
        $this->assertEquals('Menu', $this->slotManager->indexAt(1)->get()->getType());
        $this->assertEquals('Script', $this->slotManager->last()->get()->getType());
        $this->assertEquals(1, $this->slotManager->first()->get()->getContentPosition());
        $this->assertEquals(2, $this->slotManager->indexAt(1)->get()->getContentPosition());
        $this->assertEquals(3, $this->slotManager->last()->get()->getContentPosition());
    }

    public function testAddNewBlockGivingAnInvalidBlockIdAddsTheBlockAsLast()
    {
        $this->setUpRepositoryBehavior(2);
        
        $block = $this->initBlock();
        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, "Text"));
        $block = $this->initBlock(null, 'Script');

        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Script\AlBlockManagerScript', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, "Script", 99999999));

        $this->assertEquals(2, $this->slotManager->length());
        $this->assertEquals('Script', $this->slotManager->last()->get()->getType());
    }

    /**
     * @dataProvider repeatedSlotProvider
     */
    public function testAddNewBlockOnARepeatedSlot($languageId, $pageId, $repeated)
    {
        $this->setUpRepositoryBehavior();
        $block = $this->initBlock(null, null, $languageId, $pageId);

        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $block);
        $slotManager = new AlSlotManager($this->eventsHandler, new AlSlot('test', array('repeated' => $repeated)), $this->blockRepository, $factory, $this->validator);
        $this->assertTrue($slotManager->addBlock(2, 2, "Text"));

        $this->assertEquals($languageId, $slotManager->first()->get()->getLanguageId());
        $this->assertEquals($pageId, $slotManager->first()->get()->getPageId());
    }
    
    public function testTryingToEditNonExistentBlock()
    {
        $this->setUpRepositoryBehavior();
        $block = $this->initBlock(1);

        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, "Text"));

        $this->assertNull($this->slotManager->editBlock(9999999999, array('Content', 'fake')));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testEditBlockThrownAnUnespectedException()
    {
        $this->setUpRepositoryBehavior(0, 1);
        $block = $this->initBlock(1);

        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $block);
        $slotManager = new AlSlotManager($this->eventsHandler, new AlSlot('test', array('repeated' => 'language')), $this->blockRepository, $factory, $this->validator);

        $this->blockManager
             ->expects($this->once())
             ->method('save')
             ->will($this->throwException(new \RuntimeException()))
        ;

        $slotManager->setUpBlockManagers(array($block));
        $slotManager->editBlock(1, array('Content' => 'fake'));
    }
    
    public function testEditBlockFailsWhenBlockManagerSavingFails()
    {
        $this->setUpRepositoryBehavior(0, 1);
        $block = $this->initBlock(1, null, null, null, null);

        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $block, 'save', false);
        $slotManager = new AlSlotManager($this->eventsHandler, new AlSlot('test', array('repeated' => 'language')), $this->blockRepository, $factory, $this->validator);
        $slotManager->setUpBlockManagers(array($block));
        $res = $slotManager->editBlock(1, array('Content' => 'fake'));
        $this->assertFalse($res);
    }

    public function testEditBlock()
    {
        $this->setUpRepositoryBehavior();
        $block = $this->initBlock(1, null, null, null, null, 'fake');

        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $block);
        $slotManager = new AlSlotManager($this->eventsHandler, new AlSlot('test', array('repeated' => 'language')), $this->blockRepository, $factory, $this->validator);
        $slotManager->setUpBlockManagers(array($block));
        $res = $slotManager->editBlock(1, array('Content' => 'fake'));
        $this->assertTrue($res);
        $this->assertEquals('fake', $slotManager->first()->get()->getContent());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testDeleteBlockThrownAnUnespectedException()
    {
        $this->setUpRepositoryBehavior(0, 1);
        $block = $this->initBlock(1);

        $blockManager = $this->getMockBuilder('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage')
                            ->disableOriginalConstructor()
                            ->getMock();

        $blockManager->expects($this->once())
            ->method('delete')
            ->will($this->throwException(new \RuntimeException()));

        $blockManager->expects($this->any())
                        ->method('get')
                        ->will($this->returnValue($block));
        $factory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory->expects($this->any())
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager));
         
        $slotManager = new AlSlotManager($this->eventsHandler, new AlSlot('test', array('repeated' => 'language')), $this->blockRepository, $factory, $this->validator);
        $slotManager->setUpBlockManagers(array($block));
        $slotManager->deleteBlock(1);
    }
    
    public function testDeleteBlockFailsWhenBlockManagerFaildToDeleteABlock()
    {
        $this->setUpRepositoryBehavior(0, 1);
        $block = $this->initBlock(1);

        $blockManager = $this->getMockBuilder('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage')
                            ->disableOriginalConstructor()
                            ->getMock();

        $blockManager->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(false));

        $blockManager->expects($this->any())
                        ->method('get')
                        ->will($this->returnValue($block));
        $factory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory->expects($this->any())
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager));
         
        $slotManager = new AlSlotManager($this->eventsHandler, new AlSlot('test', array('repeated' => 'language')), $this->blockRepository, $factory, $this->validator);
        $slotManager->setUpBlockManagers(array($block));
        $slotManager->deleteBlock(1);
    }
    
    public function testDeleteIsSkippedWhenAnyBlockManagerExists()
    {
        $this
            ->blockRepository
            ->expects($this->never())
            ->method('startTransaction')
        ;
        
        $factory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $slotManager = new AlSlotManager($this->eventsHandler, new AlSlot('test', array('repeated' => 'language')), $this->blockRepository, $factory, $this->validator);
        $this->assertNull($slotManager->deleteBlock(1));
    }

    public function testDeleteBlock()
    {
        $this->setUpRepositoryBehavior(2);

        $this->blockRepository->expects($this->once())
            ->method('setRepositoryObject')
            ->will($this->returnSelf());

        $this->blockRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));        
        
        $block1 = $this->initBlock(1, 'Text', null, null, 1);
        $blockManager1 = $this->getMockBuilder('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage')
                            ->disableOriginalConstructor()
                            ->getMock();

        $blockManager1->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(true));

        $blockManager1->expects($this->any())
            ->method('get')
            ->will($this->returnValue($block1));
        
        $block2 = $this->initBlock(null, 'Script', null, null, array(1, 2));
        $blockManager2 = $this->getMockBuilder('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Script\AlBlockManagerScript')
                            ->disableOriginalConstructor()
                            ->getMock();

        $blockManager2->expects($this->any())
            ->method('get')
            ->will($this->returnValue($block2));

        $factory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory->expects($this->any())
            ->method('createBlockManager')
            ->will($this->onConsecutiveCalls($blockManager1, $blockManager2));

        $slotManager = new AlSlotManager($this->eventsHandler, new AlSlot('test', array('repeated' => 'language')), $this->blockRepository, $factory, $this->validator);
        $slotManager->setUpBlockManagers(array($block1, $block2));

        $this->assertEquals(2, $slotManager->length());
        $this->assertEquals("Text", $slotManager->first()->get()->getType());
        $this->assertEquals(1, $slotManager->first()->get()->getContentPosition());
        $this->assertTrue($slotManager->deleteBlock(1));
        $this->assertEquals(1, $slotManager->length());
        $this->assertEquals("Script", $slotManager->first()->get()->getType());
        $this->assertEquals(1, $slotManager->first()->get()->getContentPosition());
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testDeleteBlocksFailsWhenABlockDeletedOperationThrowsAnAnUnespectedException()
    {
        $this->setUpRepositoryBehavior(0, 1);
        $block1 = $this->initBlock();

        $blockManager1 = $this->getMockBuilder('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage')
                            ->disableOriginalConstructor()
                            ->getMock();

        $blockManager1->expects($this->once())
                ->method('delete')
                ->will($this->throwException(new \RuntimeException()));

        $blockManager1->expects($this->any())
            ->method('get')
            ->will($this->returnValue($block1));

        $factory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory->expects($this->once())
            ->method('createBlockManager')
            ->will($this->onConsecutiveCalls($blockManager1));

        $slotManager = new AlSlotManager($this->eventsHandler, new AlSlot('test', array('repeated' => 'language')), $this->blockRepository,  $factory, $this->validator);
        $slotManager->setUpBlockManagers(array($block1));

        $slotManager->deleteBlocks();
    }
    
    public function testDeleteBlocksFailsWhenOneBlockIsNotDeletedDueToAnUnespectedError()
    {
        $this->setUpRepositoryBehavior(0, 1);
        $block1 = $this->initBlock();

        $blockManager1 = $this->getMockBuilder('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage')
                            ->disableOriginalConstructor()
                            ->getMock();

        $blockManager1->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(false));

        $blockManager1->expects($this->any())
            ->method('get')
            ->will($this->returnValue($block1));

        $factory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory->expects($this->once())
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager1));

        $slotManager = new AlSlotManager($this->eventsHandler, new AlSlot('test', array('repeated' => 'language')), $this->blockRepository,  $factory, $this->validator);
        $slotManager->setUpBlockManagers(array($block1));

        $slotManager->deleteBlocks();
    }

    public function testDeleteBlocks()
    {
        $this->setUpRepositoryBehavior(1);
        $block1 = $this->initBlock();

        $blockManager1 = $this->getMockBuilder('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage')
                            ->disableOriginalConstructor()
                            ->getMock();

        $blockManager1->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(true));

        $blockManager1->expects($this->any())
            ->method('get')
            ->will($this->returnValue($block1));

        // Block Manager 2
        $block2 = $this->initBlock();
        $blockManager2 = $this->getMockBuilder('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Script\AlBlockManagerScript')
                            ->disableOriginalConstructor()
                            ->getMock();

        $blockManager2->expects($this->any())
            ->method('get')
            ->will($this->returnValue($block2));

        $blockManager2->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(true));

        $factory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory->expects($this->any())
            ->method('createBlockManager')
            ->will($this->onConsecutiveCalls($blockManager1, $blockManager2));

        $slotManager = new AlSlotManager($this->eventsHandler, new AlSlot('test', array('repeated' => 'language')), $this->blockRepository,  $factory, $this->validator);
        $slotManager->setUpBlockManagers(array($block1, $block2));

        $slotManager->deleteBlocks();
        $this->assertEquals(0, $slotManager->length());
    }
    
    public function testDeleteBlocksIsSkippedWhenAnyBlockManagerExists()
    {
        $this
            ->blockRepository
            ->expects($this->never())
            ->method('startTransaction')
        ;
        
        $factory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $slotManager = new AlSlotManager($this->eventsHandler, new AlSlot('test', array('repeated' => 'language')), $this->blockRepository, $factory, $this->validator);
        $this->assertNull($slotManager->deleteBlocks());
    }

    public function testFirst()
    {
        $this->setUpRepositoryBehavior(1);

        $this->assertNull($this->slotManager->first());

        $this->addMoreBlocks('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage');

        $this->assertEquals('Text', $this->slotManager->first()->get()->getType());
    }

    public function testLast()
    {
        $this->setUpRepositoryBehavior(2);

        $this->assertNull($this->slotManager->last());

        $this->addMoreBlocks('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage');
        $this->assertEquals('Text', $this->slotManager->last()->get()->getType());

        $this->addMoreBlocks('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Script\AlBlockManagerScript', 'Script');
        $this->assertEquals('Script', $this->slotManager->last()->get()->getType());
    }

    public function testLastAdded()
    {
        $this->setUpRepositoryBehavior(4);

        $this->blockRepository->expects($this->once())
            ->method('setRepositoryObject')
            ->will($this->returnSelf());

        $this->blockRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));

        $this->assertNull($this->slotManager->lastAdded());
        $block = $this->initBlock(1, 'Text');

        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, 'Text'));
        $this->assertEquals('Text', $this->slotManager->lastAdded()->get()->getType());

        $this->addMoreBlocks('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Script\AlBlockManagerScript', 'Script');
        $this->assertEquals('Script', $this->slotManager->lastAdded()->get()->getType());

        $block = $this->initBlock(null, 'Menu');

        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Menu\AlBlockManagerMenu', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, 'Menu', 1));
        $this->assertEquals('Menu', $this->slotManager->lastAdded()->get()->getType());
        $this->assertEquals('Script', $this->slotManager->last()->get()->getType());
    }

    public function testIndexAt()
    {
        $this->setUpRepositoryBehavior(3);

        $this->assertNull($this->slotManager->indexAt(0));
        $this->addMoreBlocks('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage');
        $this->addMoreBlocks('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Script\AlBlockManagerScript', 'Script');
        $this->addMoreBlocks('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Menu\AlBlockManagerMenu', 'Menu');
        $this->assertNull($this->slotManager->indexAt(-1));
        $this->assertNull($this->slotManager->indexAt(3));
        $this->assertEquals('Text', $this->slotManager->indexAt(0)->get()->getType());
        $this->assertEquals('Script', $this->slotManager->indexAt(1)->get()->getType());
        $this->assertEquals('Menu', $this->slotManager->indexAt(2)->get()->getType());
    }

    public function testLength()
    {
        $this->setUpRepositoryBehavior(2);

        $this->assertEquals(0, $this->slotManager->length());
        $this->addMoreBlocks('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage');
        $this->assertEquals(1, $this->slotManager->length());
        $this->addMoreBlocks('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Script\AlBlockManagerScript', 'Script');
        $this->assertEquals(2, $this->slotManager->length());
    }

    public function testGetBlockManager()
    {
        $this->setUpRepositoryBehavior();

        $this->assertNull($this->slotManager->getBlockManager(99999999));
        $this->assertNull($this->slotManager->lastAdded());
        
        $block = $this->initBlock();
        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, 'Text'));

        $this->assertEquals($this->slotManager->first(), $this->slotManager->getBlockManager($this->slotManager->first()->get()->getId()));
    }

    public function testToArray()
    {
        $block = $this->initBlock();
        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $block);

        $this->slotManager->setBlockManagerFactory($factory);

        $this->blockManager->expects($this->once())
                ->method('set')
                ->with(null);
        $this->assertTrue($this->slotManager->addBlock(2, 2));

        $block = $this->initBlock();
        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $block);

        $this->slotManager->setBlockManagerFactory($factory);

        $this->blockManager->expects($this->once())
                ->method('set')
                ->with(null);
        $this->assertTrue($this->slotManager->addBlock(2, 2, 'Script'));

        $blockManagers = $this->slotManager->toArray();
        $this->assertEquals(2, count($blockManagers));
    }

    public function testForceAttributes()
    {
        $block = $this->initBlock();
        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $block);

        $this->blockManager->expects($this->once())
                ->method('set')
                ->with(null);

        $slot = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlSlot')
                    ->disableOriginalConstructor()
                    ->getMock();

        $slot->expects($this->once())
                ->method('getContent');

        $slotManager = new AlSlotManager($this->eventsHandler, $slot, $this->blockRepository,  $factory, $this->validator);
        $slotManager->setBlockManagerFactory($factory);
        $slotManager->setForceSlotAttributes(true);
        $slotManager->addBlock(2, 2);
    }
    
    public function testSetSkippedSiteLevelFlag()
    {
        $this->setUpRepositoryBehavior();
        $block = $this->initBlock();

        $factory = $this->setUpFactory('RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\AlBlockManagerImage', $block);

        $this->blockManager
            ->expects($this->once())
            ->method('set')
            ->with(null)
        ;
        
        $this->blockRepository
            ->expects($this->at(0))
            ->method('retrieveContents')
            ->with(1, 1, 'test')
            ->will($this->returnValue(array()))
        ;
        
        $this->blockRepository
            ->expects($this->at(3))
            ->method('retrieveContents')
            ->with(1, 1, 'test')
            ->will($this->returnValue(array($block)))
        ;

        $slotManager = new AlSlotManager($this->eventsHandler, new AlSlot('test', array('repeated' => 'site')), $this->blockRepository, $factory, $this->validator);
        $slotManager->setSkipSiteLevelBlocks(true);
        $this->assertTrue($slotManager->addBlock(2, 2, 'Text'));
        $this->assertNull($slotManager->addBlock(2, 2, 'Text'));
        $this->assertEquals(1 ,$slotManager->length());
    }
    
    public function repeatedSlotProvider()
    {
        return array(
            array(2, 1, 'language'),
            array(1, 1, 'site'),
        );
    }
    
    private function initBlock($id = null, $type = null, $languageId = null, $pageId = null, $position = null, $content = null)
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');        
        $this->initBlockExpectation($block, $id, 'getId');
        $this->initBlockExpectation($block, $type, 'getType');
        $this->initBlockExpectation($block, $languageId, 'getLanguageId');
        $this->initBlockExpectation($block, $pageId, 'getPageId');
        $this->initBlockExpectation($block, $position, 'getContentPosition');        
        $this->initBlockExpectation($block, $content, 'getContent');
        
        return $block;
    }
    
    private function initBlockExpectation($block, $property, $method)
    {
        if (null !== $property) {
            if (is_array($property)) {
                $value = $property[0];                
                $times = $property[1];
            }
            else {
                $value = $property;
                $times = 1;
            }
            $block
                ->expects($this->exactly($times))
                ->method($method)
                ->will($this->returnValue($value))
            ;
        }
    }
    
    private function setUpRepositoryBehavior($successTimes = 1, $failTimes = 0)
    {
        $transactionTimes = ($successTimes > 0) ? $successTimes : $failTimes;
        $this
            ->blockRepository
            ->expects($this->exactly($transactionTimes))
            ->method('startTransaction')
        ;

        $this
            ->blockRepository
            ->expects($this->exactly($successTimes))
            ->method('commit')
        ;

        $this
            ->blockRepository
            ->expects($this->exactly($failTimes))
            ->method('rollback')
        ;
    }

    private function setUpBlockManager($class, $block = null, $method = "save", $result = true)
    {
        $blockManager = $this->getMockBuilder($class)
                            ->disableOriginalConstructor()
                            ->getMock();

        $blockManager
            ->expects($this->any())
            ->method('set')
            ->with(null)
        ;
        
        $blockManager->expects($this->once())
                ->method($method)
                ->will($this->returnValue($result));

        if (null !== $block) {
            $blockManager->expects($this->any())
                        ->method('get')
                        ->will($this->returnValue($block));
        }

        $this->blockManager = $blockManager;

        return $blockManager;
    }

    private function setUpFactory($class, $block = null, $method = "save", $result = true)
    {
        $blockManager = $this->setUpBlockManager($class, $block, $method, $result);

        $factory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory->expects($this->any())
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager));

        return $factory;
    }

    private function addMoreBlocks($class, $type = "Text")
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');

        $block->expects($this->any())
                ->method('getType')
                ->will($this->returnValue($type));

        $factory = $this->setUpFactory($class, $block);
        $this->slotManager->setBlockManagerFactory($factory);
        
        $this->assertTrue($this->slotManager->addBlock(2, 2, $type));
    }
}
