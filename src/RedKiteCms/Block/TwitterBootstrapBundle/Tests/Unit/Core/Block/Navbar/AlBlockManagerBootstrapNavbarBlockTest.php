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

use RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Block\Base\BaseTestBlock;
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Navbar\AlBlockManagerBootstrapNavbarBlock;


/**
 * AlBlockManagerBootstrapNavbarBlockTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapNavbarBlockTest extends BaseTestBlock
{  
    public function testDefaultValue()
    {
        $expectedValue = array(
            "Content" =>    '
            {
                "position": "navbar-fixed-top",
                "inverted": "",
                "items": {
                    "0": {
                        "blockType" : "BootstrapNavbarMenuBlock"
                    }
                }
            }'
        );
            
        $this->initContainer(); 
        $blockManager = new AlBlockManagerBootstrapNavbarBlock($this->container, $this->validator);
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }

    /**
     * @dataProvider bootstrapVersionsProvider
     */
    public function testGetHtml($bootstrapVersion)
    {
        $value = '
        {
            "0" : {
                    "0": "item"
                },
                "1" : {
                    "0": "item"
                }
        }';
            
        $block = $this->initBlock($value);
        $this->initContainer();
        $this->initBootstrapversion($bootstrapVersion);
        
        $blockManager = new AlBlockManagerBootstrapNavbarBlock($this->container, $this->validator);
        $blockManager->set($block);

        $expectedResult = array('RenderView' => array(
            'view' => 'TwitterBootstrapBundle:Content:Navbar/Navbar/' .$bootstrapVersion . '/navbar.html.twig',
            'options' => array(
                'navbar' => array(
                    "position" =>  "navbar-fixed-top",
                    "inverted" =>  "",
                    "items" => array(
                        array(
                            "blockType" =>  "BootstrapNavbarMenuBlock",
                        )
                    ),
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
                    "button_enabled": ""
                }
            }';
        
        $block = $this->initBlock($value);
        $this->initContainer();

        $formType = $this->getMock('Symfony\Component\Form\FormTypeInterface');
        $this->container->expects($this->at(3))
                        ->method('get')
                        ->with('bootstrap_navbar.form')
                        ->will($this->returnValue($formType))
        ;
        
        $form = $this->getMockBuilder('Symfony\Component\Form\Form')
                    ->disableOriginalConstructor()
                    ->getMock();
        $form->expects($this->once())
            ->method('createView')
            ->will($this->returnValue('the-form'))
        ;
        
        $formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $formFactory->expects($this->once())
                    ->method('create')
                    ->will($this->returnValue($form))
        ;
        $this->container->expects($this->at(4))
                        ->method('get')
                        ->with('form.factory')
                        ->will($this->returnValue($formFactory))
        ;
        
        $blockManager = new AlBlockManagerBootstrapNavbarBlock($this->container, $this->validator);
        $blockManager->set($block);
        $result = $blockManager->editorParameters();
        $this->assertEquals('TwitterBootstrapBundle:Editor:Navbar/Navbar/navbar_editor.html.twig', $result["template"]);
    }
}