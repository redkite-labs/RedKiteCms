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
 
namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Label\Two;

use RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Base\AlBaseType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the form to edit a label block
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlLabelType extends AlBaseType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('label_text', null, array('label' => 'label_block_text',));
        $builder->add('label_type', 'choice', array('label' => 'label_block_type', 'choices' => array('' => 'base', 'label-info' => 'info', 'label-success' => 'success', 'label-warning' => 'warning', 'label-important' => 'important', 'label-inverse' => 'inverse')));
        
        parent::buildForm($builder, $options);
    }
}
