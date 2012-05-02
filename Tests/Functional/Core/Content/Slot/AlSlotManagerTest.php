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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Content\Slot;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;

class AlSlotManagerTest extends TestCase 
{    
    private $dispatcher;
    private $translator;
    private $alLanguage;
    private $alPage;
       
    /*
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        
        AlphaLemonDataPopulator::depopulate();
    }*/
    
    protected function setUp() 
    {
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $factory = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        
        $this->alPage = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');        
        $this->alPage->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(2));
        
        $this->alLanguage = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');        
        $this->alLanguage->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(2));
        
        $this->slotManager = new AlSlotManager($this->dispatcher, $this->translator, new AlSlot('test', array('repeated' => 'page')), $this->alPage, $this->alLanguage, $factory);
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
        
        $this->slotManager->addBlock('fake');
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
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $block);        
        $this->slotManager->setBlockManagerFactory($factory);
        
        $this->assertTrue($this->slotManager->addBlock());
        $this->assertEquals(1, $this->slotManager->length());
        $this->assertEquals(2, $this->slotManager->first()->get()->getLanguageId());
        $this->assertEquals(2, $this->slotManager->first()->get()->getPageId());
        
        $blockManager = $this->slotManager->last();
        $this->assertEquals("Text", $blockManager->get()->getClassName());
    }
    
    public function testAddNewBlockGivingTheClassType()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\ScriptBundle\Core\Block\AlBlockManagerScript', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        
        $this->assertTrue($this->slotManager->addBlock("Script"));
        $this->assertEquals(1 ,$this->slotManager->length());
    }
    
    public function testAddNewBlockInSecondPosition()
    {
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
        $this->assertTrue($this->slotManager->addBlock("Text"));
        
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
        $this->assertTrue($this->slotManager->addBlock("Script"));
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue('Media'));
        
        $block->expects($this->once())
                ->method('getContentPosition')
                ->will($this->returnValue(2));
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\MediaBundle\Core\Block\AlBlockManagerMedia', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock("Media", 1));
        
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
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue('Text'));  
        
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));  
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock("Text"));
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue('Script'));  
        
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));  
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\ScriptBundle\Core\Block\AlBlockManagerScript', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock("Script", 99999999));
        
        $this->assertEquals(2, $this->slotManager->length());
        $this->assertEquals('Script', $this->slotManager->last()->get()->getClassName());
    }
    
    public function testAddNewBlockOnSlotRepeatedAtLanguage()
    {
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
        
        $slotManager = new AlSlotManager($this->dispatcher, $this->translator, new AlSlot('test', array('repeated' => 'language')), $this->alPage, $this->alLanguage, $factory);
        $this->assertTrue($slotManager->addBlock("Text"));
        
        $this->assertEquals(2, $slotManager->first()->get()->getLanguageId());
        $this->assertEquals(1, $slotManager->first()->get()->getPageId());
    }
    
    public function testAddNewBlockOnSlotRepeatedAtSite()
    {
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
        $slotManager = new AlSlotManager($this->dispatcher, $this->translator, new AlSlot('test', array('repeated' => 'language')), $this->alPage, $this->alLanguage, $factory);
        $this->assertTrue($slotManager->addBlock("Text"));
        
        $this->assertEquals(1, $slotManager->first()->get()->getLanguageId());
        $this->assertEquals(1, $slotManager->first()->get()->getPageId());
    }
    
    public function testTryingToEditNonExistentBlock()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));  
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock("Text"));
        
        $this->assertNull($this->slotManager->editBlock(9999999999, array('HtmlContent', 'fake')));      
    }
    
    public function testEditBlock()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1)); 
        
        $block->expects($this->once())
                ->method('getHtmlContent')
                ->will($this->returnValue('fake'));  
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $block);
        $slotManager = new AlSlotManager($this->dispatcher, $this->translator, new AlSlot('test', array('repeated' => 'language')), $this->alPage, $this->alLanguage, $factory, array($block));
        
        $res = $slotManager->editBlock(1, array('HtmlContent' => 'fake'));
        $this->assertTrue($res);  
        $this->assertEquals('fake', $slotManager->first()->get()->getHtmlContent());
    }
    
    public function testDeleteBlock()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');        
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));
        
        $block->expects($this->any())
                ->method('getContentPosition')
                ->will($this->returnValue(1));
        
        $block->expects($this->any())
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
            ->will($this->returnValue($block));
        
        // Block Manager 2
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');        
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));
        
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue("Script"));
        
        $block->expects($this->any())
                ->method('getContentPosition')
                ->will($this->returnValue(1));
        
        $blockManager2 = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\ScriptBundle\Core\Block\AlBlockManagerScript')
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $blockManager2->expects($this->any())
            ->method('get')
            ->will($this->returnValue($block));
        
        $factory = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory->expects($this->any())
            ->method('createBlock')
            ->will($this->onConsecutiveCalls($blockManager1, $blockManager2));
              
        $slotManager = new AlSlotManager($this->dispatcher, $this->translator, new AlSlot('test', array('repeated' => 'language')), $this->alPage, $this->alLanguage, $factory, array(1,2));
        
        $this->assertEquals(2, $slotManager->length());
        $this->assertEquals("Text", $slotManager->first()->get()->getClassName());
        $this->assertEquals(1, $slotManager->first()->get()->getContentPosition());
        $this->assertTrue($slotManager->deleteBlock(1));
        $this->assertEquals(1, $slotManager->length());
        $this->assertEquals("Script", $slotManager->first()->get()->getClassName());
        $this->assertEquals(1, $slotManager->first()->get()->getContentPosition());
    }
    
    public function testDeleteContents()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');        
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(1));
        
        $block->expects($this->any())
                ->method('getContentPosition')
                ->will($this->returnValue(1));
        
        $block->expects($this->any())
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
            ->will($this->returnValue($block));
        
        // Block Manager 2
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');        
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));
        
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue("Script"));
        
        $block->expects($this->any())
                ->method('getContentPosition')
                ->will($this->returnValue(1));
        
        $blockManager2 = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\ScriptBundle\Core\Block\AlBlockManagerScript')
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $blockManager2->expects($this->any())
            ->method('get')
            ->will($this->returnValue($block));
        
        $blockManager2->expects($this->once())
                ->method('delete')
                ->will($this->returnValue(true));
        
        $factory = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $factory->expects($this->any())
            ->method('createBlock')
            ->will($this->onConsecutiveCalls($blockManager1, $blockManager2));
             
        $slotManager = new AlSlotManager($this->dispatcher, $this->translator, new AlSlot('test', array('repeated' => 'language')), $this->alPage, $this->alLanguage, $factory, array(1,2));
        
        $slotManager->deleteBlocks(); 
        $this->assertEquals(0, $slotManager->length());
    }
    
    public function testFirst()
    {
        $this->assertNull($this->slotManager->first());
        
        $this->addBlockManagerOnlyWithClassName('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText');
        
        $this->assertEquals('Text', $this->slotManager->first()->get()->getClassName());
    }
    
    public function testLast()
    {
        $this->assertNull($this->slotManager->last());
        
        $this->addBlockManagerOnlyWithClassName('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText');        
        $this->assertEquals('Text', $this->slotManager->last()->get()->getClassName());
        
        $this->addBlockManagerOnlyWithClassName('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\ScriptBundle\Core\Block\AlBlockManagerScript', 'Script');        
        $this->assertEquals('Script', $this->slotManager->last()->get()->getClassName());
    }
    
    public function testLastAdded()
    {
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
        $this->assertTrue($this->slotManager->addBlock('Text'));
        $this->assertEquals('Text', $this->slotManager->lastAdded()->get()->getClassName());
        
        $this->addBlockManagerOnlyWithClassName('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\ScriptBundle\Core\Block\AlBlockManagerScript', 'Script');   
        $this->assertEquals('Script', $this->slotManager->lastAdded()->get()->getClassName());
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $block->expects($this->any())
                ->method('getClassName')
                ->will($this->returnValue('Media'));  
        
        $factory = $this->setUpFactory('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\MediaBundle\Core\Block\AlBlockManagerMedia', $block);
        $this->slotManager->setBlockManagerFactory($factory);
        $this->assertTrue($this->slotManager->addBlock('Media', 1));
        $this->assertEquals('Media', $this->slotManager->lastAdded()->get()->getClassName());
        $this->assertEquals('Script', $this->slotManager->last()->get()->getClassName());
    }
    
    public function testIndexAt()
    {
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
        $this->assertEquals(0, $this->slotManager->length());
        $this->addBlockManagerOnlyWithClassName('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText');
        $this->assertEquals(1, $this->slotManager->length());
        $this->addBlockManagerOnlyWithClassName('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\ScriptBundle\Core\Block\AlBlockManagerScript', 'Script'); 
        $this->assertEquals(2, $this->slotManager->length());
    }
    
    public function testGetBlockManager()
    {   
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
        $this->assertTrue($this->slotManager->addBlock('Text'));
        
        $this->assertEquals($this->slotManager->first(), $this->slotManager->getBlockManager($this->slotManager->first()->get()->getId()));
    }
    
    public function testToArray()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $this->setUpFactory('Text', $block);
        
        $this->assertTrue($this->slotManager->addBlock());
        
        $blockManagers = $this->slotManager->toArray();
        $this->assertEquals(count($blockManagers),count($this->slotManager->getBlockManagers()));
        
        $array = $blockManagers[0];print_r($blockManagers);exit;
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
        
        $this->assertTrue($this->slotManager->addBlock($type));
    }
}





