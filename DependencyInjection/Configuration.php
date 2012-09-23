<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configures the CMS parameters
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('alpha_lemon_cms');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('orm')->end()
                ->scalarNode('skin')->end()
                ->scalarNode('web_folder_dir')->end()
                ->scalarNode('upload_assets_dir')->end()
                ->booleanNode('enable_yui_compressor')->defaultFalse()->end()
                ->arrayNode('deploy_bundle')
                    ->children()
                        ->scalarNode('resources_dir')->end()
                        ->scalarNode('assets_base_dir')->end()
                        ->scalarNode('config_dir')->end()
                        ->scalarNode('views_dir')->end()
                        ->scalarNode('media_dir')->end()
                        ->scalarNode('js_dir')->end()
                        ->scalarNode('css_dir')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
