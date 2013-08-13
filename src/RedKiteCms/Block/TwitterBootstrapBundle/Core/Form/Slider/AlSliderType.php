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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Slider;

use RedKiteLabs\RedKiteCmsBundle\Core\Form\JsonBlock\JsonBlockType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the form to edit the Bootstrap slider's attributes
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlSliderType extends JsonBlockType
{
    /**
     * {@inheritdoc}
     */    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder->add('src');
        $builder->add('data_src', 'hidden');
        $builder->add('title');
        $builder->add('alt');
        $builder->add('caption_title');
        $builder->add('caption_body', 'textarea');
    }
}
