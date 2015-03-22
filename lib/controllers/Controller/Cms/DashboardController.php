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

namespace Controller\Cms;

use RedKiteCms\Rendering\Controller\Cms\DashboardController as BaseDashboardController;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * This object implements the Silex controller to render RedKite CMS dashboard
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package Controller\Cms
 */
class DashboardController extends BaseDashboardController
{
    /**
     * Show dashboard action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application                        $app
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request, Application $app)
    {
        $options = array(
            'template_assets' => $app["red_kite_cms.template_assets"],
            'twig' => $app["twig"],
        );

        return parent::show($options);
    }
}