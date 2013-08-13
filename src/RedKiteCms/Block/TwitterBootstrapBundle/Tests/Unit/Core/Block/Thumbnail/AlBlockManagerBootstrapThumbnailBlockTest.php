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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Block\Thumbnail;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\Base\AlBlockManagerContainerBase;
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Thumbnail\AlBlockManagerBootstrapThumbnailBlock;

/**
 * AlBlockManagerBootstrapThumbnailBlockTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapThumbnailBlockTest extends AlBlockManagerContainerBase
{  
    public function testDefaultValue()
    {
        $expectedValue = array(
            "Content" =>    '
            {
                "0" : {
                    "width": "span3"
                }
            }'
        );
            
        $this->initContainer(); 
        $blockManager = new AlBlockManagerBootstrapThumbnailBlock($this->container, $this->validator);
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }
    
    public function testEditorParameters()
    {
        $value = '
            {
                "0" : {
                    "width": "span3"
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
                        ->with('bootstrap_thumbnail.form')
                        ->will($this->returnValue($formType))
        ;
        
        $this->container->expects($this->at(3))
                        ->method('get')
                        ->with('form.factory')
                        ->will($this->returnValue($formFactory))
        ;
        
        $blockManager = new AlBlockManagerBootstrapThumbnailBlock($this->container, $this->validator);
        $blockManager->set($block);
        $result = $blockManager->editorParameters();
        $this->assertEquals('TwitterBootstrapBundle:Editor:Thumbnail/editor.html.twig', $result["template"]);
    }
    
    public function testGetHtml()
    {
        $value = '
        {
            "0" : {
                "width": "span3"
            }
        }';
            
        $block = $this->initBlock($value);
        $this->initContainer();
        
        $blockManager = new AlBlockManagerBootstrapThumbnailBlock($this->container, $this->validator);
        $blockManager->set($block);
        
        $expectedResult = array('RenderView' => array(
            'view' => 'TwitterBootstrapBundle:Content:Thumbnail/thumbnail.html.twig',
            'options' => array(
                'thumbnail' => array(
                    "width" => "span3",
                ),
                'block_manager' => $blockManager,
            ),
        ));
        
        $this->assertEquals($expectedResult, $blockManager->getHtml());
    }
    
    public function testIsInternalBlock()
    {
        
        $blockManager = new AlBlockManagerBootstrapThumbnailBlock($this->container, $this->validator);
        $this->assertTrue($blockManager->getIsInternalBlock());
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
