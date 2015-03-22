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

namespace Controller\ElFinder;

use RedKiteCms\Rendering\Controller\ElFinder\ElFinderController;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * This object implements the Silex controller to render the ElFinder media library
 * to manage generic files
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package Controller\ElFinder
 */
class ElFinderFilesController extends ElFinderController
{
    /**
     * Show ElFinder for files action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application                        $app
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function filesAction(Request $request, Application $app)
    {
        $options = array(
            "connector" => $app["red_kite_cms.elfinder_files_connector"],
        );

        return parent::show($options);
    }
}