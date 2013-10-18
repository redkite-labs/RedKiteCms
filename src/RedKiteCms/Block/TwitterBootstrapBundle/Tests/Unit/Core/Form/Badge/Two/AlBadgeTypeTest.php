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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Form\Badge\Two;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\AlBaseType;
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Badge\Two\AlBadgeType;

/**
 * AlBadgeTypeTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBadgeTypeTest extends AlBaseType
{
    protected function configureFields()
    {
        return array(
            'badge_text',
            array(
                'name' => 'badge_type',
                'type' => 'choice', 
                'options' => array('choices' => array('' => 'base', 'badge-info' => 'info', 'badge-success' => 'success', 'badge-warning' => 'warning', 'badge-important' => 'important', 'badge-inverse' => 'inverse')),
            ),
            array(
                'name' => 'save',
                'type' => 'submit',
                'options' => array('attr' => array('class' => 'al_editor_save btn btn-primary')),
            ),
        );
    }
    
    protected function getForm()
    {
        return new AlBadgeType();
    }
    
    public function testDefaultOptions()
    {
        $this->setBaseResolver();

        $this->getForm()->setDefaultOptions($this->resolver);
    }
    
    public function testGetName()
    {
        $this->assertEquals('al_json_block', $this->getForm()->getName());
    }
}
