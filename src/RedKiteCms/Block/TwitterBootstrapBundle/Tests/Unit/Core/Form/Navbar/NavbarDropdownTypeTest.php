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

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\AlBaseType;
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Navbar\AlNavbarDropdownType;

/**
 * AlNavbarDropdownTypeTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlNavbarDropdownTypeTest extends AlBaseType
{
    protected $translatorDomain = 'TwitterBootstrapBundle';
    
    protected function configureFields()
    {
        return array(
            array(
                'name' => 'button_text', 
                'type' => null, 
                'options' => array(
                    'label' => 'navbar_button_text',
                ),
            ),
        );
    }
    
    protected function getForm()
    {
        return new AlNavbarDropdownType();
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
