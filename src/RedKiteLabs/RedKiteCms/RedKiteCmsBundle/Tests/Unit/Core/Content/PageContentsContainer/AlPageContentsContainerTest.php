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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\PageContentsContainer;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageContentsContainer\AlPageContentsContainer;

use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;

class AlPageContentsContainerTest extends TestCase
{   
    private $dispatcher;
    private $blockModel;
    private $pageContentsContainer;
      
    protected function setUp() 
    {
        parent::setUp();
        
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');        
        $this->blockModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlBlockModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->pageContentsContainer = new AlPageContentsContainer($this->dispatcher, $this->blockModel);
    }
    
    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterIsEmptyException
     */
    public function testRefreshThrownAnExceptionWhenPageAndLanguageHaveNotBeenSet()
    {
        $this->pageContentsContainer->refresh();
    }
    
    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterIsEmptyException
     */
    public function testRefreshThrownAnExceptionWhenPageHaveNotBeenSet()
    {
        $this->pageContentsContainer
                ->setIdLanguage(2)
                ->refresh();
    }
    
    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterIsEmptyException
     */
    public function testRefreshThrownAnExceptionWhenLanguageHaveNotBeenSet()
    {
        $this->pageContentsContainer
                ->setIdPage(2)
                ->refresh();
    }
    
    public function testAnEmptyArrayIsRetrievedWhenAnyBlockExists()
    {
        $this->blockModel->expects($this->once())
            ->method('retrieveContents')
            ->will($this->returnValue(array()));
        
        
        $this->pageContentsContainer
                ->setIdLanguage(2)
                ->setIdPage(2)
                ->refresh();
        
        $this->assertEquals(0, count($this->pageContentsContainer->getBlocks()));
    }
    
    public function testContentsAreRetrieved()
    {
        $blocks = array(
            $this->setUpBlock('logo'),
            $this->setUpBlock('logo'),
            $this->setUpBlock('menu'),
        );
        
        $this->blockModel->expects($this->once())
            ->method('retrieveContents')
            ->will($this->returnValue($blocks));
        
        
        $this->pageContentsContainer
                ->setIdLanguage(2)
                ->setIdPage(2)
                ->refresh();
        
        $this->assertEquals(2, count($this->pageContentsContainer->getBlocks()));
        $this->assertEquals(2, count($this->pageContentsContainer->getSlotBlocks('logo')));
        $this->assertEquals(1, count($this->pageContentsContainer->getSlotBlocks('menu')));
    }
    
    private function setUpBlock($slotName)
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('getSlotName')
            ->will($this->returnValue($slotName));
        
        return $block;
    }
}