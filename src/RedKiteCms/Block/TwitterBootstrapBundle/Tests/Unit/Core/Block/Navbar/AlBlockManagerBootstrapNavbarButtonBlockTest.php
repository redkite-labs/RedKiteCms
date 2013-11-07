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
 
namespace RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Block\Navbar;

use RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Block\Button\AlBlockManagerBootstrapButtonBlockTest;
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Navbar\AlBlockManagerBootstrapNavbarButtonBlock;

/**
 * AlBlockManagerBootstrapNavbarButtonBlockTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapNavbarButtonBlockTest extends AlBlockManagerBootstrapButtonBlockTest
{  
    public function testDefaultValue()
    {
        $expectedValue = array(
            "Content" =>    
            '
            {
                "0" : {
                    "button_text": "Button 1",
                    "button_type": "",
                    "button_attribute": "",
                    "button_block": "",
                    "button_enabled": "",
                    "alignment": "navbar-left"
                }
            }
        '
        );
            
        $this->initContainer(); 
        $blockManager = new AlBlockManagerBootstrapNavbarButtonBlock($this->container, $this->validator);
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }
    
    public function testGetHtml()
    {
        $value = '{
            "0" : {
                "button_text": "Button 1",
                "button_type": "danger",
                "button_attribute": "large",
                "button_block": "block",
                "button_enabled": "true",
                "alignment": "navbar-left"
            }
        }';
        
        $block = $this->initBlock($value);
        $this->initContainer();
        
        $blockManager = new AlBlockManagerBootstrapNavbarButtonBlock($this->container, $this->validator);
        $blockManager->set($block);
        
        $expectedResult = array('RenderView' => array(
            'view' => 'TwitterBootstrapBundle:Content:Navbar/Button/navbar_button.html.twig',
            'options' => array(
                'data' => array(
                    'button_text' => 'Button 1',
                    'button_type' => 'danger',
                    'button_attribute' => 'large',
                    'button_block' => 'block',
                    'button_enabled' => 'true',
                    'alignment' => 'navbar-left',
                ),
                'block_manager' => $blockManager,
            ),
        ));
        
        $this->assertEquals($expectedResult, $blockManager->getHtml());
    }
    
    public function testEditorParameters()
    {
        $value = '
            {
                "0" : {
                    "button_text": "Button 1",
                    "button_type": "",
                    "button_attribute": "",
                    "button_block": "",
                    "button_enabled": "",
                    "alignment": "navbar-left"
                }
            }';
        
        $block = $this->initBlock($value);
        $this->initContainer();

        $form = $this->getMockBuilder('Symfony\Component\Form\Form')
                    ->disableOriginalConstructor()
                    ->getMock();
        $form->expects($this->once())
            ->method('createView')
            ->will($this->returnValue('the-form'))
        ;

        $formFactory = $this->getMockBuilder('RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Factory\BootstrapFormFactory')
                    ->disableOriginalConstructor()
                    ->getMock();
        $formFactory->expects($this->once())
                    ->method('createForm')
                    ->with('Navbar\Button', 'AlNavbarButtonType')
                    ->will($this->returnValue($form))
        ;
        $this->container->expects($this->at(2))
                        ->method('get')
                        ->with('twitter_bootstrap.bootstrap_form_factory')
                        ->will($this->returnValue($formFactory))
        ;
        
        $blockManager = new AlBlockManagerBootstrapNavbarButtonBlock($this->container, $this->validator);
        $blockManager->set($block);
        $result = $blockManager->editorParameters();
        $this->assertEquals('TwitterBootstrapBundle:Editor:Button/button_editor.html.twig', $result["template"]);
    }
}
