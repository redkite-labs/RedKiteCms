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
class RedKiteLabsThemeEngineExtension extends Extension
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

        if (isset($config['base_template'])) {
            $container->setParameter('red_kite_labs_theme_engine.base_template', $config['base_template']);
        }
        
        if (isset($config['render_slot_class'])) {
            $container->setParameter('twig.extension.render_slot.class', $config['render_slot_class']);
        }
        
        if (isset($config['templates_folder'])) {
            $container->setParameter('red_kite_labs_theme_engine.deploy.templates_folder', $config['templates_folder']);
        }
        
        if (isset($config['bootstrap'])) {
            $bootstrapThemes = array();
            foreach($config['bootstrap'] as $themes) {
                foreach($themes as $theme) {
                    $bootstrapThemes[$theme['theme']] = $theme['version'];
                }
            }
            
            $container->setParameter('red_kite_cms.bootstrap_themes', $bootstrapThemes);
        }
        
        $container->setParameter('red_kite_labs_theme_engine.deploy_bundle', $config['deploy_bundle']);
    }

    public function getAlias()
    {
        return 'red_kite_labs_theme_engine';
    }
}
