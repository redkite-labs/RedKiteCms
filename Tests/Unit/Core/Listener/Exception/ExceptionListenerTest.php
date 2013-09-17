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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Listener\Exception;

use RedKiteLabs\RedKiteCmsBundle\Core\Configuration\AlConfigurationManager;
use RedKiteLabs\RedKiteCmsBundle\Core\Listener\Exception\ExceptionListener;

/**
 * ExceptionListenerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ExceptionListenerTest extends \RedKiteLabs\RedKiteCmsBundle\Tests\TestCase
{
    protected $container;
    protected $dispatcher;
    protected $templateSlots;
    protected $containerAtSequenceAfterObjectCreation;
    
    protected function setUp()
    {
        parent::setUp();

        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->translator = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Translator\AlTranslatorInterface');

        $this->event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent')
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->listener = new ExceptionListener($this->templating, $this->translator);
    }
    
    public function testExceptionIsNotHandledWhenItDoesNotImplementTheRedKiteCmsExceptionInterface()
    {
        $this->event
            ->expects($this->once())
            ->method('getException')
            ->will($this->returnValue(new \RuntimeException()))
        ;

        $this->event
            ->expects($this->never())
            ->method('setResponse')
        ;

        $this->listener->onKernelException($this->event);
    }

    /**
     * @dataProvider exceptionProvider
     */
    public function testExceptionReceivesAMessageString($value)
    {
        $message = $value;
        $params = array();
        $domain = 'RedKiteCmsBundle';
        $locale = null;
        if (is_array($value)) {
            $message = $value["message"];
            $params = $value["parameters"];
            $domain = $value["domain"];
            $locale = $value["locale"];
            $value = json_encode($value);
        }

        $this->event
            ->expects($this->once())
            ->method('getException')
            ->will($this->returnValue($this->setupException($value)))
        ;

        $translatedMessage = "Error message translated";
        $this->translator
            ->expects($this->once())
            ->method("translate")
            ->with(
                $message,
                $params,
                $domain,
                $locale
            )
            ->will($this->returnValue($translatedMessage))
        ;

        $this->setupResponse($translatedMessage);

        $this->listener->onKernelException($this->event);
    }

    public function exceptionProvider()
    {
        return array(
            array(
                'value' => 'an_error',
            ),
            array(
                'value' => array(
                    'message' => 'an_error',
                    'parameters' => array(),
                    'domain' => 'RedKiteCmsBundle',
                    'locale' => null,
                ),
            ),
            array(
                'value' => array(
                    'message' => 'an_error',
                    'parameters' => array('%a_parameter%' => 'A value'),
                    'domain' => 'RedKiteCmsBundle',
                    'locale' => null,
                ),
            ),
            array(
                'value' => array(
                    'message' => 'an_error',
                    'parameters' => array('%a_parameter%' => 'A value'),
                    'domain' => 'messages',
                    'locale' => null,
                ),
            ),
            array(
                'value' => array(
                    'message' => 'an_error',
                    'parameters' => array('%a_parameter%' => 'A value'),
                    'domain' => 'messages',
                    'locale' => 'it',
                ),
            ),
        );
    }

    private function setupException($message)
    {
        $exception = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Exception\RedKiteCmsExceptionInterface', array('getMessage'));
        $exception
            ->expects($this->once())
            ->method('getMessage')
            ->will($this->returnValue($message))
        ;
        
        return $exception;
    }



    private function setupResponse($message)
    {
        $response = $this->getMock('Symfony\Component\HttpFoundation\Response');
        $response
            ->expects($this->once())
            ->method('setStatusCode')
            ->with(404)
        ;

        $this->templating
            ->expects($this->once())
            ->method('renderResponse')
            ->with(
                'RedKiteCmsBundle:Dialog:dialog.html.twig',
                array(
                    'message' => $message,
                )
            )
            ->will($this->returnValue($response))
        ;

        $this->event
            ->expects($this->once())
            ->method('setResponse')
            ->with($response)
        ;
    }
}