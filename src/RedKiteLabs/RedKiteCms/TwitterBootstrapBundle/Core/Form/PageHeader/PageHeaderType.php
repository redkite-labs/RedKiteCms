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

namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Form\PageHeader;

use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Form\Base\BaseType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the form to edit a Page Header block
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class PageHeaderType extends BaseType
{
    /**
     * Builds the form
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('page_header_title', null, array('label' => 'page_header_title',));
        $builder->add('page_header_subtitle', null, array('label' => 'page_header_subtitle',));
        $builder->add('page_header_tag', 'choice', array(
            'label' => 'page_header_tag',
            'choices' => array(
                'h1' => 'h1',
                'h2' => 'h2',
                'h3' => 'h3',
                'h4' => 'h4',
                'h5' => 'h5',
                'h6' => 'h6',
            )
        ));
        $this->addClassAttribute($builder);

        parent::buildForm($builder, $options);
    }
}
