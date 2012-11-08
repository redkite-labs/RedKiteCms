<?php
/**
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

namespace AlphaLemon\ThemeEngineBundle\Tests\Unit\Core\Rendering\Listener;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\Rendering\Listener\BasePageRenderingListener;

class PageRenderingListenerTester extends BasePageRenderingListener
{
    private $slotContents;
    
    public function setSlotContents($slotContents)
    {
        $this->slotContents = $slotContents;
    }
    
    protected function renderSlotContents()
    {
        return $this->slotContents;
    }
}

/**
 * BasePageRenderingListenerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class BasePageRenderingListenerTest extends TestCase
{
    private $event;
    private $response;
    
    protected function setUp()
    {
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->response = $this->getMock('Symfony\Component\HttpFoundation\Response');
        $this->slotContent = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\Rendering\SlotContent\AlSlotContent');
        
        $this->event = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Rendering\Event\PageRenderer\BeforePageRenderingEvent')
                            ->disableOriginalConstructor()
                            ->getMock();
        $this->event->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($this->response));
        
        $this->listener = new PageRenderingListenerTester($this->container);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage "renderSlotContents" method must return an array 
     */
    public function testAnExceptionIsThrownWhenRenderSlotContentsMethodDoesNotReturnAnArray()
    {
        $this->listener->setSlotContents('Fake');
        $this->listener->onPageRendering($this->event);
    }
    
    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Any slot defined for the event AlphaLemon\ThemeEngineBundle\Tests\Unit\Core\Rendering\Listener\PageRenderingListenerTester
     */
    public function testAnExceptionIsThrownWhenRenderSlotContentsNotDefinesTheSlotName()
    {
        $this->setUpSlotContent();
        
        $this->listener->setSlotContents(array($this->slotContent));
        $this->listener->onPageRendering($this->event);
    }
    
    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Any action has been specified for the event AlphaLemon\ThemeEngineBundle\Tests\Unit\Core\Rendering\Listener\PageRenderingListenerTester
     */
    public function testAnExceptionIsThrownWhenRenderSlotContentsNotDefinesTheAction()
    {
        $this->setUpSlotContent('logo');
        
        $this->listener->setSlotContents(array($this->slotContent));
        $this->listener->onPageRendering($this->event);
    }
    
    public function testNothingIsMadeWhenRenderSlotContentsNotDefinesTheReplaceContent()
    {
        $this->setUpSlotContent('logo', true);
        
        $this->response->expects($this->never())
            ->method('getContent');
        
        $this->listener->setSlotContents(array($this->slotContent));
        $this->listener->onPageRendering($this->event);
    }
    
    public function testContentIsReplaced()
    {
        $replacingContent = 'my awesome content';
        $this->setUpSlotContent('logo', true, $replacingContent);
        
        $this->response->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue('<!-- BEGIN LOGO BLOCK -->a replaceable content<!-- END LOGO BLOCK -->'));
        
        $this->response->expects($this->once())
            ->method('setContent')
            ->with($replacingContent);
        
        $this->listener->setSlotContents(array($this->slotContent));
        $this->listener->onPageRendering($this->event);
    }
    
    public function testContentIsInject()
    {
        $replacingContent = 'my awesome content';
        $this->setUpSlotContent('logo', false, $replacingContent);
        
        $existingContent = 'a replaceable content';
        $this->response->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue(sprintf('<!-- BEGIN LOGO BLOCK -->%s<!-- END LOGO BLOCK -->', $existingContent)));
        
        $this->response->expects($this->once())
            ->method('setContent')
            ->with($existingContent . PHP_EOL . $replacingContent);
        
        $this->listener->setSlotContents(array($this->slotContent));
        $this->listener->onPageRendering($this->event);
    }
    
    public function testContentIsInject1()
    {
        $replacingContent = 'my awesome content';
        
        $this->slotContent->expects($this->once())
            ->method('isReplacing')
            ->will($this->returnValue(false));        
        
        $this->slotContent->expects($this->exactly(2))
            ->method('getSlotName')
            ->will($this->returnValue('menu'));
        
        $this->slotContent->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($replacingContent));
        
        $existingContent = 'a replaceable content';
        $this->response->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue(sprintf('<!-- BEGIN LOGO BLOCK -->%s<!-- END LOGO BLOCK -->', $existingContent)));
        
        $this->listener->setSlotContents(array($this->slotContent));
        $this->listener->onPageRendering($this->event);
    }
    
    private function setUpSlotContent($slotName = null, $replacing = null, $content = null)
    {       
        $expectation = (null === $content) ? $this->once() : $this->exactly(2);
        $this->slotContent->expects($expectation)
            ->method('getSlotName')
            ->will($this->returnValue($slotName));
        if (null === $slotName) return;
        
        $this->slotContent->expects($this->once())
            ->method('isReplacing')
            ->will($this->returnValue($replacing));        
        if (null === $replacing) return;
        
        $expectation = (null === $content) ? $this->once() : $this->exactly(2);
        $this->slotContent->expects($expectation)
            ->method('getContent')
            ->will($this->returnValue($content));
    }
}
