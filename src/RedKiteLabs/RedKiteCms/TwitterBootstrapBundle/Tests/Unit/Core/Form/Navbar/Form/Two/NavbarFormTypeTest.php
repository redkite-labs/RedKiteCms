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

namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Tests\Unit\Core\Form\Navbar\Form\Two;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\BaseType;
use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Form\Navbar\Form\Two\NavbarFormType;

/**
 * NavbarFormTypeTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class NavbarFormTypeTest extends BaseType
{
    protected $translatorDomain = 'TwitterBootstrapBundle';
    
    protected function configureFields()
    {
        return array(
            array(
                'name' => 'method',
                'type' => 'choice',
                'options' => array(
                    'label' => 'navbar_form_method', 
                    'choices' => array("post" => "POST", "get" => "GET"),
                ),
            ),
            array(
                'name' => 'action', 
                'type' => null,
                'options' => array(
                    'label' => 'navbar_form_action', 
                ),
            ),
            array(
                'name' => 'enctype',
                'type' => 'choice',
                'options' => array(
                    'label' => 'navbar_form_enctype', 
                    'choices' => array(
                        "" => "", 
                        "application/x-www-form-urlencoded" => "application/x-www-form-urlencoded", 
                        "multipart/form-data" => "multipart/form-data", "text/plain" => "text/plain",
                    ),
                ),
            ),
            array(
                'name' => 'placeholder', 
                'type' => null,
                'options' => array(
                    'label' => 'navbar_form_placeholder', 
                ),
            ),
            array(
                'name' => 'button_text', 
                'type' => null,
                'options' => array(
                    'label' => 'navbar_button_text', 
                ),
            ),
            array(
                'name' => 'role', 
                'type' => null,
                'options' => array(
                    'label' => 'navbar_form_role', 
                ),
            ),
        );
    }
    
    protected function getForm()
    {
        return new NavbarFormType();
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