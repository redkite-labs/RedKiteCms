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
 
namespace RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Form\Slider;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\AlBaseType;
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Slider\AlSliderType;

/**
 * AlSliderTypeTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlSliderTypeTest extends AlBaseType
{
    protected function configureFields()
    {
        return array(
            'src',
            array('name' => 'data_src', 'type' => 'hidden'),
            'title',
            'alt',
            'caption_title',
            array('name' => 'caption_body', 'type' => 'textarea'),
        );
    }
    
    protected function getForm()
    {
        return new AlSliderType();
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
