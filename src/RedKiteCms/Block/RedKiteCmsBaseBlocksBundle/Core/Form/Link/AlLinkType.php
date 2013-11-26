<?php
/*
 * This file is part of the BusinessDropCapBundle and it is distributed
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

namespace RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Form\Link;

use RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Form\Base\AlBaseType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the link editor form
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlLinkType extends AlBaseType
{
    /**
     * Builds the form
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('href', null, array(
            'attr' => array('class' => 'rk-href'),
            'label' => 'link_block_href',
        ));
        $builder->add('value', null, array(
            'label' => 'link_block_displayed_value',
        ));
        
        parent::buildForm($builder, $options);
    }
}