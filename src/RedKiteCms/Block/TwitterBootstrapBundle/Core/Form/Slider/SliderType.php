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

use RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Base\BaseType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the form to edit the Bootstrap slider's attributes
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class SliderType extends BaseType
{
    /**
     * Builds the form
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('src', null, array('label' => 'slider_src_attribute'));
        $builder->add('data_src', 'hidden');
        $builder->add('title', null, array('label' => 'slider_title_attribute'));
        $builder->add('alt', null, array('label' => 'slider_alt_attribute'));
        $builder->add('caption_title', null, array('label' => 'slider_caption_title'));
        $builder->add('caption_body', 'textarea', array('label' => 'slider_caption_body', 'attr' => array('rows' => 6)));
    }
}
