<?php
/**
 * This file is part of the BusinessWebsiteThemeBundle theme and it is distributed
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
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\ThemeEngineBundle\Core\Rendering\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

abstract class BaseExtension extends Extension
{
    abstract function configureTheme();

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $themeConfiguration = $this->configureTheme();
        $this->loadConfigurationRecursive($container, $themeConfiguration);
        $this->processTemplates($config, $container);
    }

    protected function loadConfigurationRecursive($container, $configuration)
    {
        foreach ($configuration as $values) {
            $loader = new XmlFileLoader($container, new FileLocator($values['path']));
            foreach ($values['configFiles'] as $configFile) {
                $loader->load($configFile);
            }
            if (array_key_exists('configuration', $values)) {
                $this->loadConfigurationRecursive($container, $values['configuration']);
            }
        }
    }

    protected function processTemplates($config, ContainerBuilder $container)
    {
        $extensionAlias = preg_replace('/_theme$/', '', $this->getAlias());
        if (isset($config['templates'])) {
            foreach ($config['templates'] as $templateName => $sections) {
                foreach ($sections as $sectionName => $assets) {
                    foreach (array_keys($assets) as $type) {
                        $sectionName = ($sectionName != 'all') ? '.' . $sectionName : '';
                        $parameter = sprintf('%s.%s.%s%s', $extensionAlias, $templateName, $type, $sectionName);
                        $values = array_merge($container->getParameter($parameter), $assets[$type]);
                        $container->setParameter($parameter, $values);
                    }
                }
            }
        }
    }
}