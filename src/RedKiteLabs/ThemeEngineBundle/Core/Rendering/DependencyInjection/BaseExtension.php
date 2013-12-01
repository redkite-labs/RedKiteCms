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

/**
 * Implements the base class to load a theme configuration
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class BaseExtension extends Extension
{
    abstract function configureTheme();

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $themeConfiguration = $this->configureTheme();
        $this->loadConfigurationRecursive($container, $themeConfiguration);
    }

    /**
     * Loads the them configuration recursively
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array $configuration
     */
    protected function loadConfigurationRecursive(ContainerBuilder $container, array $configuration)
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
}