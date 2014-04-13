<?php
/*
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Tests\Unit\Core\Form\Three\Label;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\BaseType;
use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Form\Label\Three\LabelType;

/**
 * LabelTypeTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class LabelTypeTest extends BaseType
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
                    'choices' => array(
                        'label-default' => 'base', 
                        'label-primary' => 'primary', 
                        'label-success' => 'success', 
                        'label-info' => 'info', 
                        'label-warning' => 'warning', 
                        'label-danger' => 'danger', 
                    )
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
        return new LabelType();
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