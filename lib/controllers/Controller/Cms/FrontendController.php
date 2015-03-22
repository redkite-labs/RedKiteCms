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

use RedKiteCms\Rendering\Controller\Cms\FrontendController as BaseFrontendController;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * This object implements the Silex controller to render RedKite CMS frontend
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package Controller\Cms
 */
class FrontendController extends BaseFrontendController
{
    /**
     * Show frontend action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application                        $app
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request, Application $app)
    {
        $options = $this->options($request, $app);

        return parent::show($options);
    }

    /**
     * Sets the options required by parent class
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application                        $app
     * @return array
     */
    protected function options(Request $request, Application $app)
    {
        return array(
            "root_dir" => $app["red_kite_cms.root_dir"],
            "request" => $request,
            "twig" => $app["twig"],
            "red_kite_cms_config" => $app["red_kite_cms.configuration_handler"],
            "page" => $app["red_kite_cms.page"],
            "plugin_manager" => $app["red_kite_cms.plugin_manager"],
            "template_assets" => $app["red_kite_cms.template_assets"],
            "page_renderer" => $app["red_kite_cms.page_renderer_production"],
            "username" => null,
        );
    }
}