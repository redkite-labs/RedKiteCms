<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\RoutingGenerator;

/**
 * Defines the interface that must be implemented to generate the routes required
 * to handle the website routing
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
interface RoutingGeneratorInterface
{
    /**
     * Generates the routing from the given PageTreeCollection
     *
     * @param  string $deployBundle     The name of the deploy bundle
     * @param  string $deployController The name of the deploy controller
     * @return self
     */
    public function generateRouting($deployBundle, $deployController);

    /**
     * Returns the generated routing
     *
     * @return string
     */
    public function getRouting();

    /**
     * Writes the generated routing
     *
     * @param  string $path
     * @return string
     */
    public function writeRouting($path);
}
