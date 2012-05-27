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
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactory;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPage;

use Symfony\Bundle\AsseticBundle\Tests\TestKernel;

/**
 * AlBlockManagerFactoryTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerFactoryTest extends TestCase
{    
    private $dispatcher;
      
    protected function setUp() 
    {
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->blockModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlBlockModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->factory = new AlBlockManagerFactory($this->dispatcher);
    }
    
    public function testCreateBlockReturnsNullWhenTheBlockParamIsNull()
    {
        $contenManager = $this->factory->createBlock($this->blockModel, null);
        $this->assertNull($contenManager);
    }
    
    public function testCreateBlockReturnsNullWhenTheBlokParamIsEmptyString()
    {
        $contenManager = $this->factory->createBlock($this->blockModel, "");
        $this->assertNull($contenManager);
    }
    
    public function testCreateBlockReturnsNullWhenTheBlokParamIsNotStringOrAlBlock()
    {
        $contenManager = $this->factory->createBlock($this->blockModel, array());
        $this->assertNull($contenManager);
    }
    
    public function testCreateBlockFailsWhenAnInesistentBlockTypeIsGiven()
    {
        $contenManager = $this->factory->createBlock($this->blockModel, 'fake');
        $this->assertNull($contenManager);
    }
    
    public function testCreateABlockFromAValidStringType()
    {
        $contenManager = $this->factory->createBlock($this->blockModel, 'text');
        $this->assertInstanceOf('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager', $contenManager);
    }
    
    public function testCreateABlockFromAValidAlBlockObject()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
                ->method('getClassName')
                ->will($this->returnValue('Text')); 
        
        $blockManager = $this->factory->createBlock($this->blockModel, $block);
        $this->assertInstanceOf('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager', $blockManager);
    }
    
    public function testCreatingFromARemovedBlockObjectDeletesTheBlock()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        $block->expects($this->once())
                ->method('setToDelete'); 
        
        $block->expects($this->once())
                ->method('save'); 
        
        $block->expects($this->any())
                ->method('getToDelete')
                ->will($this->returnValue(1)); 
        
        $contenManager = $this->factory->createBlock($this->blockModel, $block);
        $this->assertNull($contenManager);
        $this->assertEquals(1, $block->getToDelete());
    }
    
    public function testCreateBlockWithTranslator()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
                ->method('getClassName')
                ->will($this->returnValue('Text')); 
        
        $translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        
        $factory = new AlBlockManagerFactory($this->dispatcher, $translator);
        $contenManager = $factory->createBlock($this->blockModel, $block);
        $this->assertInstanceOf('Symfony\Component\Translation\TranslatorInterface', $contenManager->getTranslator());
    }
}