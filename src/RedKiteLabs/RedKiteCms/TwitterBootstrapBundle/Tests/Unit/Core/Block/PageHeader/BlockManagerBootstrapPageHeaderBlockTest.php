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
 
namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Tests\Unit\Core\Block\Slider;

use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Tests\Unit\Core\Block\Base\BaseTestBlock;
use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Block\PageHeader\BlockManagerBootstrapPageHeaderBlock;


/**
 * BlockManagerBootstrapSliderBlockTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerBootstrapPageHeaderBlockTest extends BaseTestBlock
{  
    public function testDefaultValue()
    {
        $expectedValue = array(
            "Content" => '
                {
                    "0": {
                        "page_header_title": "Page Header",
                        "page_header_subtitle": "An awesome component",
                        "page_header_tag": "h1"
                    }
                }',
        );
        
        $this->initContainer(); 
        $blockManager = new BlockManagerBootstrapPageHeaderBlock($this->container);
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }
   
    public function testGetHtml()
    {
        $value = '{
            "0" : {
                "page_header_title": "Page Header",
                "page_header_subtitle": "An awesome component",
                "page_header_tag": "h1"
            }
        }';
        
        $block = $this->initBlock($value);
        $this->initContainer();
        
        $blockManager = new BlockManagerBootstrapPageHeaderBlock($this->container);
        $blockManager->set($block);
        
        $expectedResult = array('RenderView' => array(
            'view' => 'TwitterBootstrapBundle:Content:PageHeader/page_header.html.twig',
            'options' => array(
                "data" => array(
                    'page_header_title' => 'Page Header',
                    'page_header_subtitle' => 'An awesome component',
                    'page_header_tag' => 'h1',
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
                "page_header_title": "Page Header",
                "page_header_subtitle": "An awesome component",
                "page_header_tag": "h1"
            }
        }';
        
        $block = $this->initBlock($value);
        $this->initContainer();
        $form = $this->initFormFactory();        
        $form->expects($this->once())
            ->method('createView')
        ;
        
        $blockManager = new BlockManagerBootstrapPageHeaderBlock($this->container);
        $blockManager->set($block);
        $result = $blockManager->editorParameters();
        $this->assertEquals('TwitterBootstrapBundle:Editor:PageHeader/page_header_editor.html.twig', $result["template"]);
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
                        ->with('bootstrap_page_header_block.form')
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