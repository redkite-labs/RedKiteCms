<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\Block\ImageBundle\Tests\Unit\Core\Block;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\Base\AlBlockManagerContainerBase;
use AlphaLemon\Block\ImageBundle\Core\Block\AlBlockManagerImage;

/**
 * AlBlockManagerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlBlockManagerImageTest extends AlBlockManagerContainerBase
{
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->initContainer();
        $this->blockManager = new AlBlockManagerImage($this->container, $this->validator);
    }

    public function testDefaultValue()
    {
        $expectedValue = array('Content' =>
            '
                {
                    "0" : {
                        "src": "",
                        "data_src": "holder.js/260x180",
                        "title" : "Sample title",
                        "alt" : "Sample alt"
                    }
                }
            '
        );
        
        $this->translate("Sample title", 0);
        $this->translate("Sample alt", 1);
        $this->assertEquals($expectedValue, $this->blockManager->getDefaultValue());
    }

    public function testHtmlViewOutput()
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
        $this->blockManager->set($block);        
        $expectedResult = array('RenderView' => array(
            'view' => 'ImageBundle:Image:image.html.twig',
            'options' => array(
                'image' => array(
                    'src' => '',
                    'data_src' => 'holder.js/260x180',
                    'title' => 'Sample title',
                    'alt' => 'Sample alt',
                ),
                'block_manager' => $this->blockManager
            ),
        ));
        
        $this->assertEquals($expectedResult, $this->blockManager->getHtml());
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
        $this->container->expects($this->at(4))
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
        
        $this->container->expects($this->at(5))
                        ->method('get')
                        ->with('form.factory')
                        ->will($this->returnValue($formFactory))
        ;
        
        $this->initContainer();
        $blockManager = new AlBlockManagerImage($this->container, $this->validator);
        $blockManager->set($block);
        $blockManager->editorParameters();        
    }
    
    protected function initContainer()
    {
        parent::initContainer();
        
        $this->translator = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Translator\AlTranslatorInterface');
        $this->container
            ->expects($this->at(2))
            ->method('get')
            ->with('alpha_lemon_cms.translator')
            ->will($this->returnValue($this->translator))
        ;
        
        $this->configuration = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Configuration\AlConfigurationInterface');
        $this->container
            ->expects($this->at(3))
            ->method('get')
            ->with('alpha_lemon_cms.configuration')
            ->will($this->returnValue($this->configuration))
        ;
    }

    private function initBlock($value)
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
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
