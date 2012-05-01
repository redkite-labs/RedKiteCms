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
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactory;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPage;

use Symfony\Bundle\AsseticBundle\Tests\TestKernel;

class AlBlockManagerFactoryTest extends TestCase
{    
    private $dispatcher;
    private $translator;
      
    protected function setUp() 
    {
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
    }
    
    public function testCreateBlockReturnsNullWhenTheBlockParamIsNull()
    {
        $contenManager = AlBlockManagerFactory::createBlock($this->dispatcher, $this->translator, null);
        $this->assertNull($contenManager);
    }
    
    public function testCreateBlockReturnsNullWhenTheBlokParamIsEmptyString()
    {
        $contenManager = AlBlockManagerFactory::createBlock($this->dispatcher, $this->translator, "");
        $this->assertNull($contenManager);
    }
    
    public function testCreateBlockReturnsNullWhenTheBlokParamIsNotStringOrAlBlock()
    {
        $contenManager = AlBlockManagerFactory::createBlock($this->dispatcher, $this->translator, array());
        $this->assertNull($contenManager);
    }
    
    public function testCreateBlockFailsWhenAnInesistentBlockTypeIsGiven()
    {
        $contenManager = AlBlockManagerFactory::createBlock($this->dispatcher, $this->translator, 'fake');
        $this->assertNull($contenManager);
    }
    
    public function testCreateABlockFromAValidStringType()
    {
        $contenManager = AlBlockManagerFactory::createBlock($this->dispatcher, $this->translator, 'text');
        $this->assertInstanceOf('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager', $contenManager);
    }
    
    public function testCreateABlockFromAValidAlBlockObject()
    {
        $block = new \AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock();
        
        $contenManager = AlBlockManagerFactory::createBlock($this->dispatcher, $this->translator, $block);
        $this->assertInstanceOf('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager', $contenManager);
    }
    
    public function testCreatingFromARemovedBlockObjectDeletesTheBlock()
    {
        $block = new \AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock();
        $block->setClassName('Fake');
        $this->assertEquals(0, $block->getToDelete());
        
        $contenManager = AlBlockManagerFactory::createBlock($this->dispatcher, $this->translator, $block);
        $this->assertNull($contenManager);
        $this->assertEquals(1, $block->getToDelete());
    }
}