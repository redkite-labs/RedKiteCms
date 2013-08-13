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

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\Base\AlBlockManagerContainerBase;
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Navbar\AlBlockManagerBootstrapNavbarDropdownBlock;

/**
 * AlBlockManagerBootstrapNavbarDropdownBlockTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapNavbarDropdownBlockTest extends AlBlockManagerContainerBase
{  
    public function testDefaultValue()
    {
        $expectedValue = array(
            "Content" =>    
            '
            {
                "0": {
                    "button_text": "Dropdown Button 1",
                    "button_type": "",
                    "button_attribute": "",
                    "button_dropup" : "none",
                    "items": [
                        {
                            "data" : "Item 1", 
                            "metadata" : {  
                                "type": "link",
                                "href": "#",
                                "attributes": {}
                            }
                        },
                        { 
                            "data" : "Item 2", 
                            "metadata" : {  
                                "type": "link",
                                "href": "#",
                                "attributes": {}
                            }
                        },
                        { 
                            "data" : "Item 3", 
                            "metadata" : {  
                                "type": "link",
                                "href": "#",
                                "attributes": {}
                            }
                        }
                    ]
                }
            }'
        );
            
        $this->initContainer(); 
        $blockManager = new AlBlockManagerBootstrapNavbarDropdownBlock($this->container, $this->validator);
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }
    
    public function testEditorParameters()
    {
        $value = '
            {
                "0": {
                    "button_text": "Dropdown Button 1",
                    "button_type": "",
                    "button_attribute": "",
                    "button_dropup" : "none",
                    "items": [
                        {
                            "data" : "Item 1", 
                            "metadata" : {  
                                "type": "link",
                                "href": "#",
                                "attributes": {}
                            }
                        },
                        { 
                            "data" : "Item 2", 
                            "metadata" : {  
                                "type": "link",
                                "href": "#",
                                "attributes": {}
                            }
                        },
                        { 
                            "data" : "Item 3", 
                            "metadata" : {  
                                "type": "link",
                                "href": "#",
                                "attributes": {}
                            }
                        }
                    ]
                }
            }';
        
        $block = $this->initBlock($value);
        $this->initContainer();
        
        $formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $formFactory->expects($this->at(0))
                    ->method('create')
                    ->will($this->returnValue($this->initForm()))
        ;
        
        $formType = $this->getMock('Symfony\Component\Form\FormTypeInterface');
        $this->container->expects($this->at(2))
                        ->method('get')
                        ->with('bootstrap_navbar_dropbown.form')
                        ->will($this->returnValue($formType))
        ;
        
        $this->container->expects($this->at(3))
                        ->method('get')
                        ->with('form.factory')
                        ->will($this->returnValue($formFactory))
        ;
        
        $blockManager = new AlBlockManagerBootstrapNavbarDropdownBlock($this->container, $this->validator);
        $blockManager->set($block);
        $result = $blockManager->editorParameters();
        $this->assertEquals('TwitterBootstrapBundle:Editor:DropdownButton/dropdown_editor.html.twig', $result["template"]);
    }
    
    protected function initBlock($value)
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->once())
              ->method('getContent')
              ->will($this->returnValue($value));

        return $block;
    }
    
    protected function initForm()
    {
        $form = $this->getMockBuilder('Symfony\Component\Form\Form')
                    ->disableOriginalConstructor()
                    ->getMock();
        $form->expects($this->once())
            ->method('createView')
        ;
        
        return $form;
    }
}
