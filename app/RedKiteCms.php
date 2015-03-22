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

use \RedKiteCms\RedKiteCms as RedKiteCmsBase;

class RedKiteCms extends RedKiteCmsBase
{
    protected function configure()
    {
        // Return an array of options to change RedKite CMS internal configuration
        // or an empty array to use the default configuration
        return array();
    }
    
    protected function register(Silex\Application $app)
    {
        // Add custom providers, services, listeners here
    }
}