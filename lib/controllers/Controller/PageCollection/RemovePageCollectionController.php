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

namespace Controller\PageCollection;

use RedKiteCms\Rendering\Controller\PageCollection\RemovePageCollectionController as BaseRemovePageCollectionController;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * This object implements the Silex controller to remove a page collection from the website
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package Controller\Page
 */
class RemovePageCollectionController extends BaseRemovePageCollectionController
{
    /**
     * Remove page collection action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application                        $app
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeAction(Request $request, Application $app)
    {
        $options = array(
            "request" => $request,
            "page_collection_manager" => $app["red_kite_cms.page_collection_manager"],
            "username" => $this->fetchUsername($app["security"], $app["red_kite_cms.configuration_handler"]),
        );

        return parent::remove($options);
    }
}