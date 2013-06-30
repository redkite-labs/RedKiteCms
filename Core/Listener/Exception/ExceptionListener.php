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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Exception;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\AlphaLemonExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Listens for kernel exception
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class ExceptionListener
{
    protected $templating;
    protected $translator;
    protected $configuration;
    
    public function __construct(EngineInterface $templating, $translator, $configuration)
    {
        $this->templating = $templating;
        $this->translator = $translator;
        $this->configuration = $configuration;
    }

    /**
    * Handles AlphaLemonExceptionInterface exceptions
    *
    * @param GetResponseForExceptionEvent $event
    */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ( ! $exception instanceof AlphaLemonExceptionInterface) {
            return;
        }
        
        $message = $exception->getMessage();
        $jsonMessage = json_decode($message, true);
        if (is_array($jsonMessage)) {
            $configurationLanguage = $this->configuration->read('language');
            $parameters = array(
                'message' => '',
                'parameters' => array(),
                'domain' => 'messages',
                'locale' => $configurationLanguage,
            );
            $cleanedParameters = array_intersect_key($jsonMessage, $parameters);
            $parameters = array_merge($parameters, $cleanedParameters);
            
            if (empty($jsonMessage["locale"])) {
                $parameters["domain"] = $configurationLanguage . '_' . $parameters["domain"];
            }
            
            $message = $this->translator->trans(
                $parameters["message"],
                $parameters["parameters"],
                $parameters["domain"],
                $parameters["locale"]
            );
        }
        
        $values = array(
            'message' => $message,
        );
        
        $response = $this->templating->renderResponse('AlphaLemonCmsBundle:Dialog:dialog.html.twig', $values);
        $response->setStatusCode(404);        
        $event->setResponse($response);
    }
}