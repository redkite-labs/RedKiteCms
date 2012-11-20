<?php
/**
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
                ->scalarNode('orm')->defaultValue('Propel')->end()
                ->scalarNode('skin')->defaultValue('alphaLemon')->end()
                ->scalarNode('web_folder_dir')->defaultValue('web')->end()
                ->scalarNode('web_folder_dir_full_path')->defaultValue('%kernel.root_dir%/../%alpha_lemon_cms.web_folder%')->end()
                ->scalarNode('upload_assets_dir')->defaultValue('uploads/assets')->end()
                ->scalarNode('upload_assets_full_path')->defaultValue('%alpha_lemon_cms.web_folder_full_path%/uploads/assets')->end()
                ->scalarNode('upload_assets_absolute_path')->defaultValue('/%alpha_lemon_cms.web_folder%%/uploads/assets')->end()
                ->booleanNode('enable_yui_compressor')->defaultFalse()->end()
                ->arrayNode('deploy_bundle')
                    ->children()
                        ->scalarNode('resources_dir')->defaultValue('Resources')->end()
                        ->scalarNode('assets_base_dir')->defaultValue('%alpha_lemon_cms.deploy_bundle.resources_dir%/public')->end()
                        ->scalarNode('config_dir')->defaultValue('%alpha_lemon_cms.deploy_bundle.resources_dir%/config')->end()
                        ->scalarNode('views_dir')->defaultValue('%alpha_lemon_cms.deploy_bundle.resources_dir%/views')->end()
                        ->scalarNode('media_dir')->defaultValue('media')->end()
                        ->scalarNode('js_dir')->defaultValue('js')->end()
                        ->scalarNode('css_dir')->defaultValue('css')->end()
                        ->scalarNode('controller')->defaultValue('WebSite')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
