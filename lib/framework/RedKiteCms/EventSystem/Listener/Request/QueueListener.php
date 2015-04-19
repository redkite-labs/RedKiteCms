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

namespace RedKiteCms\EventSystem\Listener\Request;


use RedKiteCms\Rendering\Queue\QueueManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class QueueListener listens to Kernel Request and renders the not executed queue flow when it exists
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Listener\Request
 */
class QueueListener
{
    /**
     * @var \RedKiteCms\Rendering\Queue\QueueManager
     */
    private $queueManager;
    /**
     * @type \Symfony\Component\Security\Core\SecurityContext
     */
    private $securityContext;

    /**
     * Constructor
     *
     * @param \RedKiteCms\Rendering\Queue\QueueManager $queueManager
     * @param \Symfony\Component\Security\Core\SecurityContext $securityContext
     */
    public function __construct(QueueManager $queueManager, SecurityContext $securityContext)
    {
        $this->queueManager = $queueManager;
        $this->securityContext = $securityContext;
    }

    /**
     * Aligns the site slots
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if ($request->getMethod() == "POST") {
            return $event;
        }

        $token = $this->securityContext->getToken();
        if (null === $token) {
            return $event;
        }

        $data = $request->get("data");
        if (null === $data) {
            if ($this->queueManager->hasQueue()) {
                $content = $this->queueManager->renderQueue();
                $event->setResponse(new Response($content));

                return $event;
            }
        }
    }
} 