<?php
/**
 * This file is part of the RedKiteCmsBaseBlocksBundle and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Tests\Unit\Core\Block\Image;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\Base\BlockManagerContainerBase;
use RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image\BlockManagerImage;

/**
 * BlockManagerImageTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerImageTest extends BlockManagerContainerBase
{
    public function testDefaultValue()
    {
        $expectedValue = array('Content' =>
            '
                {
                    "0" : {
                        "src": "",
                        "data_src": "holder.js/260x180",
                        "title" : "image_block_title_attribute",
                        "alt" : "image_block_alt_attribute"
                    }
                }
            '
        );
        
        $this->translate("image_block_title_attribute", 0);
        $this->translate("image_block_alt_attribute", 1);
        
        $this->initContainer();  
        $blockManager = new BlockManagerImage($this->container, $this->validator);
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }

    public function testHtmlViewOutput()
    {   
        $this->initContainer();      
        $this->initRequest(); 
        $blockManager = new BlockManagerImage($this->container, $this->validator);
        
        $value =
        '
            {
                "0" : {
                    "src": "",
                    "data_src": "holder.js/260x180",
                    "title" : "Sample title",
                    "alt" : "Sample alt"
                }
            }
        ';
        $block = $this->initBlock($value);
        $blockManager->set($block);        
        $expectedResult = array('RenderView' => array(
            'view' => 'RedKiteCmsBaseBlocksBundle:Content:Image/image.html.twig',
            'options' => array(
                'image' => array(
                    'src' => '',
                    'data_src' => 'holder.js/260x180',
                    'title' => 'Sample title',
                    'alt' => 'Sample alt',
                ),
                'block_manager' => $blockManager,
                'folder' => '',
            ),
        ));
        
        $this->assertEquals($expectedResult, $blockManager->getHtml());
    }
    
    public function testEditorParameters()
    {
        $value =
        '
            {
                "0" : {
                    "src": "",
                    "data_src": "holder.js/260x180",
                    "title" : "Sample title",
                    "alt" : "Sample alt"
                }
            }
        ';

        $block = $this->initBlock($value);
        $formType = $this->getMock('Symfony\Component\Form\FormTypeInterface');
        $this->container->expects($this->at(3))
                        ->method('get')
                        ->with('image.form')
                        ->will($this->returnValue($formType))
        ;
        
        $form = $this->getMockBuilder('Symfony\Component\Form\Form')
                    ->disableOriginalConstructor()
                    ->getMock();
        $form->expects($this->once())
            ->method('createView')
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
        
        $this->initContainer();
        $blockManager = new BlockManagerImage($this->container, $this->validator);
        $blockManager->set($block);
        $blockManager->editorParameters();        
    }

    private function initRequest()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->container->expects($this->at(3))
                        ->method('get')
                        ->with('request')
                        ->will($this->returnValue($request));
    }

    private function initBlock($value)
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block->expects($this->once())
              ->method('getContent')
              ->will($this->returnValue($value));

        return $block;
    }
    
    private function translate($message, $at)
    {
        $this->translator
            ->expects($this->at($at))
            ->method('translate')
            ->with($message)                
            ->will($this->returnValue($message));
    }
}
