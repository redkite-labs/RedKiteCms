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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Block\Thumbnails;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\Base\AlBlockManagerContainerBase;
use RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Thumbnails\AlBlockManagerBootstrapThumbnailsBlock;


/**
 * AlBlockManagerBootstrapThumbnailsBlockTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapThumbnailsBlockTest extends AlBlockManagerContainerBase
{  
    public function testDefaultValue()
    {
        $expectedValue = array(
            "Content" =>    '
            {
                "0" : {
                    "type": "BootstrapThumbnailBlock"
                },
                "1" : {
                    "type": "BootstrapThumbnailBlock"
                }
            }'
        );
            
        $this->initContainer(); 
        $blockManager = new AlBlockManagerBootstrapThumbnailsBlock($this->container, $this->validator);
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }
    
    
    public function testGetHtml()
    {
        $value = '
        {
            "0" : {
                "type": "BootstrapThumbnailBlock"
            },
            "1" : {
                "type": "BootstrapThumbnailBlock"
            }
        }';
            
        $block = $this->initBlock($value);
        $this->initContainer();
        
        $blockManager = new AlBlockManagerBootstrapThumbnailsBlock($this->container, $this->validator);
        $blockManager->set($block);
        
        $expectedResult = array('RenderView' => array(
            'view' => 'TwitterBootstrapBundle:Content:Thumbnails/thumbnails.html.twig',
            'options' => array(
                'values' => array(
                    array(
                        "type" => "BootstrapThumbnailBlock",
                    ),
                    array(
                        "type" => "BootstrapThumbnailBlock",
                    ),
                ),
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
}
