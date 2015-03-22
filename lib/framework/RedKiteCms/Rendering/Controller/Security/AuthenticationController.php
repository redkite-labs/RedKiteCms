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

namespace RedKiteCms\Rendering\Controller\Security;

use RedKiteCms\Rendering\Controller\Cms\BackendController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthenticationController is the object deputed to sign in to the CMS backend
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Security
 */
abstract class AuthenticationController extends BackendController
{
    /**
     * Implements the action to sign in to the CMS backend
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(array $options)
    {
        $response = null;
        $template = 'RedKiteCms/Resources/views/Security/Login/login-form.html.twig';
        if ($options["is_ajax"]) {
            $response = new Response();
            $response->setStatusCode('403');
        }

        $params['target'] = '/backend/' . $options["red_kite_cms_config"]->homepagePermalink();
        $params['error'] = $options["error"];
        $params['last_username'] = $options["last_username"];
        $params['template_assets_manager'] = $options["template_assets"];
        $params['template_assets_manager']->add($options["assets"]);

        return $options["twig"]->render($template, $params, $response);
    }
}