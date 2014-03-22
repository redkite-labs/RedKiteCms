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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Form\Navbar;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\BaseType;
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Navbar\NavbarType;

/**
 * NavbarDropdownTypeTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class NavbarTypeTest extends BaseType
{
    protected $translatorDomain = 'TwitterBootstrapBundle';
    
    protected function configureFields()
    {
        return array(
            array(
                'name' => 'position',
                'type' => 'choice',
                'options' => array(
                    'label' => 'navbar_position', 
                    'choices' => array("" => "normal", "navbar-fixed-top" => "fixed top", "navbar-fixed-bottom" => "fixed bottom", "navbar-static-top" => "static top"),
                ),
            ),
            array(
                'name' => 'inverted',
                'type' => 'choice',
                'options' => array(
                    'label' => 'navbar_inverted',
                    'choices' => array("" => "normal", "navbar-inverse" => "inverted"),
                ),
            ),
            array(
                'name' => 'save', 
                'type' => 'submit', 
                'options' => array(
                    'label' => 'common_label_save',
                    'attr' => array('class' => 'al_editor_save btn btn-primary'),
                ),
            ),
        );
    }
    
    protected function getForm()
    {
        return new NavbarType();
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
