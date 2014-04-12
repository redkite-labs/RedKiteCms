<?php
/**
 * This file is part of the BusinessDropCapBundle and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBaseBlocksBundle\Core\Form\File;

use Symfony\Component\Form\FormBuilderInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBaseBlocksBundle\Core\Form\Base\BaseType;

/**
 * Defines the file form
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class FileType extends BaseType
{
    /**
     * Builds the form
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file');
        $builder->add('description', 'textarea');
        $builder->add('opened', 'checkbox', array(
            'label' => 'file_block_show_opened',
            )
        );

        parent::buildForm($builder, $options);
    }
}
