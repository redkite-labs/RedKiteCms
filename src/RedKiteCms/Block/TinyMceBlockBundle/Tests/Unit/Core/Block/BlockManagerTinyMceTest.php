<?php
/**
 * This file is part of the RedKiteCms CMS Application and it is distributed
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

namespace RedKiteCms\Block\TinyMceBlockBundle\Tests\Unit\Core\Block;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\Base\BlockManagerContainerBase;
use RedKiteCms\Block\TinyMceBlockBundle\Core\Block\BlockManagerTinyMceBlock;

/**
 * BlockManagerTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerTinyMceTest extends BlockManagerContainerBase
{
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->blockManager = new BlockManagerTinyMceBlock();
    }

    public function testRenderHtml()
    {
        $value = 'My awesome hypertext block';
        $block = $this->initBlock($value);
        $this->blockManager->set($block);        
        $expectedResult = array('RenderView' => array(
            'view' => 'TinyMceBlockBundle:Content:tinymce.html.twig',
            'options' => array(
                'id' => 2, 
                'content' => $value, 
                'block_manager' => $this->blockManager
            ),
        ));
        
        $this->assertEquals($expectedResult, $this->blockManager->getHtml());
    }
    
    private function initBlock($value)
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block->expects($this->once())
              ->method('getId')
              ->will($this->returnValue(2));
        
        $block->expects($this->once())
              ->method('getContent')
              ->will($this->returnValue($value));

        return $block;
    }
}
