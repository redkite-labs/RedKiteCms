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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Navbar;

use RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Base\BaseType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the form to edit a navbar block
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class NavbarType extends BaseType
{
    /**
     * Builds the form
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('position', 'choice', array('label' => 'navbar_position', 'choices' => array("" => "normal", "navbar-fixed-top" => "fixed top", "navbar-fixed-bottom" => "fixed bottom", "navbar-static-top" => "static top")));
        $builder->add('inverted', 'choice', array('label' => 'navbar_inverted', 'choices' => array("" => "normal", "navbar-inverse" => "inverted")));

        parent::buildForm($builder, $options);
    }
}
