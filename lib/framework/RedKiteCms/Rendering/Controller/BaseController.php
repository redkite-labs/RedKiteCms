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

namespace RedKiteCms\Rendering\Controller;

use RedKiteCms\Bridge\Security\User;
use RedKiteCms\Configuration\ConfigurationHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Class BaseController defines a base RedKite CMS controller
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller
 */
abstract class BaseController
{
    /**
     * Returns the current name for the signed in user
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $security
     * @param \RedKiteCms\Configuration\ConfigurationHandler $configurationHandler
     *
     * @return string
     */
    public function fetchUserName(SecurityContextInterface $security, ConfigurationHandler $configurationHandler)
    {
        $user = $this->fetchUser($security, $configurationHandler);
        if (null === $user) {
            return $user;
        }

        return $user->getUsername();
    }

    /**
     * Returns the current signed in user
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $security
     * @param \RedKiteCms\Configuration\ConfigurationHandler $configurationHandler
     *
     * @return \RedKiteCms\Bridge\Security\User
     */
    public function fetchUser(SecurityContextInterface $security, ConfigurationHandler $configurationHandler)
    {
        $user = null;
        if ($configurationHandler->isTheme()) {
            return $user;
        }

        $token = $security->getToken();
        if (null !== $token) {
            $user = $token->getUser();
        }

        return $user;
    }

    /**
     * Configures the options for the resolver
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    abstract protected function configureOptions(OptionsResolver $resolver);

    /**
     * Builda a json response
     * @param mixed $value
     * @param int $status
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildJSonResponse($value, $status = 200)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }
        $response = new Response($value, $status);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Returns a response with the given message
     * @param string $message
     * @param int $statusCode
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderMessage($message, $statusCode = 404)
    {
        $response = new Response();
        $response->setStatusCode($statusCode);
        $response->setContent($message);

        return $response;
    }
}