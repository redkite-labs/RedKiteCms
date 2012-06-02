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

        if (isset($config['skin'])) {
            $container->setParameter('alcms.skin', $config['skin']);
        }

        if (isset($config['web_folder_name'])) {
            $container->setParameter('alcms.web_folder_name', $config['web_folder_name']);
        }

        if (isset($config['page_blocks'])) {
            $this->mergeArrayParameter($container, $config, 'al_cms.page_blocks', 'page_blocks');
        }

        if (isset($config['stylesheets'])) {
            $this->mergeArrayParameter($container, $config, 'alcms.stylesheets', 'stylesheets');
        }

        if (isset($config['javascripts'])) {
            $this->mergeArrayParameter($container, $config, 'alcms.javascripts', 'javascripts');
        }

        if (isset($config['deploy'])) {
            if (isset($config['deploy']['xliff_skeleton'])) {
                $container->setParameter('alcms.deploy.xliff_skeleton', $config['deploy']['xliff_skeleton']);
            }

            if (isset($config['deploy']['xml_skeleton'])) {
                $container->setParameter('alcms.deploy.xml_skeleton', $config['deploy']['xml_skeleton']);
            }
        }

        if (isset($config['assets'])) {
            if (isset($config['assets']['skeletons_folder'])) {
                $container->setParameter('alcms.assets.skeletons_folder', $config['assets']['skeletons_folder']);
            }

            if (isset($config['assets']['output_folder'])) {
                $container->setParameter('alcms.assets.output_folder', $config['assets']['output_folder']);
            }
        }
    }

    public function getAlias()
    {
        return 'alpha_lemon_cms';
    }

    private function mergeArrayParameter(ContainerBuilder $container, $config, $parameterName, $configName)
    {
        if(!is_array($config[$configName]))
        {
            throw new \Symfony\Component\Form\Exception\InvalidConfigurationException(sprintf('%s param must be an array', $configName));
        }

        $param = $container->getParameter($parameterName);
        $param = array_merge($param, $config[$configName]);
        $container->setParameter($parameterName, $param);
    }
}
