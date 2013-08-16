<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
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
 */

namespace RedKiteLabs\ThemeEngineBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('red_kite_labs_theme_engine');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                ->scalarNode('deploy_bundle')->isRequired()->end()
                ->scalarNode('base_template')->end()
                ->arrayNode('themes_panel')
                    ->children()
                        ->scalarNode('base_theme')->end()
                        ->scalarNode('theme_section')->end()
                        ->scalarNode('theme_skeleton')->end()
                    ->end()
                ->end()
                ->scalarNode('info_valid_entries')->end()
                ->scalarNode('render_slot_class')->end()
                ->scalarNode('active_theme_file')->end()
                ->scalarNode('templates_folder')->end()
                ->scalarNode('stage_templates_folder')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
