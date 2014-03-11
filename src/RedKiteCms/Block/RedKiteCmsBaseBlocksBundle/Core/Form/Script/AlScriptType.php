<?php
/**
 * This file is part of the RedKiteCmsBaseBlocksBundle and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Form\Script;

use RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Form\Base\AlBaseType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the script editor form
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlScriptType extends AlBaseType
{
    /**
     * Builds the form
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', 'textarea', array('label' => false, 'attr' => array('class' => 'editor-textarea', 'rows' => '12')));

        parent::buildForm($builder, $options);
    }
}
