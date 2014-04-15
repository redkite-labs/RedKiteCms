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
 
namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Tests\Unit\Core\Block\Navbar;

use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Tests\Unit\Core\Block\Base\BaseTestBlock;
use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Block\Navbar\BlockManagerBootstrapNavbarMenuBlock;

/**
 * BlockManagerBootstrapNavbarMenuBlockTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerBootstrapNavbarMenuBlockTest extends BaseTestBlock
{  
    public function testDefaultValue()
    {
        $expectedValue = array(
            "Content" =>    
            '
            {
                "alignment" : "none",
                "items": {
                    "0": {
                        "blockType" : "Link"
                    },
                    "1": {
                        "blockType" : "Link"
                    }
                }
            }'
        );
            
        $this->initContainer(); 
        $blockManager = new BlockManagerBootstrapNavbarMenuBlock($this->container, $this->validator);
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }
    
    public function testGetHtml()
    {
        $value = '{
            "alignment" : "none",
            "items": {
                "0": {
                    "blockType" : "Link"
                },
                "1": {
                    "blockType" : "Link"
                }
            }
        }';
        
        $block = $this->initBlock($value);
        $this->initContainer();
        
        $blockManager = new BlockManagerBootstrapNavbarMenuBlock($this->container, $this->validator);
        $blockManager->set($block);
        
        $expectedResult = array('RenderView' => array(
            'view' => 'TwitterBootstrapBundle:Content:Navbar/Menu/navbar_menu.html.twig',
            'options' => array(
                'menu' => array(
                    'items' => array(
                        array(
                            'blockType' => 'Link',
                        ),
                        array(
                            'blockType' => 'Link',
                        ),
                    ),
                    'alignment' => 'none',
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
                "alignment" : "none",
                "items": {
                    "0": {
                        "blockType" : "Link"
                    },
                    "1": {
                        "blockType" : "Link"
                    }
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

        $formFactory = $this->getMockBuilder('RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Form\Factory\BootstrapFormFactory')
                    ->disableOriginalConstructor()
                    ->getMock();
        $formFactory->expects($this->once())
                    ->method('createForm')
                    ->with('Navbar\Menu', 'NavbarMenuType')
                    ->will($this->returnValue($form))
        ;
        $this->container->expects($this->at(3))
                        ->method('get')
                        ->with('twitter_bootstrap.bootstrap_form_factory')
                        ->will($this->returnValue($formFactory))
        ;
        
        $blockManager = new BlockManagerBootstrapNavbarMenuBlock($this->container, $this->validator);
        $blockManager->set($block);
        $result = $blockManager->editorParameters();
        $this->assertEquals('TwitterBootstrapBundle:Editor:Navbar/Menu/navbar_menu_editor.html.twig', $result["template"]);
    }
}