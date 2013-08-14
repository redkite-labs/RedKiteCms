<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Listener\Cms;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use RedKiteLabs\RedKiteCmsBundle\Core\ResourcesLocker\AlResourcesLocker;
use RedKiteLabs\RedKiteCmsBundle\Core\ResourcesLocker\Exception\ResourceNotFreeException;

/**
 * Checks that the requested resource is not used by any other user. When it is not free,
 * it stops the request propagation and returns a response warning the user that the
 * resouce is locked, when it is available, it locks the resource for the current user.
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class ResourceFreeListener
{
    private $securityContext;
    private $resourcesLocker;

    /**
     * Contructor
     *
     * @param \Symfony\Component\Security\Core\SecurityContext                       $securityContext
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\ResourcesLocker\AlResourcesLocker $resourcesLocker
     *
     * @api
     */
    public function __construct(SecurityContext $securityContext, AlResourcesLocker $resourcesLocker)
    {
        $this->securityContext = $securityContext;
        $this->resourcesLocker = $resourcesLocker;
    }

    /**
     * Listen to onKernelRequest event to lock a resource
     *
     * @param GetResponseEvent $event
     *
     * @api
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        // checks if the backend is secured
        $token = $this->securityContext->getToken();
        if (null !== $token) {

            // Check if the user has already been logged in
            $user = $token->getUser();
            if (null !== $user) {
                $errorMessage = '';
                $userId = $user->getId();

                // Frees the expired locked resources and the resource previously locked
                // by the user
                try {
                    $this->resourcesLocker->unlockExpiredResources();
                    $this->resourcesLocker->unlockUserResource($userId);
                } catch (\Exception $ex) {
                    $errorMessage = $ex->getMessage();
                }

                if ($errorMessage == '') {
                    $request = $event->getRequest();

                    // LOcks the resource
                    $locked = $request->get('locked');
                    if (null !== $locked) {
                        try {
                            // Process composite locking rules
                            $rules = explode(',', $locked);
                            if (isset($rules[1])) {
                                $locked = $rules[0];
                                $param = $request->get($rules[1]);
                            } else {
                                $param = $request->get($locked);
                            }

                            $key = ('locked' !== $locked) ? $locked . "=" . $param : $request->getUri() . '/locked';
                            $this->resourcesLocker->lockResource($userId, md5($key));
                        } catch (\PropelException $ex) {
                            $errorMessage = 'The resource is not lockable because it was free but someone has locked it before you. This happens when two users tries to get a resource at the same time';
                        } catch (ResourceNotFreeException $ex) {
                            $errorMessage = $ex->getMessage();
                        } catch (\Exception $ex) {
                            $errorMessage = $ex->getMessage();
                        }
                    }
                }

                // The resource is not free, stops the request
                if ($errorMessage != '') {
                    $response = new Response();
                    $response->setStatusCode('404');
                    $response->setContent($errorMessage);

                    $event->setResponse($response);
                    $event->stopPropagation();
                }
            }
        }
    }
}
