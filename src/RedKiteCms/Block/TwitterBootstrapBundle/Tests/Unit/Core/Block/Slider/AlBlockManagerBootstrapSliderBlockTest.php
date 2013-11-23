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
 
namespace RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Block\Slider;

use RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Block\Base\BaseTestBlock;
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Slider\AlBlockManagerBootstrapSliderBlock;

class AlBlockManagerBootstrapSliderBlockTester extends AlBlockManagerBootstrapSliderBlock
{
    public function removeFormNameReferenceTester($values)
    {
        return $this->removeFormNameReference($values);
    }
}

/**
 * AlBlockManagerBootstrapSliderBlockTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapSliderBlockTest extends BaseTestBlock
{  
    public function testDefaultValue()
    {
        $expectedValue = array(
            "Content" =>    
        '{
            "0" : {
                "src": "",
                "data_src" : "holder.js/400x280",
                "title" : "Sample title",
                "alt" : "Sample alt",
                "caption_title" : "First Thumbnail label",
                "caption_body" : "Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus."
            },
            "1" : {
                "src": "",
                "data_src" : "holder.js/400x280",
                "title" : "Sample title",
                "alt" : "Sample alt",
                "caption_title" : "Second Thumbnail label",
                "caption_body" : "Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus."
            },
            "2" : {
                "src": "",
                "data_src" : "holder.js/400x280",
                "title" : "Sample title",
                "alt" : "Sample alt",
                "caption_title" : "Third Thumbnail label",
                "caption_body" : "Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus."
            }
        }',
        );
            
        $this->initContainer(); 
        $blockManager = new AlBlockManagerBootstrapSliderBlock($this->container, $this->validator);
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }
    
    public function testGetHtmlReturnsAnEmptyStringWhenAnyBlockIsDefined()
    {
        $this->initContainer();
        $blockManager = new AlBlockManagerBootstrapSliderBlock($this->container, $this->validator);
                
        $this->assertEquals("", $blockManager->getHtml());
    }

    /**
     * @dataProvider bootstrapVersionsProvider
     */
    public function testGetHtml($bootstrapVersion)
    {
        $value = '{
            "0" : {
                "src": "/path/to/image",
                "data_src" : "holder.js/400x280",
                "title" : "Sample title",
                "alt" : "Sample alt",
                "caption_title" : "First Thumbnail label",
                "caption_body" : "Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus."
            }
        }';
        
        $block = $this->initBlock($value);
        $this->initContainer();
        $this->initBootstrapversion($bootstrapVersion);
        
        $blockManager = new AlBlockManagerBootstrapSliderBlock($this->container, $this->validator);
        $blockManager->set($block);
        
        $expectedResult = array('RenderView' => array(
            'view' => 'TwitterBootstrapBundle:Content:Slider/' . $bootstrapVersion . '/content.html.twig',
            'options' => array(
                'items' => array(
                    array(
                        "src" => "/path/to/image",
                        "data_src" => "holder.js/400x280",
                        "title" => "Sample title",
                        "alt" => "Sample alt",
                        "caption_title" => "First Thumbnail label",
                        "caption_body" => "Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus.",
                    ),
                ),
                'block_manager' => $blockManager,
            ),
        ));
        
        $this->assertEquals($expectedResult, $blockManager->getHtml());
    }
    
    public function testEditorParameters()
    {
        $value = '{
            "0" : {
                "src": "/path/to/image",
                "data_src" : "holder.js/400x280",
                "title" : "Sample title",
                "alt" : "Sample alt",
                "caption_title" : "First Thumbnail label",
                "caption_body" : "Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus."
            }
        }';
        
        $block = $this->initBlock($value);
        $this->initContainer();
        $form = $this->initFormFactory();        
        $form->expects($this->once())
            ->method('createView')
        ;
        
        $blockManager = new AlBlockManagerBootstrapSliderBlock($this->container, $this->validator);
        $blockManager->set($block);
        $result = $blockManager->editorParameters();
        $this->assertEquals('TwitterBootstrapBundle:Editor:Slider/editor.html.twig', $result["template"]);
    }
    
    public function testRemoveFormNameReference()
    {
        $this->initContainer();
        $form = $this->initFormFactory();
        $form->expects($this->once())
              ->method('getName')
              ->will($this->returnValue('al_json_block'))
        ;
        
        $values = array(
            "Content" => '[{"al_json_block_src":"","al_json_block_title":"Sample title 112","al_json_block_alt":"Sample alt","al_json_block_caption_title":"First Thumbnail label","al_json_block_caption_body":"Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus.","al_json_block_data_src":"holder.js/400x280"},{"al_json_block_src":"","al_json_block_data_src":"holder.js/400x280","al_json_block_title":"Sample title","al_json_block_alt":"Sample alt","al_json_block_caption_title":"Second Thumbnail label","al_json_block_caption_body":"Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus."},{"al_json_block_src":"","al_json_block_data_src":"holder.js/400x280","al_json_block_title":"Sample title","al_json_block_alt":"Sample alt","al_json_block_caption_title":"Third Thumbnail label","al_json_block_caption_body":"Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus."}]',            
        );
        
        $expectedResult = array(
            "Content" => '[{"src":"","title":"Sample title 112","alt":"Sample alt","caption_title":"First Thumbnail label","caption_body":"Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus.","data_src":"holder.js/400x280"},{"src":"","data_src":"holder.js/400x280","title":"Sample title","alt":"Sample alt","caption_title":"Second Thumbnail label","caption_body":"Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus."},{"src":"","data_src":"holder.js/400x280","title":"Sample title","alt":"Sample alt","caption_title":"Third Thumbnail label","caption_body":"Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus."}]',
        );
        
        $blockManager = new AlBlockManagerBootstrapSliderBlockTester($this->container, $this->validator);
        $this->assertEquals($expectedResult, $blockManager->removeFormNameReferenceTester($values));
    }
    
    protected function initForm()
    {
        $form = $this->getMockBuilder('Symfony\Component\Form\Form')
                    ->disableOriginalConstructor()
                    ->getMock();
        $form->expects($this->any())
            ->method('createView')
        ;
        
        return $form;
    }
    
    private function initFormFactory()
    {
        $form = $this->initForm();
        $formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $formFactory->expects($this->at(0))
                    ->method('create')
                    ->will($this->returnValue($form))
        ;
        
        $formType = $this->getMock('Symfony\Component\Form\FormTypeInterface');
        $this->container->expects($this->at(3))
                        ->method('get')
                        ->with('bootstrapsliderblock.form')
                        ->will($this->returnValue($formType))
        ;
        
        $this->container->expects($this->at(4))
                        ->method('get')
                        ->with('form.factory')
                        ->will($this->returnValue($formFactory))
        ;
        
        return $form;
    }
}