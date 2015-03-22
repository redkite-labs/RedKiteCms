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

use RedKiteCms\Rendering\Controller\Cms\BackendController as BaseBackendController;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * This object implements the Silex controller to render RedKite CMS backend
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package Controller\Cms
 */
class BackendController extends BaseBackendController
{
    /**
     * Show backend action
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
        $options = parent::options($request, $app);
        $configurationHandler = $app["red_kite_cms.configuration_handler"];

        $options["skin"] = $configurationHandler->skin();
        $options["block_factory"] = $app["red_kite_cms.block_factory"];
        $options["page_renderer"] = $app["red_kite_cms.page_renderer_backend"];
        $options["languages"] = $configurationHandler->languages();
        $options["username"] = $this->fetchUsername($app["security"], $configurationHandler);
        $options["form_factory"] = $app["form.factory"];
        $options["serializer"] = $app["jms.serializer"];
        $options["toolbar_manager"] = $app["red_kite_cms.toolbar_manager"];

        return $options;
    }
}