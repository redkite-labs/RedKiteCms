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
 
namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Tests\Unit\Core\Block\Navbar;

use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Tests\Unit\Core\Block\Base\BaseTestBlock;
use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Block\Navbar\BlockManagerBootstrapNavbarFormBlock;

/**
 * BlockManagerBootstrapNavbarFormBlockTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerBootstrapNavbarFormBlockTest extends BaseTestBlock
{  
    protected function setUp()
    {
        parent::setUp();

        $this->activeTheme = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ActiveTheme\ActiveThemeInterface');
    }
    
    /**
     * @dataProvider bootstrapVersionsProvider
     */
    public function testDefaultValue($bootstrapVersion, $alignment)
    {
        $this->initContainer($bootstrapVersion); 
        
        $expectedValue = array(
            "Content" =>    
            '
            {
                "0" : {
                    "method": "POST",
                    "action": "#",
                    "enctype": "",
                    "placeholder": "Search",
                    "role": "Search",
                    "button_text": "Go",
                    "alignment": "' . $alignment . '"
                }
            }
        ',
        );
        
        $blockManager = new BlockManagerBootstrapNavbarFormBlock($this->container, $this->validator);
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }
    
    /**
     * @dataProvider bootstrapVersionsProvider
     */
    public function testGetHtml($bootstrapVersion, $alignment)
    {
        $value = '{
            "0": {
                "method": "POST",
                "action": "#",
                "enctype": "",
                "placeholder": "Search",
                "role": "Search",
                "button_text": "Go",
                "alignment": "' . $alignment . '"
            }
        }';
        
        $block = $this->initBlock($value);
        $this->initContainer($bootstrapVersion);
        
        $blockManager = new BlockManagerBootstrapNavbarFormBlock($this->container, $this->validator);
        $blockManager->set($block);
        
        $expectedResult = array('RenderView' => array(
            'view' => 'TwitterBootstrapBundle:Content:Navbar/Form/' . $bootstrapVersion . '/navbar_form.html.twig',
            'options' => array(
                'data' => array(
                   'method' => 'POST',
                    'action' => '#',
                    'enctype' => '',
                    'placeholder' => 'Search',
                    'role' => 'Search',
                    'button_text' => 'Go',
                    'alignment' => $alignment,
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
                    "method": "POST",
                    "action": "#",
                    "enctype": "",
                    "placeholder": "Search",
                    "role": "Search",
                    "button_text": "Go"
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
                    ->with('Navbar\Form', 'NavbarFormType')
                    ->will($this->returnValue($form))
        ;
        $this->container->expects($this->at(4))
                        ->method('get')
                        ->with('twitter_bootstrap.bootstrap_form_factory')
                        ->will($this->returnValue($formFactory))
        ;
        
        $blockManager = new BlockManagerBootstrapNavbarFormBlock($this->container, $this->validator);
        $blockManager->set($block);
        $result = $blockManager->editorParameters();
        $this->assertEquals('TwitterBootstrapBundle:Editor:Navbar/Form/navbar_form_editor.html.twig', $result["template"]);
    }
    
    public function bootstrapVersionsProvider()
    {
        return array(
            array(
                "2.x",
                'pull-left'
            ),
            array(
                "3.x",
                "navbar-left",
            ),
        );
    }
    
    protected function initContainer($bootstrapVersion = null)
    {
        parent::initContainer();
        
        $this->container->expects($this->at(3))
                        ->method('get')
                        ->with('red_kite_cms.active_theme')
                        ->will($this->returnValue($this->activeTheme));
        
        if (null === $bootstrapVersion) {
            return;
        }
        
        $this->activeTheme
            ->expects($this->once())
            ->method('getThemeBootstrapVersion')
            ->will($this->returnValue($bootstrapVersion));
    }
}