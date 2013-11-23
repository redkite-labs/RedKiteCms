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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Form\Two\DropdownButton;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\AlBaseType;
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\DropdownButton\Two\AlDropdownButtonType;

/**
 * AlDropdownButtonTypeTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlDropdownButtonTypeTest extends AlBaseType
{
    protected $translatorDomain = 'TwitterBootstrapBundle';
    
    protected function configureFields()
    {
        return array(
            'button_text',
            array(
                'name' => 'button_type',
                'type' => 'choice',
                'options' => array('label' => 'button_block_type', 'choices' => array('' => 'base', 'btn-primary' => 'primary', 'btn-info' => 'info', 'btn-success' => 'success', 'btn-warning' => 'warning', 'btn-danger' => 'danger', 'btn-inverse' => 'inverse')),
            ),
            array(
                'name' => 'button_attribute',
                'type' => 'choice',
                'options' => array('label' => 'button_block_attribute', 'choices' => array("" => "normal", "btn-mini" => "mini", "btn-small" => "small", "btn-large" => "large")),
            ),
            array(
                'name' => 'button_block',
                'type' => 'choice',
                'options' => array('label' => 'button_block_attribute_block', 'choices' => array("" => "normal", "btn-block" => "block")),
            ),
            array(
                'name' => 'button_enabled',
                'type' => 'choice',
                'options' => array('label' => 'button_block_enabled', 'choices' => array("" => "enabled", "disabled" => "disabled")),
            ),
            'button_href',
        );
    }
    
    protected function getForm()
    {
        return new AlDropdownButtonType();
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
