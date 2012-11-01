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

namespace AlphaLemon\ThemeEngineBundle\Core\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Listens for kernel exception
 *
 * @author alphalemon <webmaster@alphalemon.com>
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
            
            $event->setResponse($this->templating->renderResponse('AlphaLemonThemeEngineBundle:Error:error.html.twig', $values, $response));
        }
    }
}
