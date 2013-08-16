<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
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

namespace RedKiteLabs\ThemeEngineBundle\Core\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Listens for kernel exception
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class NotFoundErrorHandlerListener
{
    protected $templating;
    
    public function __construct(EngineInterface $templating) {
        $this->templating = $templating;
    }

    /**
    * Returns a custom error page for 404 errors
    *
    * @param GetResponseForExceptionEvent $event
    */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        
        if ($exception instanceof NotFoundHttpException) {
            $response = $event->getResponse();

            $values = array(
                'status_code' => 404,
                'message' => $exception->getMessage(),
            );
            
            $event->setResponse($this->templating->renderResponse('RedKiteLabsThemeEngineBundle:Error:error.html.twig', $values, $response));
        }
    }
}
