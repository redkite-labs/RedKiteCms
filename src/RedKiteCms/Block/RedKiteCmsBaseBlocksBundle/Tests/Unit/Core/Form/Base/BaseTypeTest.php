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

namespace RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Tests\Unit\Core\Form\Base;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\BaseType;
use RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Form\File\FileType;

class TestForm extends \RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Form\Base\BaseType
{
}

/**
 * BaseTypeTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BaseTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testResolver()
    {
        $resolver = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolverInterface');
        
        $options = array(
            'translation_domain' => 'RedKiteCmsBaseBlocksBundle',
            'csrf_protection' => false,
        );
        $resolver
            ->expects($this->at(0))
            ->method('setDefaults')
            ->with($options)
        ;
        
        $form = new TestForm();
        $form->setDefaultOptions($resolver);
    }
}