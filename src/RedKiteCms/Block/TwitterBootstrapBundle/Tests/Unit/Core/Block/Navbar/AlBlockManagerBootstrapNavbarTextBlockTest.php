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
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Navbar\AlBlockManagerBootstrapNavbarTextBlock;

/**
 * AlBlockManagerBootstrapNavbarMenuBlockTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapNavbarTextBlockTest extends BaseTestBlock
{  
    public function testDefaultValue()
    {
        $expectedValue = array(
            "Content" =>    
            '
            {
                "0": {
                    "text": "Default text"
                }
            }
        ',
        );
            
        $this->initContainer(); 
        $blockManager = new AlBlockManagerBootstrapNavbarTextBlock($this->container, $this->validator);
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }
    
    public function testGetHtml()
    {
        $value = '{
            "0": {
                "text": "Default text"
            }
        }';
        
        $block = $this->initBlock($value);
        $this->initContainer();
        
        $blockManager = new AlBlockManagerBootstrapNavbarTextBlock($this->container, $this->validator);
        $blockManager->set($block);
        
        $expectedResult = array('RenderView' => array(
            'view' => 'TwitterBootstrapBundle:Content:Navbar/Text/navbar_text.html.twig',
            'options' => array(
                'data' => array(
                    'text' => 'Default text',
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
                "0": {
                    "text": "Default text"
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
                    ->with('Navbar\Text', 'AlNavbarTextType')
                    ->will($this->returnValue($form))
        ;
        $this->container->expects($this->at(2))
                        ->method('get')
                        ->with('twitter_bootstrap.bootstrap_form_factory')
                        ->will($this->returnValue($formFactory))
        ;
        
        $blockManager = new AlBlockManagerBootstrapNavbarTextBlock($this->container, $this->validator);
        $blockManager->set($block);
        $result = $blockManager->editorParameters();
        $this->assertEquals('TwitterBootstrapBundle:Editor:Navbar/Text/navbar_text_editor.html.twig', $result["template"]);
    }
}