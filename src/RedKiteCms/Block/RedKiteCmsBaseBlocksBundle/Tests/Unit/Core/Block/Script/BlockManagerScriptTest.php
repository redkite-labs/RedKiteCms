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

namespace RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Tests\Unit\Core\Block\Script;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\Base\BlockManagerContainerBase;
use RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Script\BlockManagerScript;

/**
 * BlockManagerScriptTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerScriptTest extends BlockManagerContainerBase
{
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->initContainer();
        
        $this->blockManager = new BlockManagerScript($this->container, $this->validator);
    }

    public function testDefaultValue()
    {
        $message = 'script_block_default_content';
        $this->translator
            ->expects($this->once())
            ->method('translate')
            ->with($message)                
            ->will($this->returnValue($message));
            
        $expectedValue = array(
            'Content' => $message,
        );
        $this->assertEquals($expectedValue, $this->blockManager->getDefaultValue());
    }

    public function testHtmlViewOutput()
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $this->blockManager->set($block);        
        $expectedResult = array('RenderView' => array(
            'view' => 'RedKiteCmsBaseBlocksBundle:Content:Script/script.html.twig',
            'options' => array(
                'block_manager' => $this->blockManager
            ),
        ));
        
        $this->assertEquals($expectedResult, $this->blockManager->getHtml());
    }
    
    public function testEditorParameters()
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block->expects($this->once())
              ->method('getExternalJavascript')
              ->will($this->returnValue('javascript-1.js,javascript-2.js'))
        ;
        $block->expects($this->once())
              ->method('getExternalStylesheet')
              ->will($this->returnValue('stylesheet-1.js,stylesheet-2.js'))
        ;
        $this->blockManager->set($block);        
        
        $formType = $this->getMock('Symfony\Component\Form\FormTypeInterface');
        $this->container->expects($this->at(0))
                        ->method('get')
                        ->with('script.form')
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
        $this->container->expects($this->at(1))
                        ->method('get')
                        ->with('form.factory')
                        ->will($this->returnValue($formFactory))
        ;
        
        $this->translator
            ->expects($this->once())
            ->method('translate')
            ->with('script_block_editor_title')                
            ->will($this->returnValue('Script editor'));
        
        $expectedResult = array(
            "template" => "RedKiteCmsBaseBlocksBundle:Editor:Script/editor.html.twig",
            "title" => "Script editor",
            "blockManager" => $this->blockManager,
            'form' => 'the-form',
            "jsFiles" => array(
                "javascript-1.js",
                "javascript-2.js",
            ),
            "cssFiles" => array(
                "stylesheet-1.js",
                "stylesheet-2.js",
            ),
        );
        
        $this->assertEquals($expectedResult, $this->blockManager->editorParameters());
    }

    public function testHideInEditMode()
    {   
        $this->assertTrue($this->blockManager->getHideInEditMode());
    }
}
