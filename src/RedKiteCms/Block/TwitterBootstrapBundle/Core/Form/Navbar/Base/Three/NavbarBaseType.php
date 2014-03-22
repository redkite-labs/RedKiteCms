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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Navbar\Base\Three;

use RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Base\BaseType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the form to edit a navbar dropbown block
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
abstract class NavbarBaseType extends BaseType
{
    /**
     * Builds the form
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('alignment', 'choice', $this->setChoices());

        parent::buildForm($builder, $options);
    }

    protected function setChoices()
    {
        return array(
            'label' => 'navbar_alignment',
            'choices' => array(
                "navbar-left" => "Left",
                "navbar-right" => "Right",
            ),
        );
    }
}
