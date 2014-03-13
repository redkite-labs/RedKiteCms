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
 * RoutingGeneratorStage generated the routing for the production environment
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class RoutingGeneratorStage extends RoutingGenerator
{
    /**
     * {@inheritdoc}
     */
    public function writeRouting($path)
    {
        @file_put_contents(sprintf('%s/site_routing_stage.yml', $path), $this->getRouting());
    }

    /**
     * {@inheritdoc}
     */
    protected function defineRouteSchema($deployBundle, $deployController)
    {
        $schema = "# Route << %1\$s >> generated for language << %2\$s >> and page << %3\$s >>\n";
        $schema .= "_stage_%4\$s:\n";
        $schema .= "  pattern: /%1\$s\n";
        $schema .= "  defaults: { _controller: $deployBundle:$deployController:stage, _locale: %2\$s, page: %3\$s }";

        return $schema;
    }
}
