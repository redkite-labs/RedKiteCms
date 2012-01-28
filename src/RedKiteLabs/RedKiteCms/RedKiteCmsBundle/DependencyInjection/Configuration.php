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
 * @author AlphaLemon <info@alphalemon.com>
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
                ->scalarNode('skin')->end()
                ->variableNode('page_blocks')->end()
                ->variableNode('javascripts')->end()
                ->variableNode('stylesheets')->end()
                ->scalarNode('panel_base_template')->end()
                ->arrayNode('deploy')
                    ->children()
                        ->scalarNode('xliff_skeleton')->end()
                        ->scalarNode('xml_skeleton')->end()
                    ->end()
                ->end()
                ->arrayNode('assets')
                    ->children()
                        ->scalarNode('skeletons_folder')->end()
                        ->scalarNode('output_folder')->end()
                    ->end()
                ->end()
            ->end()
        ;
        
        return $treeBuilder;
    }
}
