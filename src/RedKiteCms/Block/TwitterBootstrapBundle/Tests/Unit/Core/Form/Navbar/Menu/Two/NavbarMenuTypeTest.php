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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Form\Navbar\Two;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\AlBaseType;
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Navbar\Menu\Two\AlNavbarMenuType;

/**
 * AlNavbarDropdownTypeTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlNavbarMenuTypeTest extends AlBaseType
{
    protected $translatorDomain = 'TwitterBootstrapBundle';
    
    protected function configureFields()
    {
        return array(
            array(
                'name' => 'alignment',
                'type' => 'choice',
                'options' => array(
                    'label' => 'navbar_alignment', 
                    'choices' => array("pull-left" => "Left", "pull-right" => "Right"), 'empty_value' => 'None'),
            ),
            array(
                'name' => 'save', 
                'type' => 'submit', 
                'options' => array(
                    'label' => 'common_label_save', 
                    'attr' => array('class' => 'al_editor_save btn btn-primary')),
            ),
        );
    }
    
    protected function getForm()
    {
        return new AlNavbarMenuType();
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