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

use RedKiteCms\Rendering\Controller\BaseController;
use RedKiteCms\Tools\FilesystemTools;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SaveUserController is the object deputed to update the user information
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Security
 */
abstract class SaveUserController extends BaseController
{
    /**
     * Implements the action to update the user information
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function save(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);

        $request = $this->options["request"];
        $password = $request->get('password');
        $user = $this->fetchUser($this->options['security'], $this->options['configuration_handler']);

        $factory = $this->options['encoder_factory'];
        $encoder = $factory->getEncoder($user);
        $salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $password = $encoder->encodePassword($password, $salt);

        $userName = "admin";
        $usersFile = $this->options['configuration_handler']->usersDir() . '/users.json';
        $users = json_decode(FilesystemTools::readFile($usersFile), true);
        $user = $users[$userName];
        $user["password"] = $password;
        $user["salt"] = $salt;
        $users[$userName] = $user;
        FilesystemTools::writeFile($usersFile, json_encode($users));
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
                'template_assets',
                'twig',
                'request',
                'encoder_factory',
                'security',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'template_assets' => '\RedKiteCms\Rendering\TemplateAssetsManager\TemplateAssetsManager',
                'configuration_handler' => '\RedKiteCms\Configuration\ConfigurationHandler',
                'twig' => '\Twig_Environment',
                'request' => '\Symfony\Component\HttpFoundation\Request',
                'encoder_factory' => 'Symfony\Component\Security\Core\Encoder\EncoderFactory',
                'security' => 'Symfony\Component\Security\Core\SecurityContext',
            )
        );
    }
}