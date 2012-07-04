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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;

/**
 * AlBlockManagerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerTest extends TestCase 
{    
    private $dispatcher;
    private  $blockManager;
      
    protected function setUp() 
    {
        parent::setUp();
        
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        
        $this->validator = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->blockRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->blockManager = new AlBlockManagerUnitTest($this->dispatcher, $this->blockRepository, $this->validator);
    }
    
    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException
     */
    public function testSetFailsWhenANotValidPropelObjectIsGiven()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');        
        $this->blockManager->set($block);
    }
    
    public function testSetANullAlBlock()
    {
        $this->blockManager->set(null);
        $this->assertNull($this->blockManager->get());
    }
    
    public function testSetAlBlock()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $this->blockManager->set($block);
        
        $this->assertEquals($block, $this->blockManager->get());
    }
    
    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\EmptyParametersException
     */
    public function testAddFailsWhenAnyParamIsGiven()
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $this->validator->expects($this->once())
            ->method('checkEmptyParams')
            ->will($this->throwException(new General\EmptyParametersException()));
                
        $params = array();
        $this->blockManager->save($params); 
    }
    
    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterExpectedException
     */
    public function testAddFailsWhenAnyExpectedParamIsGiven()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $this->validator->expects($this->once())
            ->method('checkRequiredParamsExists')
            ->will($this->throwException(new General\ParameterExpectedException()));
        
        $params = array('Fake' => 'content');
        $this->blockManager->set($block);
        $this->blockManager->save($params); 
    }
    
    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterExpectedException
     */
    public function testAddFailsWhenOneExpectedParamIsMissing()
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $this->validator->expects($this->once())
            ->method('checkRequiredParamsExists')
            ->will($this->throwException(new General\ParameterExpectedException()));
        
        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "HtmlContent" => 'Fake content', 
                        "ClassName" => "Text");
        
        $this->blockManager->save($params); 
    }
    
    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException
     */
    public function testAddFailsWhenTheDefaultValueDoesNotReturnAnArray()
    {
        $blockManager = new AlBlockManagerFake($this->dispatcher, $this->blockRepository, $this->validator);
        
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "SlotName" => 'test',
                        "ClassName" => "Text"); 
        
        $blockManager->save($params);
    }
    
    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterExpectedException
     */
    public function testAddFailsWhenTheDefaultValueHasAnyOfTheRequiredOptions()
    {
        $blockManager = new AlBlockManagerFake1($this->dispatcher, $this->blockRepository, $this->validator);        
        
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $this->validator->expects($this->once())
            ->method('checkOnceValidParamExists')
            ->will($this->throwException(new General\ParameterExpectedException()));
                
        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "SlotName" => 'test',
                        "ClassName" => "Text"); 
        
        $blockManager->save($params);
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testAddBlockThrownAnUnespectedException()
    {
        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "SlotName" => 'test',
                        "ClassName" => "Text");
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $this->blockRepository->expects($this->once())
            ->method('startTransaction');
        
        $this->blockRepository->expects($this->once())
            ->method('rollBack');
        
        $this->blockRepository->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException()));
        
        $this->blockRepository->expects($this->once())
                ->method('setModelObject')
                ->with($block)
                ->will($this->returnSelf());
        
        $this->blockManager->set($block);
        $this->blockManager->save($params); 
    }
    
    public function testSaveBlockDuringAddFails()
    {
        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "SlotName" => 'test',
                        "ClassName" => "Text");
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $this->blockRepository->expects($this->once())
            ->method('startTransaction');
        
        $this->blockRepository->expects($this->once())
            ->method('rollBack');
        
        $this->blockRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(false));
        
        $this->blockRepository->expects($this->once())
                ->method('setModelObject')
                ->with($block)
                ->will($this->returnSelf());
        
        $this->blockManager->set($block);
        $result = $this->blockManager->save($params); 
        $this->assertEquals(false, $result);
    }
    
    public function testAdd()
    {
        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "SlotName" => 'test',
                        "ClassName" => "Text");
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $this->dispatcher->expects($this->exactly(2))
            ->method('dispatch');
        
        $this->blockRepository->expects($this->once())
            ->method('startTransaction');
        
        $this->blockRepository->expects($this->once())
            ->method('commit');
        
        $this->blockRepository->expects($this->never())
            ->method('rollback');
        
        $this->blockRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));
        
        $this->blockRepository->expects($this->once())
                ->method('setModelObject')
                ->with($block)
                ->will($this->returnSelf());
        
        $this->blockManager->set($block);
        $result = $this->blockManager->save($params); 
        $this->assertEquals(true, $result);
    }
    
    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\EmptyParametersException 
     */
    public function testEditFailsWhenAnyParamIsGiven()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));
        
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $this->validator->expects($this->once())
            ->method('checkEmptyParams')
            ->will($this->throwException(new General\EmptyParametersException()));
        
        $this->blockRepository->expects($this->never())
                ->method('setModelObject');
        
        $params = array();
        $this->blockManager->set($block);
        $this->blockManager->save($params); 
    }
    
    public function testSaveBlockDuringEditFails()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));
        
        $this->dispatcher->expects($this->once())   
            ->method('dispatch');
        
        $this->blockRepository->expects($this->once())
            ->method('startTransaction');
        
        $this->blockRepository->expects($this->once())
            ->method('rollBack');
        
        $this->blockRepository->expects($this->once())
            ->method('save')
            ->will($this->returnValue(false));
        
         $this->blockRepository->expects($this->once())
                ->method('setModelObject')
                ->with($block);
        
        $params = array('HtmlContent' => 'changed html content' );
        $this->blockManager->set($block);
        $result = $this->blockManager->save($params); 
        $this->assertEquals(false, $result);
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testEditBlockThrownAnUnespectedException()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));
        
        $params = array('HtmlContent' => 'changed html content' );
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $this->blockRepository->expects($this->once())
            ->method('startTransaction');
        
        $this->blockRepository->expects($this->once())
            ->method('rollBack');
        
        $this->blockRepository->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \RuntimeException()));
        
        $this->blockRepository->expects($this->once())
                ->method('setModelObject')
                ->with($block)
                ->will($this->returnSelf());
        
        $this->blockManager->set($block);
        $this->blockManager->save($params); 
    }
    
    public function testEdit()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));
        
        $block->expects($this->once())
                ->method('getHtmlContent')
                ->will($this->returnValue('changed html content'));
        
        $block->expects($this->once())
                ->method('getInternalJavascript')
                ->will($this->returnValue('changed internal javascript content'));
        
        $block->expects($this->once())
                ->method('getExternalJavascript')
                ->will($this->returnValue('changed external javascript content'));
        
        $block->expects($this->once())
                ->method('getInternalStylesheet')
                ->will($this->returnValue('changed internal stylesheet content'));
        
        $block->expects($this->once())
                ->method('getExternalStylesheet')
                ->will($this->returnValue('changed external stylesheet content'));
        
        $this->dispatcher->expects($this->exactly(2))
            ->method('dispatch');
        
        $this->blockRepository->expects($this->once())
            ->method('startTransaction');
        
        $this->blockRepository->expects($this->once())
            ->method('commit');
        
        $this->blockRepository->expects($this->never())
            ->method('rollback');
        
        $this->blockRepository->expects($this->once())
                ->method('save')
                ->will($this->returnValue(true));
        
         $this->blockRepository->expects($this->once())
                ->method('setModelObject')
                ->with($block);
        
        $params = array('HtmlContent' => 'changed html content',
            'InternalJavascript' => 'changed internal javascript content',
            'ExternalJavascript' => 'changed external javascript content',
            'InternalJavascript' => 'changed internal stylesheet content',
            'ExternalStylesheet' => 'changed external stylesheet content',
            );
        $this->blockManager->set($block);
        $result = $this->blockManager->save($params); 
        $this->assertEquals(true, $result);
        $this->assertEquals('changed html content', $this->blockManager->get()->getHtmlContent());
        $this->assertEquals('changed internal javascript content', $this->blockManager->get()->getInternalJavascript());
        $this->assertEquals('changed external javascript content', $this->blockManager->get()->getExternalJavascript());
        $this->assertEquals('changed internal stylesheet content', $this->blockManager->get()->getInternalStylesheet());
        $this->assertEquals('changed external stylesheet content', $this->blockManager->get()->getExternalStylesheet());
    }
    
    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterIsEmptyException
     */
    public function testDeleteBlockFailsWhenAnyBlockIsSetted()
    {
        $this->dispatcher->expects($this->never())
            ->method('dispatch');
        
        $this->blockManager->set(null);
        $this->blockManager->delete();
    }
    
    public function testSaveBlockDuringDeleteFails()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));
        
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $this->blockRepository->expects($this->once())
            ->method('startTransaction');
        
        $this->blockRepository->expects($this->once())
            ->method('rollBack');
        
        $this->blockRepository->expects($this->once())
                ->method('setModelObject')
                ->with($block)
                ->will($this->returnSelf());
        
        $this->blockRepository->expects($this->any())
                ->method('delete')
                ->will($this->returnValue(false));
        
        $this->blockManager->set($block);
        $result = $this->blockManager->delete();  
        $this->assertEquals(false, $result);
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testDeleteBlockThrownAnUnespectedException()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $this->blockRepository->expects($this->once())
            ->method('startTransaction');
        
        $this->blockRepository->expects($this->once())
            ->method('rollBack');
        
        $this->blockRepository->expects($this->once())
            ->method('delete')
            ->will($this->throwException(new \RuntimeException()));
        
        $this->blockRepository->expects($this->once())
                ->method('setModelObject')
                ->with($block)
                ->will($this->returnSelf());
        
        $this->blockManager->set($block);
        $this->blockManager->delete(); 
    }
    
    public function testDeleteBlock()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));
        
        $block->expects($this->any())
                ->method('getToDelete')
                ->will($this->returnValue(1));
        
        $this->dispatcher->expects($this->exactly(2))
            ->method('dispatch');
        
        $this->blockRepository->expects($this->once())
                ->method('setModelObject')
                ->with($block)
                ->will($this->returnSelf());
        
        $this->blockRepository->expects($this->any())
                ->method('delete')
                ->will($this->returnValue(true));
        
        $this->blockManager->set($block);
        $result = $this->blockManager->delete();  
        $this->assertEquals(true, $result);        
        $this->assertEquals(1, $this->blockManager->get()->getToDelete());
    }
    
    public function testToArrayReturnsAnEmptyArrayWhenAnyBlockHasBeenSet()
    { 
        $array = $this->blockManager->toArray();
        
        $this->assertEmpty($array);
         
    }
    
    public function testAlBlockToArrayReturnsAnEmptyArrayWhenBlockIsNull()
    { 
        $this->blockManager->set(null);
        $array = $this->blockManager->toArray();
        
        $this->assertEmpty($array);
    }
    
    public function testAlBlockToArray()
    { 
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getHtmlContent')
                ->will($this->returnValue('my fancy content'));
        
        $this->blockManager->set($block);
        $array = $this->blockManager->toArray();
        
        $this->assertTrue(array_key_exists('HideInEditMode', $array));
        $this->assertTrue(array_key_exists('HtmlContent', $array));
        $this->assertTrue(array_key_exists('ExternalJavascript', $array));
        $this->assertTrue(array_key_exists('InternalJavascript', $array));
        $this->assertTrue(array_key_exists('ExternalStylesheet', $array));
        $this->assertTrue(array_key_exists('InternalStylesheet', $array));
        $this->assertTrue(array_key_exists('Block', $array));
        
        $this->assertEquals('my fancy content', $array['HtmlContent']);
    }
    
    private function setModelObjectMethods($block)
    {
        $this->blockRepository->expects($this->any())
            ->method('setModelObject')
            ->with($block)
            ->will($this->returnSelf());
        
        $this->blockRepository->expects($this->any())
            ->method('getModelObject')
            ->will($this->returnValue($block));
    }
}

class AlBlockManagerUnitTest extends AlBlockManager
{
    public function getDefaultValue()
    {
        return array("HtmlContent" => "Test value");
    }
}

// This class has default value not valid because an array is required
class AlBlockManagerFake extends AlBlockManager
{
    public function getDefaultValue()
    {
        return "Test value";
    }
}

// This class has a valid default value but any of the available options is defined
class AlBlockManagerFake1 extends AlBlockManager
{
    public function getDefaultValue()
    {
        return array("Fake" => "Test value");
    }
}