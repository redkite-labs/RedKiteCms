<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Form\Exception\InvalidConfigurationException;

/**
 * Loads the CMS parameters into the DIC
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class RedKiteCmsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services/parameters.xml');
        $loader->load('services/services.xml');
        $loader->load('services/listeners.xml');
        $loader->load('services/twig.xml');

        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $container->setParameter('red_kite_cms.orm', $config['orm']);
        $container->setParameter('red_kite_cms.skin', $config['skin']);
        $container->setParameter('red_kite_cms.web_folder', $config['web_folder_dir']);
        $container->setParameter('red_kite_cms.web_folder_full_path', $config['web_folder_dir_full_path']);
        $container->setParameter('red_kite_cms.upload_assets_dir', $config['upload_assets_dir']);
        $container->setParameter('red_kite_cms.love', $config['love']);
        $container->setParameter('red_kite_cms.enable_yui_compressor', $config['enable_yui_compressor']);        
        $container->setParameter('red_kite_cms.theme_structure_file', $config['theme_structure_file']); 
        $container->setParameter('red_kite_cms.website_url', $config['website_url']);
        $container->setParameter('red_kite_cms.bootstrap_version', $config['bootstrap_version']);
        
        if (isset($config['active_theme_file'])) {
            $container->setParameter('red_kite_cms.active_theme_file', $config['active_theme_file']);
        }
        
        if (isset($config['deploy_bundle'])) {
            $container->setParameter('red_kite_cms.deploy_bundle.resources_dir', $config['deploy_bundle']['resources_dir']);
            $container->setParameter('red_kite_cms.deploy_bundle.assets_base_dir', $config['deploy_bundle']['assets_base_dir']);
            $container->setParameter('red_kite_cms.deploy_bundle.config_dir', $config['deploy_bundle']['config_dir']);
            $container->setParameter('red_kite_cms.deploy_bundle.views_dir', $config['deploy_bundle']['views_dir']);
            $container->setParameter('red_kite_cms.deploy_bundle.media_dir', $config['deploy_bundle']['media_dir']);
            $container->setParameter('red_kite_cms.deploy_bundle.js_dir', $config['deploy_bundle']['js_dir']);
            $container->setParameter('red_kite_cms.deploy_bundle.css_dir', $config['deploy_bundle']['css_dir']);
            $container->setParameter('red_kite_cms.deploy_bundle.controller', $config['deploy_bundle']['controller']);
        }
    }

    public function getAlias()
    {
        return 'red_kite_cms';
    }
}
