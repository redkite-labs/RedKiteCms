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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Form\Exception\InvalidConfigurationException;

/**
 * Loads the CMS parameters into the DIC
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlphaLemonCmsExtension extends Extension
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

        if (isset($config['orm'])) {
            $container->setParameter('alpha_lemon_cms.orm', $config['orm']);
        }

        if (isset($config['skin'])) {
            $container->setParameter('alpha_lemon_cms.skin', $config['skin']);
        }

        if (isset($config['web_folder_dir'])) {
            $container->setParameter('alpha_lemon_cms.web_folder', $config['web_folder_dir']);
        }

        if (isset($config['upload_assets_dir'])) {
            $container->setParameter('alpha_lemon_cms.upload_assets_dir', $config['upload_assets_dir']);
        }

        if (isset($config['deploy_bundle'])) {
            if (isset($config['deploy_bundle']['resources_dir'])) {
                $container->setParameter('alpha_lemon_cms.deploy_bundle.resources_dir', $config['deploy_bundle']['resources_dir']);
            }

            if (isset($config['deploy_bundle']['assets_base_dir'])) {
                $container->setParameter('alpha_lemon_cms.deploy_bundle.assets_base_dir', $config['deploy_bundle']['assets_base_dir']);
            }

            if (isset($config['deploy_bundle']['config_dir'])) {
                $container->setParameter('alpha_lemon_cms.deploy_bundle.config_dir', $config['deploy_bundle']['config_dir']);
            }

            if (isset($config['deploy_bundle']['views_dir'])) {
                $container->setParameter('alpha_lemon_cms.deploy_bundle.views_dir', $config['deploy_bundle']['views_dir']);
            }

            if (isset($config['deploy_bundle']['media_dir'])) {
                $container->setParameter('alpha_lemon_cms.deploy_bundle.media_dir', $config['deploy_bundle']['media_dir']);
            }

            if (isset($config['deploy_bundle']['js_dir'])) {
                $container->setParameter('alpha_lemon_cms.deploy_bundle.js_dir', $config['deploy_bundle']['js_dir']);
            }

            if (isset($config['deploy_bundle']['css_dir'])) {
                $container->setParameter('alpha_lemon_cms.deploy_bundle.css_dir', $config['deploy_bundle']['css_dir']);
            }
        }
    }

    public function getAlias()
    {
        return 'alpha_lemon_cms';
    }
}
