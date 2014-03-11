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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Form\Two\Label;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\AlBaseType;
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Label\Two\AlLabelType;

/**
 * AlLabelTypeTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlLabelTypeTest extends AlBaseType
{
    protected $translatorDomain = 'TwitterBootstrapBundle';
    
    protected function configureFields()
    {
        return array(
            'label_text',
            array(
                'name' => 'label_type',
                'type' => 'choice',
                'options' => array(
                    'label' => 'label_block_type',
                    'choices' => array('' => 'base', 'label-info' => 'info', 'label-success' => 'success', 'label-warning' => 'warning', 'label-important' => 'important', 'label-inverse' => 'inverse')
                ),
            ),
            'class',
            array(
                'name' => 'save',
                'type' => 'submit',
                'options' => array(
                    'label' => 'common_label_save',
                    'attr' => array('class' => 'al_editor_save btn btn-primary')
                ),
            ),
        );
    }
    
    protected function getForm()
    {
        return new AlLabelType();
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
