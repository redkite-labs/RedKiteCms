<?php
/*
 * This file is part of the AlphaLemonCMS InstallerBundle and it is distributed
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

namespace AlphaLemon\CmsInstallerBundle\Core\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * AlphaLemonCms Form Type.
 *
 * @author alphalemon <webmaster@alphalemoncms.com>
 */
class AlphaLemonCmsParametersType extends AbstractType
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
        return 'alphalemon_cms_parameters';
    }
}
