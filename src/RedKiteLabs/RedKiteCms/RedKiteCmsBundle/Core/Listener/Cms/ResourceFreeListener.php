<?php
/*
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Cms;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\SecurityContext;
use AlphaLemon\AlphaLemonCmsBundle\Core\ResourcesLocker\AlResourcesLocker;
use AlphaLemon\AlphaLemonCmsBundle\Core\ResourcesLocker\Exception\ResourceNotFreeException;

/**
 * 
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class ResourceFreeListener
{
    private $securityContext;
    private $resourcesLocker;

    /**
     * Contructor
     *
     */
    public function __construct(SecurityContext $securityContext, AlResourcesLocker $resourcesLocker)
    {
        $this->securityContext = $securityContext;
        $this->resourcesLocker = $resourcesLocker;
    }

    /**
     * Listen to onKernelRequest 
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $token = $this->securityContext->getToken();
        if (null !== $token) {
            $user = $token->getUser();
            if (null !== $user) {
                $userId = $user->getId();
                $this->resourcesLocker->freeExpiredResources();
                $this->resourcesLocker->unlockUserResource($userId);

                $request = $event->getRequest();
                $locked = $request->get('locked');
                if (null !== $locked) {
                    $errorMessage = '';
                    try {
                        $key = (null !== $request->get($locked)) ? $locked . "=" . $request->get($locked) : $request->getUri() . '/locked';
                        $this->resourcesLocker->lockResource($userId, md5($key));
                    } catch(\PropelException $ex) {
                        $errorMessage = 'The resource is not lockable because it was free but someone has locked it before you. This happens when two users tries to get a resource at the same time';
                    } catch(ResourceNotFreeException $ex) {
                        $errorMessage = $ex->getMessage();
                    } catch(\Exception $ex) {
                        $errorMessage = $ex->getMessage();
                    }
                    
                    if ($errorMessage != "") {
                        $response = new \Symfony\Component\HttpFoundation\Response();
                        $response->setStatusCode('404');
                        $response->setContent($errorMessage);
                        
                        $event->setResponse($response);
                        $event->stopPropagation();
                    }
                }
            }
        }
    }
}
