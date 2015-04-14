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

namespace RedKiteCms\Action;

use Silex\Application;

/**
 *
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Listener\Request
 */
class FactoryAction
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function create($entity, $action)
    {
        $type = ucfirst($entity);
        $actionName = ucfirst($action);
        $class = sprintf('RedKiteCms\Action\%s\%s%sAction', $type, $actionName, $type);

        if (!class_exists($class)) {
            return null;
        }

        $reflectionClass = new \ReflectionClass($class);

        return $reflectionClass->newInstance($this->app);
    }
}