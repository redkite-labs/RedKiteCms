<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\Bridge\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * The object deputed to define a RedKiteCMS generic form
 *
 * @author  RedKite Labs <info@redkite-labs.com>
 * @package RedKiteCms\Bridge\Form
 */
abstract class BaseType extends AbstractType
{
    private $pages;

    /**
     * Constructor
     *
     * @param array $pages
     */
    public function __construct(array $pages = array())
    {
        $this->pages = $pages;
    }

    /**
     * Sets the default options for this type
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'translation_domain' => 'RedKiteCms',
                'csrf_protection' => false,
            )
        );
    }

    /**
     * Returns the name of this type
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'rkcms_block';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function addClassAttribute(FormBuilderInterface $builder, array $options = array())
    {
        $attributes = array_merge_recursive(
            array(
                'label' => 'common_block_css_class',
                'attr' => array(
                    'data-bind' => "value: cssClass, valueUpdate: 'afterkeydown', event: {change: edit}",
                    "placeholder" => 'image_block_class_placeholder',
                ),
            ),
            $options
        );

        $builder->add('class', 'text', $attributes);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function addSitePages(FormBuilderInterface $builder, array $options = array())
    {
        $attributes = array_merge_recursive(
            array(
                'choices' => $this->pages,
                'empty_value' => '',
                'attr' => array(
                    'data-bind' => 'value: selected',
                    'class' => 'form-control input-sm',
                ),
            ),
            $options
        );

        $builder->add('pages_selector', 'choice', $attributes);
    }
}
