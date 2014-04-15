<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\Security;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\Base\BaseBlockType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Implements the form to manage the website users
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class UserType extends BaseBlockType
{
    /**
     * Builds the form
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden');
        $builder->add('username');
        $builder->add('password', 'password');
        $builder->add('email');

        $builder->add('Role', 'model', array(
            'class'     => 'RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Role',
            'property'  => 'Role',
            'label' => 'security_controller_label_roles',
        ));
    }

    /**
     * Sets the default options for this type
     *
     * @param OptionsResolverInterface $resolver The resolver for the options
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'data_class' => 'RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\User',
        ));
    }

    /**
     * Returns the name of this type
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'al_user';
    }
}
