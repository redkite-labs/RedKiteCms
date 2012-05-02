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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Content\Block;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;

class AlBlockManagerFunctionalTest extends AlBlockManager
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

class AlBlockManagerTest extends TestCase 
{    
    private $dispatcher;
    private $translator;
    private  $testAlBlockManager;
      
    protected function setUp() 
    {
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        
        $this->testAlBlockManager = new AlBlockManagerFunctionalTest($this->dispatcher, $this->translator);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetFailsWhenANotValidPropelObjectIsGiven()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        
        $this->testAlBlockManager->set($block);
    }
    
    public function testSetANullAlBlock()
    {
        $this->testAlBlockManager->set(null);
        $this->assertNull($this->testAlBlockManager->get());
    }
    
    public function testSetAlBlock()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $this->testAlBlockManager->set($block);
        $this->assertEquals($block, $this->testAlBlockManager->get());
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddFailsWhenAnyParamIsGiven()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $this->translator->expects($this->once())
            ->method('trans');
        
        $block->expects($this->any())
            ->method('save')
            ->will($this->throwException(new \InvalidArgumentException));
        
        $params = array();
        $this->testAlBlockManager->set($block);
        $this->testAlBlockManager->save($params); 
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddFailsWhenAnyExpectedParamIsGiven()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $this->translator->expects($this->once())
            ->method('trans');
        
        $block->expects($this->any())
            ->method('save')
            ->will($this->throwException(new \InvalidArgumentException));
        
        $params = array('Fake' => 'content');
        $this->testAlBlockManager->set($block);
        $this->testAlBlockManager->save($params); 
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddFailsWhenOneExpectedParamIsMissing()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $this->translator->expects($this->once())
            ->method('trans');
        
        $block->expects($this->any())
            ->method('save')
            ->will($this->throwException(new \InvalidArgumentException));
        
        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "HtmlContent" => 'Fake content', 
                        "ClassName" => "Text");
        
        $this->testAlBlockManager->set($block);
        $this->testAlBlockManager->save($params); 
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddFailsWhenTheDefaultValueDoesNotReturnAnArray()
    {
        $testAlBlockManager = new AlBlockManagerFake($this->dispatcher, $this->translator);
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $this->translator->expects($this->once())
            ->method('trans');
        
        $block->expects($this->any())
            ->method('save')
            ->will($this->throwException(new \InvalidArgumentException));
        
        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "SlotName" => 'test',
                        "ClassName" => "Text");        
        $testAlBlockManager->set($block);
        $testAlBlockManager->save($params);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddFailsWhenTheDefaultValueHasAnyOfTheRequiredOptions()
    {
        $testAlBlockManager = new AlBlockManagerFake1($this->dispatcher, $this->translator);
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $this->translator->expects($this->once())
            ->method('trans');
        
        $block->expects($this->any())
            ->method('save')
            ->will($this->throwException(new \InvalidArgumentException));
        
        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "SlotName" => 'test',
                        "ClassName" => "Text");        
        $testAlBlockManager->set($block);
        $testAlBlockManager->save($params);
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
        
        $block->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));
        
        $this->testAlBlockManager->set($block);
        $result = $this->testAlBlockManager->save($params); 
        $this->assertEquals(true, $result);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testEditFailsWhenAnyParamIsGiven()
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $this->translator->expects($this->once())
            ->method('trans');
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));
        
        $params = array();
        $this->testAlBlockManager->set($block);
        $this->testAlBlockManager->save($params); 
    }
    
    public function testEditHtmlContent()
    {
        $this->dispatcher->expects($this->exactly(2))
            ->method('dispatch');
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));
        
        $block->expects($this->once())
                ->method('save')
                ->will($this->returnValue(true));
        
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
        
        $params = array('HtmlContent' => 'changed html content',
            'InternalJavascript' => 'changed internal javascript content',
            'ExternalJavascript' => 'changed external javascript content',
            'InternalJavascript' => 'changed internal stylesheet content',
            'ExternalStylesheet' => 'changed external stylesheet content',
            );
        $this->testAlBlockManager->set($block);
        $result = $this->testAlBlockManager->save($params); 
        $this->assertEquals(true, $result);
        $this->assertEquals('changed html content', $this->testAlBlockManager->get()->getHtmlContent());
        $this->assertEquals('changed internal javascript content', $this->testAlBlockManager->get()->getInternalJavascript());
        $this->assertEquals('changed external javascript content', $this->testAlBlockManager->get()->getExternalJavascript());
        $this->assertEquals('changed internal stylesheet content', $this->testAlBlockManager->get()->getInternalStylesheet());
        $this->assertEquals('changed external stylesheet content', $this->testAlBlockManager->get()->getExternalStylesheet());
    }
    
    /**
     * @expectedException RuntimeException
     */
    public function testDeleteBlockFailsWhenAnyBlockIsSetted()
    {
        $this->dispatcher->expects($this->never())
            ->method('dispatch');
        
        $this->testAlBlockManager->delete();
    }
    
    public function testDeleteBlock()
    {
        $this->dispatcher->expects($this->exactly(2))
            ->method('dispatch');
        
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getId')
                ->will($this->returnValue(2));
        
        $block->expects($this->once())
                ->method('save')
                ->will($this->returnValue(true));
        
        $block->expects($this->once())
                ->method('getToDelete')
                ->will($this->returnValue(1));
        
        $this->testAlBlockManager->set($block);
        $result = $this->testAlBlockManager->delete();  
        $this->assertEquals(true, $result);        
        $this->assertEquals(1, $this->testAlBlockManager->get()->getToDelete());
    }
    
    public function testToArrayReturnsAnEmptyArrayWhenAnyBlockHasBeenSet()
    { 
        $array = $this->testAlBlockManager->toArray();
        
        $this->assertEmpty($array);
         
    }
    
    public function testAlBlockToArray()
    { 
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
                ->method('getHtmlContent')
                ->will($this->returnValue('my fancy content'));
        
        $this->testAlBlockManager->set($block);
        $array = $this->testAlBlockManager->toArray();
        
        $this->assertTrue(array_key_exists('HideInEditMode', $array));
        $this->assertTrue(array_key_exists('HtmlContent', $array));
        $this->assertTrue(array_key_exists('ExternalJavascript', $array));
        $this->assertTrue(array_key_exists('InternalJavascript', $array));
        $this->assertTrue(array_key_exists('ExternalStylesheet', $array));
        $this->assertTrue(array_key_exists('InternalStylesheet', $array));
        $this->assertTrue(array_key_exists('Block', $array));
        
        $this->assertEquals('my fancy content', $array['HtmlContent']);
    }
}