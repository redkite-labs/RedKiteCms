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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Block\Accordion;

use RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Block\Base\BaseTestBlock;
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Accordion\BlockManagerBootstrapAccordionPanelBlock;


/**
 * BlockManagerBootstrapAccordionBlockTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerBootstrapAccordionPanelBlockTest extends BaseTestBlock
{  
    protected function setUp()
    {
        parent::setUp();

        $this->activeTheme = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ActiveTheme\ActiveThemeInterface');
    }
    
    public function testDefaultValue()
    {
        $expectedValue = array(
            "Content" =>    '
            {
                "0" : {
                    "0": "accordion"
                }
            }'
        );
            
        $this->initContainer(); 
        $blockManager = new BlockManagerBootstrapAccordionPanelBlock($this->container, $this->validator);
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }

    /**
     * @dataProvider bootstrapVersionsProvider
     */
    public function testGetHtml($bootstrapVersion)
    {
        $value = '
        {
            "0" : {
                    "0": "item"
                },
                "1" : {
                    "0": "item"
                }
        }';
            
        $block = $this->initBlock($value);
        $this->initContainer($bootstrapVersion);
        
        $blockManager = new BlockManagerBootstrapAccordionPanelBlock($this->container, $this->validator);
        $blockManager->set($block);
        
        $expectedResult = array('RenderView' => array(
            'view' => 'TwitterBootstrapBundle:Content:AccordionPanel/' . $bootstrapVersion . '/accordion_panel.html.twig',
            'options' => array(
                'block_manager' => $blockManager,
            ),
        ));
        
        $this->assertEquals($expectedResult, $blockManager->getHtml());
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
