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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Slot;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;

/**
 * AlSlotsConverterFactoryTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlSlotManagerTest extends TestCase 
{    
    private $dispatcher;
       
    /*
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        
        AlphaLemonDataPopulator::depopulate();
    }*/
    
    protected function setUp() 
    {
        parent::setUp();
        
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->validator = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $factory = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        
        $this->blockModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlBlockModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $slot = new AlSlot('test', array('repeated' => 'page'));
        $this->slotManager = new AlSlotManager($this->dispatcher, $slot, $this->blockModel, $this->validator, $factory);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddBlockFailsWhenReceivesAnInvalidType()
    {
        $this->dispatcher->expects($this->never())
            ->method('dispatch');
        
        $factory = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory->expects($this->any())
            ->method('createBlock')
            ->will($this->throwException(new \InvalidArgumentException));
        
        $this->slotManager->addBlock(2, 2, 'fake');
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testAddNewBlockThrownAnUnespectedException()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getLanguageId')
                ->will($this->returnValue(2));
        
        $block->expects($this->any())
                ->method('getPageId')
                ->will($this->returnValue(2));
        
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue('Text'));
        
        $this->blockModel->expects($this->once())
            ->method('startTransaction');
        
        $this->blockModel->expects($this->once())
            ->method('rollback');
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $block);        
        $this->slotManager->setBlockManagerFactory($factory);
        
        $this->blockManager->expects($this->once())
                ->method('set')
                ->with(null);
        
        $this->blockManager->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException()));
        
        $this->slotManager->addBlock(2, 2);
    }
    
    public function testAddNewBlockWithoutGivingTheClassType()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getLanguageId')
                ->will($this->returnValue(2));
        
        $block->expects($this->any())
                ->method('getPageId')
                ->will($this->returnValue(2));
        
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue('Text'));
        
        $this->blockModel->expects($this->once())
            ->method('startTransaction');
        
        $this->blockModel->expects($this->once())
            ->method('commit');
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $block);        
        $this->slotManager->setBlockManagerFactory($factory);
        
        $this->blockManager->expects($this->once())
                ->method('set')
                ->with(null);
        
        $this->assertTrue($this->slotManager->addBlock(2, 2));
        $this->assertEquals(1, $this->slotManager->length());
        $this->assertEquals(2, $this->slotManager->first()->get()->getLanguageId());
        $this->assertEquals(2, $this->slotManager->first()->get()->getPageId());
        
        $blockManager = $this->slotManager->last();
        $this->assertEquals("Text", $blockManager->get()->getClassName());
    }
    
    public function testAddNewBlockGivingTheClassType()
    {
        $this->blockModel->expects($this->once())
            ->method('startTransaction');
        
        $this->blockModel->expects($this->once())
            ->method('commit');
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\ScriptBundle\Core\Block\AlBlockManagerScript', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        
        $this->blockManager->expects($this->once())
                ->method('set')
                ->with(null);
        
        $this->assertTrue($this->slotManager->addBlock(2, 2, "Script"));
        $this->assertEquals(1 ,$this->slotManager->length());
    }
    
    public function testAddNewBlockInSecondPosition()
    {
        $this->blockModel->expects($this->exactly(4))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(4))
            ->method('commit');
        
        $this->blockModel->expects($this->once())
            ->method('setModelObject')
            ->will($this->returnSelf());
        
        $this->blockModel->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue('Text'));  
        
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));  
        
        $block->expects($this->once())
                ->method('getContentPosition')
                ->will($this->returnValue(1)); 
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        
        $this->blockManager->expects($this->once())
                ->method('set')
                ->with(null);
        
        $this->assertTrue($this->slotManager->addBlock(2, 2, "Text"));
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue('Script'));
        
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));
        
        $block->expects($this->exactly(3))
                ->method('getContentPosition')
                ->will($this->returnValue(3)); 
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\ScriptBundle\Core\Block\AlBlockManagerScript', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        
        $this->blockManager->expects($this->once())
                ->method('set')
                ->with(null);
        
        $this->assertTrue($this->slotManager->addBlock(2, 2, "Script"));
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue('Media'));
        
        $block->expects($this->once())
                ->method('getContentPosition')
                ->will($this->returnValue(2));
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\MediaBundle\Core\Block\AlBlockManagerMedia', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        
        $this->blockManager->expects($this->once())
                ->method('set')
                ->with(null);
        
        $this->assertTrue($this->slotManager->addBlock(2, 2, "Media", 1));
        
        $this->assertEquals(3, $this->slotManager->length());
        $this->assertEquals('Text', $this->slotManager->first()->get()->getClassName());
        $this->assertEquals('Media', $this->slotManager->indexAt(1)->get()->getClassName());
        $this->assertEquals('Script', $this->slotManager->last()->get()->getClassName());
        $this->assertEquals(1, $this->slotManager->first()->get()->getContentPosition());
        $this->assertEquals(2, $this->slotManager->indexAt(1)->get()->getContentPosition());
        $this->assertEquals(3, $this->slotManager->last()->get()->getContentPosition());
    }
        
    public function testAddNewBlockGivingAnInvalidBlockIdAddsTheBlockAsLast()
    {
        $this->blockModel->expects($this->exactly(2))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(2))
            ->method('commit');
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue('Text'));  
        
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1)); 
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        
        $this->blockManager->expects($this->once())
                ->method('set')
                ->with(null);
        
        $this->assertTrue($this->slotManager->addBlock(2, 2, "Text"));
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue('Script'));  
        
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));  
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\ScriptBundle\Core\Block\AlBlockManagerScript', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, "Script", 99999999));
        
        $this->assertEquals(2, $this->slotManager->length());
        $this->assertEquals('Script', $this->slotManager->last()->get()->getClassName());
    }
    
    public function testAddNewBlockOnSlotRepeatedAtLanguage()
    {
        $this->blockModel->expects($this->once())
            ->method('startTransaction');
        
        $this->blockModel->expects($this->once())
            ->method('commit');
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue('Text'));  
        
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));  
        
        $block->expects($this->once())
                ->method('getLanguageId')
                ->will($this->returnValue(2));  
        
        $block->expects($this->once())
                ->method('getPageId')
                ->will($this->returnValue(1));  
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $block);
        
        $this->blockManager->expects($this->once())
                ->method('set')
                ->with(null);
        
        $slotManager = new AlSlotManager($this->dispatcher, new AlSlot('test', array('repeated' => 'language')), $this->blockModel, $this->validator, $factory);
        $this->assertTrue($slotManager->addBlock(2, 2, "Text"));
        
        $this->assertEquals(2, $slotManager->first()->get()->getLanguageId());
        $this->assertEquals(1, $slotManager->first()->get()->getPageId());
    }
    
    public function testAddNewBlockOnSlotRepeatedAtSite()
    {
        $this->blockModel->expects($this->once())
            ->method('startTransaction');
        
        $this->blockModel->expects($this->once())
            ->method('commit');
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue('Text'));  
        
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));  
        
        $block->expects($this->once())
                ->method('getLanguageId')
                ->will($this->returnValue(1));  
        
        $block->expects($this->once())
                ->method('getPageId')
                ->will($this->returnValue(1));
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $block);
        $slotManager = new AlSlotManager($this->dispatcher, new AlSlot('test', array('repeated' => 'language')), $this->blockModel, $this->validator, $factory);
        
        $this->blockManager->expects($this->once())
                ->method('set')
                ->with(null);
        
        $this->assertTrue($slotManager->addBlock(2, 2, "Text"));
        
        $this->assertEquals(1, $slotManager->first()->get()->getLanguageId());
        $this->assertEquals(1, $slotManager->first()->get()->getPageId());
    }
    
    public function testTryingToEditNonExistentBlock()
    {
        $this->blockModel->expects($this->once())
            ->method('startTransaction');
        
        $this->blockModel->expects($this->once())
            ->method('commit');
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));  
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, "Text"));
        
        $this->assertNull($this->slotManager->editBlock(9999999999, array('HtmlContent', 'fake')));      
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testEditBlockThrownAnUnespectedException()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1)); 
        
        $this->blockModel->expects($this->once())
            ->method('startTransaction');
        
        $this->blockModel->expects($this->once())
            ->method('rollback');
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $block);        
        $slotManager = new AlSlotManager($this->dispatcher, new AlSlot('test', array('repeated' => 'language')), $this->blockModel, $this->validator, $factory);
        
        $this->blockManager->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException()));
        
        $slotManager->setUpBlockManagers(array($block));
        $slotManager->editBlock(1, array('HtmlContent' => 'fake'));
    }
    
    public function testEditBlock()
    {
        $this->blockModel->expects($this->once())
            ->method('startTransaction');
        
        $this->blockModel->expects($this->once())
            ->method('commit');
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1)); 
        
        $block->expects($this->once())
                ->method('getHtmlContent')
                ->will($this->returnValue('fake'));  
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $block);
        $slotManager = new AlSlotManager($this->dispatcher, new AlSlot('test', array('repeated' => 'language')), $this->blockModel, $this->validator, $factory);
        $slotManager->setUpBlockManagers(array($block));
        $res = $slotManager->editBlock(1, array('HtmlContent' => 'fake'));
        $this->assertTrue($res);  
        $this->assertEquals('fake', $slotManager->first()->get()->getHtmlContent());
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testDeleteBlockThrownAnUnespectedException()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1)); 
        
        $this->blockModel->expects($this->once())
            ->method('startTransaction');
        
        $this->blockModel->expects($this->once())
            ->method('rollback');
        
        $blockManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText')
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $blockManager->expects($this->once())
            ->method('delete')
            ->will($this->throwException(new \RuntimeException()));
        
        $blockManager->expects($this->any())
                        ->method('get')
                        ->will($this->returnValue($block));
        
        $factory = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory->expects($this->any())
            ->method('createBlock')
            ->will($this->returnValue($blockManager));
        
        //$factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $block);        
        $slotManager = new AlSlotManager($this->dispatcher, new AlSlot('test', array('repeated' => 'language')), $this->blockModel, $this->validator, $factory);
        $slotManager->setUpBlockManagers(array($block));
        $slotManager->deleteBlock(1);
    }
    
    public function testDeleteBlock()
    {
        $this->blockModel->expects($this->exactly(2))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(2))
            ->method('commit');
        
        $this->blockModel->expects($this->once())
            ->method('setModelObject')
            ->will($this->returnSelf());
        
        $this->blockModel->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));
        
        $block1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');        
        $block1->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));
        
        $block1->expects($this->any())
                ->method('getContentPosition')
                ->will($this->returnValue(1));
        
        $block1->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue("Text"));
        
        $blockManager1 = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText')
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $blockManager1->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(true));
        
        $blockManager1->expects($this->any())
            ->method('get')
            ->will($this->returnValue($block1));
        
        // Block Manager 2
        $block2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');        
        $block2->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));
        
        $block2->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue("Script"));
        
        $block2->expects($this->any())
                ->method('getContentPosition')
                ->will($this->returnValue(1));
        
        $blockManager2 = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\ScriptBundle\Core\Block\AlBlockManagerScript')
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $blockManager2->expects($this->any())
            ->method('get')
            ->will($this->returnValue($block2));
        
        $factory = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory->expects($this->any())
            ->method('createBlock')
            ->will($this->onConsecutiveCalls($blockManager1, $blockManager2));
              
        $slotManager = new AlSlotManager($this->dispatcher, new AlSlot('test', array('repeated' => 'language')), $this->blockModel, $this->validator, $factory);
        $slotManager->setUpBlockManagers(array($block1, $block2));
        
        $this->assertEquals(2, $slotManager->length());
        $this->assertEquals("Text", $slotManager->first()->get()->getClassName());
        $this->assertEquals(1, $slotManager->first()->get()->getContentPosition());
        $this->assertTrue($slotManager->deleteBlock(1));
        $this->assertEquals(1, $slotManager->length());
        $this->assertEquals("Script", $slotManager->first()->get()->getClassName());
        $this->assertEquals(1, $slotManager->first()->get()->getContentPosition());
    }
    
    public function testDeleteBlocks()
    {
        $this->blockModel->expects($this->once())
            ->method('startTransaction');
        
        $this->blockModel->expects($this->once())
            ->method('commit');
        
        $block1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');        
        $block1->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));
        
        $block1->expects($this->any())
                ->method('getContentPosition')
                ->will($this->returnValue(1));
        
        $block1->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue("Text"));
        
        $blockManager1 = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText')
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $blockManager1->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(true));
        
        $blockManager1->expects($this->any())
            ->method('get')
            ->will($this->returnValue($block1));
        
        // Block Manager 2
        $block2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');        
        $block2->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));
        
        $block2->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue("Script"));
        
        $block2->expects($this->any())
                ->method('getContentPosition')
                ->will($this->returnValue(1));
        
        $blockManager2 = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\ScriptBundle\Core\Block\AlBlockManagerScript')
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $blockManager2->expects($this->any())
            ->method('get')
            ->will($this->returnValue($block2));
        
        $blockManager2->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(true));
        
        $factory = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory->expects($this->any())
            ->method('createBlock')
            ->will($this->onConsecutiveCalls($blockManager1, $blockManager2));
             
        $slotManager = new AlSlotManager($this->dispatcher, new AlSlot('test', array('repeated' => 'language')), $this->blockModel, $this->validator, $factory);
        $slotManager->setUpBlockManagers(array($block1, $block2));
        
        $slotManager->deleteBlocks(); 
        $this->assertEquals(0, $slotManager->length());
    }
    
    public function testFirst()
    {
        $this->blockModel->expects($this->once())
            ->method('startTransaction');
        
        $this->blockModel->expects($this->once())
            ->method('commit');
        
        $this->assertNull($this->slotManager->first());
        
        $this->addBlockManagerOnlyWithClassName('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText');
        
        $this->assertEquals('Text', $this->slotManager->first()->get()->getClassName());
    }
    
    public function testLast()
    {
        $this->blockModel->expects($this->exactly(2))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(2))
            ->method('commit');
        
        $this->assertNull($this->slotManager->last());
        
        $this->addBlockManagerOnlyWithClassName('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText');        
        $this->assertEquals('Text', $this->slotManager->last()->get()->getClassName());
        
        $this->addBlockManagerOnlyWithClassName('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\ScriptBundle\Core\Block\AlBlockManagerScript', 'Script');        
        $this->assertEquals('Script', $this->slotManager->last()->get()->getClassName());
    }
    
    public function testLastAdded()
    {
        $this->blockModel->expects($this->exactly(4))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(4))
            ->method('commit');
        
        $this->blockModel->expects($this->once())
            ->method('setModelObject')
            ->will($this->returnSelf());
        
        $this->blockModel->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));
        
        $this->assertNull($this->slotManager->lastAdded());
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue('Text'));  
        
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));  
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, 'Text'));
        $this->assertEquals('Text', $this->slotManager->lastAdded()->get()->getClassName());
        
        $this->addBlockManagerOnlyWithClassName('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\ScriptBundle\Core\Block\AlBlockManagerScript', 'Script');   
        $this->assertEquals('Script', $this->slotManager->lastAdded()->get()->getClassName());
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue('Media'));  
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\MediaBundle\Core\Block\AlBlockManagerMedia', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, 'Media', 1));
        $this->assertEquals('Media', $this->slotManager->lastAdded()->get()->getClassName());
        $this->assertEquals('Script', $this->slotManager->last()->get()->getClassName());
    }
    
    public function testIndexAt()
    {
        $this->blockModel->expects($this->exactly(3))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(3))
            ->method('commit');
        
        $this->assertNull($this->slotManager->indexAt(0));
        $this->addBlockManagerOnlyWithClassName('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText');  
        $this->addBlockManagerOnlyWithClassName('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\ScriptBundle\Core\Block\AlBlockManagerScript', 'Script');  
        $this->addBlockManagerOnlyWithClassName('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\MediaBundle\Core\Block\AlBlockManagerMedia', 'Media');  
        $this->assertNull($this->slotManager->indexAt(-1));
        $this->assertNull($this->slotManager->indexAt(3));
        $this->assertEquals('Text', $this->slotManager->indexAt(0)->get()->getClassName());
        $this->assertEquals('Script', $this->slotManager->indexAt(1)->get()->getClassName());
        $this->assertEquals('Media', $this->slotManager->indexAt(2)->get()->getClassName());
    }
    
    public function testLength()
    {
        $this->blockModel->expects($this->exactly(2))
            ->method('startTransaction');
        
        $this->blockModel->expects($this->exactly(2))
            ->method('commit');
        
        $this->assertEquals(0, $this->slotManager->length());
        $this->addBlockManagerOnlyWithClassName('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText');
        $this->assertEquals(1, $this->slotManager->length());
        $this->addBlockManagerOnlyWithClassName('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\ScriptBundle\Core\Block\AlBlockManagerScript', 'Script'); 
        $this->assertEquals(2, $this->slotManager->length());
    }
    
    public function testGetBlockManager()
    {   
        $this->blockModel->expects($this->once())
            ->method('startTransaction');
        
        $this->blockModel->expects($this->once())
            ->method('commit');
        
        $this->assertNull($this->slotManager->getBlockManager(99999999));
        
        $this->assertNull($this->slotManager->lastAdded());
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue('Text'));  
        
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));  
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock(2, 2, 'Text'));
        
        $this->assertEquals($this->slotManager->first(), $this->slotManager->getBlockManager($this->slotManager->first()->get()->getId()));
    }
    
    public function testAddBlock()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
    
    public function testRollbackPreviousTests()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
    
    public function testToArray()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $this->setUpFactory('Text', $block);
        
        $this->assertTrue($this->slotManager->addBlock(2, 2));
        
        $blockManagers = $this->slotManager->toArray();
        $this->assertEquals(count($blockManagers),count($this->slotManager->getBlockManagers()));
        
        $array = $blockManagers[0];
        $this->assertTrue(array_key_exists('HideInEditMode', $array));
        $this->assertTrue(array_key_exists('HtmlContent', $array));
        $this->assertTrue(array_key_exists('ExternalJavascript', $array));
        $this->assertTrue(array_key_exists('InternalJavascript', $array));
        $this->assertTrue(array_key_exists('ExternalStylesheet', $array));
        $this->assertTrue(array_key_exists('InternalStylesheet', $array));
        $this->assertTrue(array_key_exists('Block', $array));
    }
    
    public function testForceAttributes()
    {
        $this->markTestIncomplete(
        'This test has not been implemented yet.'
        );
    }
    
    private function setUpBlockManager($class, $block = null, $method = "save")
    {
        $blockManager = $this->getMockBuilder($class)
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $blockManager->expects($this->once())
                ->method($method)
                ->will($this->returnValue(true));
        
        if (null !== $block) {
            $blockManager->expects($this->any())
                        ->method('get')
                        ->will($this->returnValue($block));
        }
        
        $this->blockManager = $blockManager;
        
        return $blockManager;
    }
    
    private function setUpFactory($class, $block = null)
    {
        $blockManager = $this->setUpBlockManager($class, $block);
        
        $factory = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory->expects($this->any())
            ->method('createBlock')
            ->will($this->returnValue($blockManager));
        
        return $factory;
    }
    
    private function addBlockManagerOnlyWithClassName($class, $type = "Text")
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue($type));  
        
        $factory = $this->setUpFactory($class, $block);
        $this->slotManager->setBlockManagerFactory($factory);
        
        $this->assertTrue($this->slotManager->addBlock(2, 2, $type));
    }
}