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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Badge\Two;

use RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Base\AlBaseType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the form to edit a badge block
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBadgeType extends AlBaseType
{
    /**
     * Builds the form
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('badge_text', null, array('label' => 'badge_block_text'));
        $builder->add('badge_type', 'choice', array('label' => 'badge_block_type', 'choices' => array('' => 'base', 'badge-info' => 'info', 'badge-success' => 'success', 'badge-warning' => 'warning', 'badge-important' => 'important', 'badge-inverse' => 'inverse')));
        $this->addClassAttribute($builder);

        parent::buildForm($builder, $options);
    }
}
