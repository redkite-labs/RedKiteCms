<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\Rendering\Controller\Theme;

use RedKiteCms\Content\Theme\ThemeDeployer;
use RedKiteCms\Rendering\Controller\BaseController;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SaveThemeController is the object deputed to save a theme
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Theme
 */
abstract class SaveThemeController extends BaseController
{
    /**
     * Implements the action to save the theme
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function save(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);

        $pluginManager = $options["plugin_manager"];
        $options["theme_deployer"]
            ->boot($pluginManager->getActiveTheme())
            ->deploy()
        ;

        return $this->buildJSonResponse(array());
    }

    /**
     * Configures the options for the resolver
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            array(
                'configuration_handler',
                'plugin_manager',
                'theme_deployer',
                'page',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'configuration_handler' => '\RedKiteCms\Configuration\ConfigurationHandler',
                'plugin_manager' => '\RedKiteCms\Plugin\PluginManager',
                'theme_deployer' => '\RedKiteCms\Content\Theme\ThemeDeployer',
                'page' => '\RedKiteCms\FilesystemEntity\Page',
            )
        );
    }
}