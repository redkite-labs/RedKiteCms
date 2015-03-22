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
 * Class PageType is the object deputed to define the page editor form
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Core\RedKiteCms\Core\Form\Page
 */
class PageType extends BaseType
{
    /**
     * @type array
     */
    private $templates;

    /**
     * Constructor
     *
     * @param array $templates
     */
    public function __construct(array $templates)
    {
        $this->templates = $templates;
    }

    /**
     * Builds the form
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add(
            'pagename',
            null,
            array(
                'attr' => array(
                    'data-bind' => 'value: name, uniqueName: true, valueUpdate: \'afterkeydown\', event: {change: $root.editPage}',
                    'class' => 'form-control input-sm',
                ),
            )
        );

        $builder->add(
            'templatename',
            'choice',
            array(
                'choices' => $this->templates,
                'attr' => array(
                    'data-bind' => 'value: template, uniqueName: true, valueUpdate: \'afterkeydown\', event: {change: $root.editPage}',
                    'class' => 'form-control input-sm',
                ),
            )
        );
    }
}
