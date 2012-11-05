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

namespace AlphaLemon\ThemeEngineBundle\Tests\Unit\Core\Listener;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\Listener\NotFoundErrorHandlerListener;

/**
 * CmsBootstrapListenerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class NotFoundErrorHandlerListenerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent')
                            ->disableOriginalConstructor()
                            ->getMock();
    }

    public function testTheCustomErrorPageIsNotRenderedWhenExceptionIsNotANotFoundHttpException()
    {
        $this->setUpException('\RuntimeException');
       
        $this->event
             ->expects($this->never())
             ->method('getResponse');
        
        $this->event
             ->expects($this->never())
             ->method('setResponse');
     
        $this->templating
             ->expects($this->never())
             ->method('renderResponse');
        
       $listener = new NotFoundErrorHandlerListener($this->templating);
       $listener->onKernelException($this->event);
    }

    public function testTheCustomErrorPageIsRendered()
    {
        $response = $this->getMock('Symfony\Component\HttpFoundation\Response');
        
        $this->setUpException();
       
        $this->event
             ->expects($this->once())
             ->method('getResponse');
        
        $this->event
             ->expects($this->once())
             ->method('setResponse')
             ->with($response);
     
        $this->templating
             ->expects($this->once())
             ->method('renderResponse')
             ->will($this->returnValue($response));
        
       
       $listener = new NotFoundErrorHandlerListener($this->templating);
       $listener->onKernelException($this->event);
    }
    
    private function setUpException($class = '\Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
    {
        $this->event
             ->expects($this->once())
             ->method('getException')
             ->will($this->returnValue(new $class()));
        
    }
}
