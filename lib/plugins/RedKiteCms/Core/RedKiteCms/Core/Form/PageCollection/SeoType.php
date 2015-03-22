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

namespace RedKiteCms\Core\RedKiteCms\Core\Form\PageCollection;

use RedKiteCms\Bridge\Form\BaseType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SeoType is the object deputed to handle the seo values
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Core\RedKiteCms\Core\Form\Page
 */
class SeoType extends BaseType
{
    /**
     * Builds the form
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add(
            'permalink',
            null,
            array(
                'attr' => array(
                    'data-bind' => 'value: permalink, event: {change: $root.editSeo}',
                    'class' => 'form-control input-sm',
                ),
            )
        );
        $builder->add(
            'title',
            'textarea',
            array(
                'attr' => array(
                    'data-bind' => 'value: title, event: {change: $root.editSeo}',
                    'class' => 'form-control input-sm',
                    'rows' => '3',
                ),
            )
        );
        $builder->add(
            'description',
            'textarea',
            array(
                'attr' => array(
                    'data-bind' => 'value: description, event: {change: $root.editSeo}',
                    'class' => 'form-control input-sm',
                    'rows' => '3',
                ),
            )
        );
        $builder->add(
            'keywords',
            'textarea',
            array(
                'attr' => array(
                    'data-bind' => 'value: keywords, event: {change: $root.editSeo}',
                    'class' => 'form-control input-sm',
                    'rows' => '3',
                ),
            )
        );
        $builder->add(
            'sitemap_frequency',
            'choice',
            array(
                'label' => 'Frequency',
                'choices' => array(
                    'always' => 'always',
                    'hourly' => 'hourly',
                    'daily' => 'daily',
                    'weekly' => 'weekly',
                    'monthly' => 'monthly',
                    'yearly' => 'yearly',
                    'never' => 'never',
                ),
                'attr' => array(
                    'data-bind' => 'value: sitemapFrequency, event: {change: $root.editSeo}',
                    'class' => 'form-control input-sm',
                ),
            )
        );
        $builder->add(
            'sitemap_priority',
            'choice',
            array(
                'label' => 'Priority',
                'choices' => array(
                    '0,0' => '0,0',
                    '0,1' => '0,1',
                    '0,2' => '0,2',
                    '0,3' => '0,3',
                    '0,4' => '0,4',
                    '0,5' => '0,5',
                    '0,6' => '0,6',
                    '0,7' => '0,7',
                    '0,8' => '0,8',
                    '0,9' => '0,9',
                    '1' => '1',
                ),
                'attr' => array(
                    'data-bind' => 'value: sitemapPriority, event: {change: $root.editSeo}',
                    'class' => 'form-control input-sm',
                ),
            )
        );
    }
}
