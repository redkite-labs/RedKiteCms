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

use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\Rendering\Controller\BaseController;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ShowThemeController is the object deputed to show the themes dashboard interface
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Theme
 */
abstract class ShowThemeController extends BaseController
{
    /**
     * Implements the action to show the themes dashboard interface
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
        $templateAssets = $this->options["template_assets"];
        $templateAssets->boot('dashboard');

        $themes = array();
        $themePlugins = $this->options["plugin_manager"]->getThemePlugins();
        foreach ($themePlugins as $themePlugin) {
            if ($themePlugin->getName() == $this->options["configuration_handler"]->theme()) {
                $themes["active_theme"] = $themePlugin;

                continue;
            }

            $themes["available_themes"][] = $themePlugin;
        }

        $template = 'RedKiteCms/Resources/views/Dashboard/themes.html.twig';

        return $options["twig"]->render(
            $template,
            array(
                "template_assets_manager" => $templateAssets,
                "themes" => $themes,
                "isTheme" => $this->options["configuration_handler"]->isTheme(),
                "version" => ConfigurationHandler::getVersion(),
            )
        );
    }

    /**
     * Configures the options for the resolver
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            array(
                'twig',
                'template_assets',
                'configuration_handler',
                'plugin_manager',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'twig' => '\Twig_Environment',
                'template_assets' => '\RedKiteCms\Rendering\TemplateAssetsManager\TemplateAssetsManager',
                'configuration_handler' => '\RedKiteCms\Configuration\ConfigurationHandler',
                'plugin_manager' => '\RedKiteCms\Plugin\PluginManager',
            )
        );
    }
}