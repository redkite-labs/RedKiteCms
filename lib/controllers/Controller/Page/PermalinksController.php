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

namespace Controller\Page;

use RedKiteCms\Rendering\Controller\Page\PermalinksController as BasePermalinksController;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * This object implements the Silex controller to list the website permalinks
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package Controller\Seo
 */
class PermalinksController extends BasePermalinksController
{
    /**
     * List permalinks action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application                        $app
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listPermalinksAction(Request $request, Application $app)
    {
        $options = array(
            "request" => $request,
            "pages_collection_parser" => $app["red_kite_cms.pages_collection_parser"],
            "username" => $this->fetchUsername($app["security"], $app["red_kite_cms.configuration_handler"]),
        );

        return parent::listPermalinks($options);
    }
}