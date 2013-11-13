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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Listener\Exception;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\RedKiteCmsExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Translator\AlTranslatorInterface;

/**
 * Listens for kernel exception
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ExceptionListener
{
    protected $templating;
    protected $translator;
    
    public function __construct(EngineInterface $templating, AlTranslatorInterface $translator)
    {
        $this->templating = $templating;
        $this->translator = $translator;
    }

    /**
    * Handles RedKiteCmsExceptionInterface exceptions
    *
    * @param GetResponseForExceptionEvent $event
    */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ( ! $exception instanceof RedKiteCmsExceptionInterface) {
            return;
        }
        
        $message = $exception->getMessage();
        $jsonMessage = json_decode($message, true);
        if ( ! is_array($jsonMessage)) {
            $jsonMessage = array(
                'message' => $message,
            );
        }
        
        $parameters = array(
            'message' => '',
            'parameters' => array(),
            'domain' => 'RedKiteCmsBundle',
            'locale' => null,
        ); 
        $cleanedParameters = array_intersect_key($jsonMessage, $parameters);
        $parameters = array_merge($parameters, $cleanedParameters);

        $message = $this->translator->translate(
            $parameters["message"],
            $parameters["parameters"],
            $parameters["domain"],
            $parameters["locale"]
        );

        $response = $this->templating->renderResponse(
            'RedKiteCmsBundle:Dialog:dialog.html.twig',
            array(
                'message' => $message,
            )
        );
        $response->setStatusCode(404);        
        $event->setResponse($response);
    }
}