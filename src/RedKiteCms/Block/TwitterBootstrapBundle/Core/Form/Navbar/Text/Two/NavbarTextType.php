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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Navbar\Text\Two;

use RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Navbar\Base\Two\AlNavbarBaseType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the form to edit a navbar dropbown block
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlNavbarTextType extends AlNavbarBaseType
{
    /**
     * Builds the form
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', null, array('label' => 'navbar_text_attribute_text'));

        parent::buildForm($builder, $options);
    }
}
