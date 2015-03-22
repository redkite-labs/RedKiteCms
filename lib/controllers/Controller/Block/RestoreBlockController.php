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

namespace Controller\Block;

use RedKiteCms\Rendering\Controller\Block\RestoreBlockController as BaseBlockController;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * This object implements the Silex controller to restore a removed block
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package Controller\Block
 */
class RestoreBlockController extends BaseBlockController
{
    /**
     * Restore block action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application                        $app
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function restoreAction(Request $request, Application $app)
    {
        $options = array(
            "request" => $request,
            "red_kite_cms_config" => $app["red_kite_cms.configuration_handler"],
            "block_factory" => $app["red_kite_cms.block_factory"],
            "serializer" => $app["jms.serializer"],
            "username" => $this->fetchUsername($app["security"], $app["red_kite_cms.configuration_handler"]),
        );

        return parent::restore($options);
    }
}