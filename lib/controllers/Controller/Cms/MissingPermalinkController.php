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

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MissingPermalinkController
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package Controller\Cms
 */
class MissingPermalinkController
{
    public function redirectAction(Request $request, Application $app)
    {
        $routeName = $request->get('route_name');
        $url = $app['url_generator']->generate($routeName);

        return $app->redirect($url);
    }
}