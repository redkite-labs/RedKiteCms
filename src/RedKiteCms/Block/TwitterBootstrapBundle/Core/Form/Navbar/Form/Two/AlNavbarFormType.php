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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Navbar\Form\Two;

use RedKiteLabs\RedKiteCmsBundle\Core\Form\JsonBlock\JsonBlockType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the form to edit a navbar form block
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlNavbarformType extends JsonBlockType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('method', 'choice', array('choices' => array("post" => "POST", "get" => "GET")));
        $builder->add('action');
        $builder->add('enctype', 'choice', array('choices' => array("" => "", "application/x-www-form-urlencoded" => "application/x-www-form-urlencoded", "multipart/form-data" => "multipart/form-data", "text/plain" => "text/plain")));
        $builder->add('placeholder');
        $builder->add('button_text');
        $builder->add('role'); 
        $builder->add('alignment', 'choice', array('choices' => array("pull-left" => "Left", "pull-right" => "Right")));
        
        parent::buildForm($builder, $options);
    }
}