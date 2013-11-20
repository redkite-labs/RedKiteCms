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

namespace RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Tests\Unit\Core\Form;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\AlBaseType;
use RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Form\Script\AlScriptType;

/**
 * AlLinkTypeTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlScriptTypeTest extends AlBaseType
{
    protected function configureFields()
    {
        return array(
            array(
                'name' => 'content',
                'type' => 'textarea',
                'options' => array('label' => false, 'attr' => array('class' => 'editor-textarea', 'rows' => '12')),
            ),
        );
    }
    
    protected function getForm()
    {
        return new AlScriptType();
    }
}