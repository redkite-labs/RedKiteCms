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

use RedKiteCms\Rendering\Controller\Security\ShowUserController as BaseShowUserController;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * This object implements the Silex controller to show the user control panel
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package Controller\Security
 */
class ShowUserController extends BaseShowUserController
{
    /**
     * Shows the user panel action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application                        $app
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request, Application $app)
    {
        $options = array(
            'twig' => $app["twig"],
            'template_assets' => $app["red_kite_cms.template_assets"],
            'configuration_handler' => $app["red_kite_cms.configuration_handler"],
            'form_factory' => $app["form.factory"],
        );

        return parent::show($options);
    }
}