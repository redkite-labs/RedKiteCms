<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\EventSystem\Listener\Exception;

use RedKiteCms\Bridge\Monolog\DataLogger;
use RedKiteCms\Exception\Publish\PageNotPublishedException;
use RedKiteCms\Exception\RedKiteCmsExceptionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ExceptionListener is the object deputed to listen for kernel exception
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Listener\Exception
 */
class ExceptionListener
{
    /**
     * @type \Twig_Environment
     */
    private $twig;
    /**
     * @type \Symfony\Component\Translation\TranslatorInterface
     */
    private $translator;
    /**
     * @type bool
     */
    private $debug;

    /**
     * Constructor
     *
     * @param \Twig_Environment $twig
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     * @param bool $debug
     */
    public function __construct(\Twig_Environment $twig, TranslatorInterface $translator, $debug = false)
    {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->debug = $debug;
    }

    /**
     * Handles the exceptions
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof AuthenticationCredentialsNotFoundException) {
            return new RedirectResponse("/login");
        }

        $message = $exception->getMessage();
        if ($exception instanceof NotFoundHttpException || $exception instanceof PageNotPublishedException) {
            $this->render404page($event, $message);

            return;
        }

        if (!$exception instanceof RedKiteCmsExceptionInterface) {
            DataLogger::log($message, DataLogger::CRITICAL);

            if ($this->debug) {
                throw $exception;
            }
            $this->setUpResponse($event, $message);

            return;
        }

        $jsonMessage = json_decode($message, true);
        if (!is_array($jsonMessage)) {
            $jsonMessage = array(
                'message' => $message,
            );
        }

        $parameters = array(
            'message' => '',
            'parameters' => array(),
            'domain' => 'RedKiteCms',
            'locale' => null,
        );
        $cleanedParameters = array_intersect_key($jsonMessage, $parameters);
        $parameters = array_merge($parameters, $cleanedParameters);

        $message = $this->translator->trans(
            $parameters["message"],
            $parameters["parameters"],
            $parameters["domain"],
            $parameters["locale"]
        );

        if (array_key_exists("show_exception", $jsonMessage) && $jsonMessage["show_exception"]) {
            $message = substr(strrchr(get_class($exception), '\\'), 1) . ": " . $message;
        }

        $this->setUpResponse($event, $message);

        DataLogger::log($message, DataLogger::ERROR);
    }

    private function setUpResponse(GetResponseForExceptionEvent $event, $message)
    {
        $response = new Response($message, 404);
        $event->setResponse($response);
    }

    private function render404page(GetResponseForExceptionEvent $event, $message)
    {
        $template = 'RedKiteCms/Resources/views/Frontend/404.html.twig';
        $content = $this->twig->render($template, array("message" => $message));
        $this->setUpResponse($event, $content);
        DataLogger::log($message, DataLogger::ERROR);
    }
}
