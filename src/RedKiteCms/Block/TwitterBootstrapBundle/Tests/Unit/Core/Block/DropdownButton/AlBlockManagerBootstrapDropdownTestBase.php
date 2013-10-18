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
 
namespace RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Block\DropdownButton;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\Base\AlBlockManagerContainerBase;
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\DropdownButton\AlBlockManagerBootstrapDropdownButtonBlock;

class AlBlockManagerBootstrapDropdownButtonBlockTester extends AlBlockManagerBootstrapDropdownButtonBlock
{
    public function saveDropdownItemsTester($values)
    {
        return $this->saveDropdownItems($values);
    }
}

/**
 * AlBlockManagerBootstrapDropdownButtonBlockTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
abstract class AlBlockManagerBootstrapDropdownTestBase extends AlBlockManagerContainerBase
{  
    abstract protected function getBlockManager();
    
    protected function setUp()
    {
        parent::setUp();

        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');

        $this->validator = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $seoRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlSeoRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($seoRepository));

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
    }
    
    protected function defaultValueTest($expectedValue)
    {    
        $this->initContainer(); 
        $blockManager = $this->getBlockManager();
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }
    
    public function getHtmlTest($value, $items, $template)
    {
        $block = $this->initBlock($value);
        $this->initContainer();
        
        $blockManager = $this->getBlockManager();
        $blockManager->set($block);
        
        $expectedResult = array('RenderView' => array(
            'view' => $template,
            'options' => array(
                'data' => array(
                    'button_text' => 'Dropdown Button 1',
                    'button_type' => 'danger',
                    'button_attribute' => 'large',
                    'button_gropup' => 'none',
                    'items' => $items,
                ),
                'block_manager' => $blockManager,
            ),
        ));
        
        $this->assertEquals($expectedResult, $blockManager->getHtml());
    }
    
    public function editorParametersTest($value, $template)
    {
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
                    ->with('DropdownButton', 'AlDropdownButtonType')
                    ->will($this->returnValue($form))
        ;
        $this->container->expects($this->at(2))
                        ->method('get')
                        ->with('twitter_bootstrap.bootstrap_form_factory')
                        ->will($this->returnValue($formFactory))
        ;

        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $this->container->expects($this->at(3))
                        ->method('get')
                        ->with('request')
                        ->will($this->returnValue($request))
        ;
        
        $blockManager = $this->getBlockManager();
        $blockManager->set($block);
        $result = $blockManager->editorParameters();
        $this->assertEquals($template, $result["template"]);
    }
    
    
    public function testManageJsonCollection()
    {
        $value = '{
            "0": {
                "button_text": "Dropdown Button 1",
                "button_type": "danger",
                "button_attribute": "large",
                "button_gropup" : "none",
                "items": [
                    {
                        "data" : "Item 1", 
                        "metadata" : {  
                            "type": "link",
                            "href": "#"
                        }
                    }
                ]
            }
        }';
        
        $values["Content"] = 'al_json_block[button_text]=Dropdown Button 1&al_json_block[button_type]=btn-default&al_json_block[button_attribute]=&al_json_block[button_block]=&al_json_block[button_enabled]=&al_json_block[button_href]=&dropdown_items_form[0][metadata][type]=link&dropdown_items_form[0][data]=Item 1&dropdown_items_form[0][metadata][href]=#&dropdown_items_form[1][metadata][type]=link&dropdown_items_form[1][data]=Item 21&dropdown_items_form[1][metadata][href]=#&dropdown_items_form[2][metadata][type]=link&dropdown_items_form[2][data]=Item 3&dropdown_items_form[2][metadata][href]=#';
             
        $blockManager = new AlBlockManagerBootstrapDropdownButtonBlockTester($this->container, $this->validator);
        $block = $this->initBlock($value);        
        $blockManager->set($block); 
        
        $result = $blockManager->saveDropdownItemsTester($values);
        $expectedResult = array('Content' => '[{"button_text":"Dropdown Button 1","button_type":"btn-default","button_attribute":"","button_block":"","button_enabled":"","button_href":"","items":[{"metadata":{"type":"link","href":"#"},"data":"Item 1"},{"metadata":{"type":"link","href":"#"},"data":"Item 21"},{"metadata":{"type":"link","href":"#"},"data":"Item 3"}]}]');
        $this->assertEquals($expectedResult, $result);
    }
    
    protected function initBlock($value)
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->any())
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
