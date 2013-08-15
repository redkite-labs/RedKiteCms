<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Form\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Implements the form to manage the website users
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlUserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden');
        $builder->add('username');
        $builder->add('password');
        $builder->add('email');

        $builder->add('AlRole', 'model', array(
            'class'     => 'RedKiteLabs\RedKiteCmsBundle\Model\AlRole',
            'property'  => 'Role',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'RedKiteLabs\RedKiteCmsBundle\Model\AlUser',
            'csrf_protection' => false,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'al_user';
    }
}
