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

namespace RedKiteCms\Bridge\Routing;


use MJanssen\Provider\RoutingServiceProvider;
use Silex\Application;

/**
 * Extends the RoutingServiceProvider object to bind a route to a controller
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Bridge\Routing
 */
class RoutingProvider extends RoutingServiceProvider
{
    protected function addRouteByMethod(Application $app, $route)
    {
        parent::addRouteByMethod($app, $route);

        $controller = $this->getController($app, $route);
        if (isset($route['bind'])) {
            $controller->bind($route['bind']);
        }
    }
}