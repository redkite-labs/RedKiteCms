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
 
namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Thumbnail\Three;

use RedKiteLabs\RedKiteCmsBundle\Core\Form\JsonBlock\JsonBlockType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the form to edit a thumbnail block
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlThumbnailType extends JsonBlockType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder->add('width', 'choice', 
            array('choices' => 
                array(
                    'none' => 'none',
                    'col-md-1' => 'col-md-1',
                    'col-md-2' => 'col-md-2',
                    'col-md-3' => 'col-md-3',
                    'col-md-4' => 'col-md-4',
                    'col-md-5' => 'col-md-5',
                    'col-md-6' => 'col-md-6',
                    'col-md-7' => 'col-md-7',
                    'col-md-8' => 'col-md-8',
                    'col-md-9' => 'col-md-9',
                    'col-md-10' => 'col-md-10',
                    'col-md-11' => 'col-md-11',
                    'col-md-12' => 'col-md-12',
                )
            )
        );     
        $builder->add('save', 'submit', array('attr' => array('class' => 'al_editor_save btn btn-primary')));
    }
}
