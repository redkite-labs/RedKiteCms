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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Navbar\Form\Three;

use RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Navbar\Base\Three\AlNavbarBaseWithEmptyOptionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the form to edit a navbar form block
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlNavbarformType extends AlNavbarBaseWithEmptyOptionType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('method', 'choice', array('label' => 'navbar_form_method', 'choices' => array("post" => "POST", "get" => "GET")));
        $builder->add('action', null,  array('label' => 'navbar_form_action',));
        $builder->add('enctype', 'choice', array('label' => 'navbar_form_enctype', 'choices' => array("" => "", "application/x-www-form-urlencoded" => "application/x-www-form-urlencoded", "multipart/form-data" => "multipart/form-data", "text/plain" => "text/plain")));
        $builder->add('placeholder', null,  array('label' => 'navbar_form_placeholder',));
        $builder->add('button_text', null,  array('label' => 'navbar_button_text',));
        $builder->add('role', null,  array('label' => 'navbar_form_role',)); 
        
        parent::buildForm($builder, $options);
    }
}