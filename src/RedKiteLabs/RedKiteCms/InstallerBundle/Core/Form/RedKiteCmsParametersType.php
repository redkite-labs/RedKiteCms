<?php
/*
 * This file is part of the RedKiteCmsCMS InstallerBundle and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\InstallerBundle\Core\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * RedKiteCms Form Type.
 *
 * @author alphalemon <webmaster@alphalemoncms.com>
 */
class RedKiteCmsParametersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company', 'text')
            ->add('bundle', 'text')
            ->add('driver', 'choice', array('choices' => array('mysql' => 'mysql', 'pgsql' => 'postgres', 'other' => 'other')))
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
            ->add('dsn', 'text', array('required' => false))
        ;
    }

    public function getName()
    {
        return 'red_kite_cms_parameters';
    }
}
