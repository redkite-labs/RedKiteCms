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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Block\Label;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\Base\AlBlockManagerContainerBase;

/**
 * AlBlockManagerTestBase
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
abstract class AlBlockManagerLabelTestBase extends AlBlockManagerContainerBase
{    
    protected abstract function getBlockManager();
    
    protected function defaultValue($expectedValue)
    {
        $this->initContainer(); 
        $blockManager = $this->getBlockManager();
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }
    
    protected function editorParameters($value, $formServiceName)
    {
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
                        ->with($formServiceName)
                        ->will($this->returnValue($formType))
        ;
        
        $this->container->expects($this->at(3))
                        ->method('get')
                        ->with('form.factory')
                        ->will($this->returnValue($formFactory))
        ;
        
        $blockManager = $this->getBlockManager();
        $blockManager->set($block);
        $blockManager->editorParameters();
    }
    
    protected function getHtml($value, $contentView, $expectedData)
    {
        $block = $this->initBlock($value);
        $this->initContainer();
        
        $blockManager = $this->getBlockManager();
        $blockManager->set($block);
        
        $expectedResult = array('RenderView' => array(
            'view' => $contentView,
            'options' => array(
                'data' => $expectedData,
                'block_manager' => $blockManager,
            ),
        ));
        
        $this->assertEquals($expectedResult, $blockManager->getHtml());
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
