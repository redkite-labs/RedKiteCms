<?php
/*
 * This file is part of the TwitterBootstrapBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave intact this copyright
 * notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT LICENSE
 *
 */

namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Form\Navbar;

use Symfony\Component\Form\FormBuilderInterface;
use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Form\Base\BaseType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Defines the form to edit a navbar dropbown block
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class NavbarDropdownType extends BaseType
{
    /**
     * Builds the form
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('button_text', null, array('label' => 'navbar_button_text'));
    }

    /**
     * Sets the default options for this type
     *
     * @param OptionsResolverInterface $resolver The resolver for the options
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'TwitterBootstrapBundle',
            'csrf_protection' => false,
        ));
    }

    /**
     * Returns the name of this type
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'al_json_block';
    }
}
