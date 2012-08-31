<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 */

namespace AlphaLemon\ThemeEngineBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AlphaLemonThemeEngineExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('twig.xml');
        $loader->load('services.xml');
        
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);
        
        if (isset($config['slot_contents_dir'])) {
            $container->setParameter('althemes.slot_contents_dir', $config['slot_contents_dir']);
        }
        
        if (isset($config['base_template'])) {
            $container->setParameter('althemes.base_template', $config['base_template']);
        }
        
        if (isset($config['base_theme_manager_template'])) {
            $container->setParameter('althemes.base_theme_manager_template', $config['base_theme_manager_template']);
        }
        
        if (isset($config['panel_sections_template'])) {
            $container->setParameter('althemes.panel_sections_template', $config['panel_sections_template']);
        }
        
        if (isset($config['theme_skeleton_template'])) {
            $container->setParameter('althemes.theme_skeleton_template', $config['theme_skeleton_template']);
        }
        
        if (isset($config['render_slot_class'])) {
            $container->setParameter('twig.extension.render_slot.class', $config['render_slot_class']);
        }
        
        $container->setParameter('alpha_lemon_theme_engine.deploy_bundle', $config['deploy_bundle']);       
        
        $container->setParameter('althemes.app_themes_dir', $container->getParameter('kernel.root_dir') . '/../src/AlphaLemon/Theme');
        
        
    }
    
    public function getAlias()
    {
        return 'alpha_lemon_theme_engine';
    }
}
