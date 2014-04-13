<?php
/*
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT LICENSE. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT LICENSE
 *
 */

namespace RedKiteLabs\RedKiteCms\InstallerBundle\Core\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * RedKiteCms Form Type.
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class RedKiteCmsParametersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bundle', 'text')
            ->add('driver', 'choice', array('choices' => array('mysql' => 'mysql', 'pgsql' => 'postgres', 'sqlite' => 'sqlite')))
            ->add('host', 'text')
            ->add('database', 'text')
            ->add('port', 'text', array('required' => false))
            ->add('user', 'text')
            ->add('password', 'repeated', array(
                'required'        => false,
                'type'            => 'password',
                'first_name'      => 'password',
                'second_name'     => 'password_again',
                'invalid_message' => 'The password fields must match.',
            ))
            ->add('website-url', 'text', array('label' => 'Website Url'))
        ;
    }

    public function getName()
    {
        return 'installer_parameters';
    }
}