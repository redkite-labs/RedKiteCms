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
 
namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Tests\Unit\Core\Form\Slider;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\BaseType;
use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Form\Slider\SliderType;

/**
 * SliderTypeTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class SliderTypeTest extends BaseType
{
    protected $translatorDomain = 'TwitterBootstrapBundle';
    
    protected function configureFields()
    {
        return array(
            array(
                'name' => 'src',                
                'type' => null,
                'options' => array(
                    'label' => 'slider_src_attribute',
                ),
            ),
            array('name' => 'data_src', 'type' => 'hidden'),
            array(
                'name' => 'title',                
                'type' => null,
                'options' => array(
                    'label' => 'slider_title_attribute',
                ),
            ),
            array(
                'name' => 'alt',                
                'type' => null,
                'options' => array(
                    'label' => 'slider_alt_attribute',
                ),
            ),
            array(
                'name' => 'caption_title',                
                'type' => null,
                'options' => array(
                    'label' => 'slider_caption_title',
                ),
            ),
            array(
                'name' => 'caption_body', 
                'type' => 'textarea', 
                "options" => array(
                    'label' => 'slider_caption_body', 
                    'attr' => array('rows' => 6),
                )
            ),
        );
    }
    
    protected function getForm()
    {
        return new SliderType();
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
