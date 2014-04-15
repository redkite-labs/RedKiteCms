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

namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Tests\Unit\Core\Form\PageHeader;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\BaseType;
use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Form\PageHeader\PageHeaderType;

/**
 * PageHeaderTypeTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class PageHeaderTypeTest extends BaseType
{
    protected $translatorDomain = 'TwitterBootstrapBundle';
    
    protected function configureFields()
    { 
        return array(
            'page_header_title',
            'page_header_subtitle',
            array(
                'name' => 'page_header_tag',
                'type' => 'choice',
                'options' => array(
                    'label' => 'page_header_tag',
                    'choices' => array(
                        'h1' => 'h1',
                        'h2' => 'h2',
                        'h3' => 'h3',
                        'h4' => 'h4',
                        'h5' => 'h5',
                        'h6' => 'h6',
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
        return new PageHeaderType();
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