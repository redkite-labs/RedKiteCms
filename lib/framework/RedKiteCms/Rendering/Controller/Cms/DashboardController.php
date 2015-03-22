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

namespace RedKiteCms\Rendering\Controller\Cms;

use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\Rendering\Controller\Cms\FrontendController as BaseFrontendController;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DashboardController is the object deputed to implement the action to show the CMS dashboard
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Cms
 */
abstract class DashboardController extends BaseFrontendController
{
    /**
     * Implements the action to render the CMS dashboard
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
        $this->options["template_assets"]->boot('dashboard');

        $template = 'RedKiteCms/Resources/views/Dashboard/home.html.twig';

        return $options["twig"]->render(
            $template,
            array(
                "template_assets_manager" => $this->options["template_assets"],
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
                'template_assets',
                'twig',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'template_assets' => '\RedKiteCms\Rendering\TemplateAssetsManager\TemplateAssetsManager',
                'twig' => '\Twig_Environment',
            )
        );
    }
}