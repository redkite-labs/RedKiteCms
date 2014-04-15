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

namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Tests\Unit\Core\Block\ButtonsGroup;

use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Tests\Unit\Core\Block\Base\BaseTestBlock;
use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Block\ButtonsGroup\BlockManagerBootstrapButtonsGroupBlock;


/**
 * BlockManagerBootstrapAccordionBlockTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerBootstrapButtonsGroupBlockTest extends BaseTestBlock
{  
    public function testDefaultValue()
    {
        $expectedValue = array(
            array(
                "type" => "BootstrapButtonBlock",
            ),
            array(
                "type" => "BootstrapButtonBlock",
            ),
            array(
                "type" => "BootstrapButtonBlock",
            ),
        );
            
        $this->initContainer(); 
        $blockManager = new BlockManagerBootstrapButtonsGroupBlock($this->container, $this->validator);
        $defaultValue = $blockManager->getDefaultValue();
        $this->assertEquals($expectedValue, json_decode($defaultValue["Content"], true));
    }
    
    public function testGetHtmlReturnsAnEmptyValueWhenAnyBlockIsSet()
    {
        $this->initContainer();
        
        $blockManager = new BlockManagerBootstrapButtonsGroupBlock($this->container, $this->validator);
        $this->assertEmpty($blockManager->getHtml());
    }
    
    public function testGetHtml()
    {
        $value = '
        {
            "0" : {
                "type": "BootstrapButtonBlock"
            },
            "1" : {
                "type": "BootstrapButtonBlock"
            },
            "2" : {
                "type": "BootstrapButtonBlock"
            }
        }';
            
        $block = $this->initBlock($value);
        $this->initContainer();
        
        $blockManager = new BlockManagerBootstrapButtonsGroupBlock($this->container, $this->validator);
        $blockManager->set($block);
        
        $expectedResult = array('RenderView' => array(
            'view' => 'TwitterBootstrapBundle:Content:ButtonsGroup/buttons_group.html.twig',
            'options' => array(
                'buttons' => array(
                    array(
                        "type" => "BootstrapButtonBlock",
                    ),
                    array(
                        "type" => "BootstrapButtonBlock",
                    ),
                    array(
                        "type" => "BootstrapButtonBlock",
                    ),
                ),
                'block_manager' => $blockManager,
            ),
        ));
        
        $this->assertEquals($expectedResult, $blockManager->getHtml());
    }
}
