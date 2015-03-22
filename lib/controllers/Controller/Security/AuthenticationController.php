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

namespace Controller\Security;

use RedKiteCms\Rendering\Controller\Security\AuthenticationController as BaseAuthenticationController;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * This object implements the Silex controller to login into the backend
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package Controller\Security
 */
class AuthenticationController extends BaseAuthenticationController
{
    /**
     * Login action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application                        $app
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request, Application $app)
    {
        $options = array(
            "is_ajax" => $request->isXmlHttpRequest(),
            "error" => $app['security.last_error']($request),
            "last_username" => $app['session']->get('_security.last_username'),
            "template_assets" => $app["red_kite_cms.template_assets"],
            "twig" => $app["twig"],
            "red_kite_cms_config" => $app["red_kite_cms.configuration_handler"],
            "assets" => array(
                'getExternalStylesheets' => array(
                    $app["red_kite_cms.configuration_handler"]->webDir(
                    ) . '/components/redkitecms/twitter-bootstrap/css/bootstrap.min.css',
                    $app["red_kite_cms.configuration_handler"]->webDir() . '/sb-admin-2/css/sb-admin-2.css',
                ),
            ),
        );

        return parent::login($options);
    }
}