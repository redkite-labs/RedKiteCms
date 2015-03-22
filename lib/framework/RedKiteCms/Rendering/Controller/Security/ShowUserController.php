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
namespace RedKiteCms\Rendering\Controller\Security;

use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\Core\RedKiteCms\Core\Form\Security\UserType;
use RedKiteCms\Rendering\Controller\BaseController;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ShowUserController is the object deputed to show the user dashboard interface
 *
 * The only user information at the moment is the password and this feature will be expanded in te future.
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Security
 */
abstract class ShowUserController extends BaseController
{

    /**
     * Implements the action to show the user dashboard interface
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);


        $userName = "admin";

        $templateAssets = $this->options["template_assets"];
        $templateAssets->boot('dashboard');
        $template = 'RedKiteCms/Resources/views/Dashboard/user.html.twig';

        return $options["twig"]->render(
            $template,
            array(
                "template_assets_manager" => $templateAssets,
                "user" => $userName,
                "version" => ConfigurationHandler::getVersion(),
            )
        );
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            array(
                'configuration_handler',
                'template_assets',
                'twig',
                'form_factory',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'template_assets' => '\RedKiteCms\Rendering\TemplateAssetsManager\TemplateAssetsManager',
                'configuration_handler' => '\RedKiteCms\Configuration\ConfigurationHandler',
                'twig' => '\Twig_Environment',
                'form_factory' => '\Symfony\Component\Form\FormFactory',
            )
        );
    }
}