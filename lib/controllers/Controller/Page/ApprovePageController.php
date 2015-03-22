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

use RedKiteCms\Rendering\Controller\Page\ApprovePageController as BaseApprovePageController;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * This object implements the Silex controller to approve a page contribution
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package Controller\Seo
 */
class ApprovePageController extends BaseApprovePageController
{
    /**
     * Approve page contribution action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application                        $app
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function approveAction(Request $request, Application $app)
    {
        $options = array(
            "request" => $request,
            "page_manager" => $app["red_kite_cms.page_manager"],
            "username" => $this->fetchUsername($app["security"], $app["red_kite_cms.configuration_handler"]),
        );

        return parent::approve($options);
    }
}