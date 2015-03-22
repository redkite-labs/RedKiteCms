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

use RedKiteCms\Rendering\Controller\Security\SaveUserController as BaseSaveUserController;
use Silex\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * This object implements the Silex controller to save a user
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package Controller\Security
 */
class SaveUserController extends BaseSaveUserController
{
    public function saveAction(Request $request, Application $app)
    {
        $options = array(
            'twig' => $app["twig"],
            'template_assets' => $app["red_kite_cms.template_assets"],
            'configuration_handler' => $app["red_kite_cms.configuration_handler"],
            'request' => $request,
            'encoder_factory' => $app["security.encoder_factory"],
            'security' => $app["security"],
        );

        parent::save($options);
        $app['session']->getFlashBag()->add('user_messages', 'The password for user admin has been changed');

        return new RedirectResponse('/backend/users/show');
    }
}