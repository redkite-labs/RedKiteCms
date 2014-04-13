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

namespace RedKiteLabs\RedKiteCms\MarkdownBlockBundle\Tests\Unit\Core\Block;

use RedKiteLabs\RedKiteCms\MarkdownBlockBundle\Core\Block\BlockManagerMarkdownBlock;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\Base\BlockManagerContainerBase;

/**
 * BlockManagerTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerMarkdownTest extends BlockManagerContainerBase
{
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->blockManager = new BlockManagerMarkdownBlock();
    }

    public function testDefautValue()
    {
        $expectedResult = array(
            'Content' => "markdown_default_content",
        );

        $this->assertEquals($expectedResult, $this->blockManager->getDefaultValue());
    }

    public function testDefautValueTranslated()
    {
        $value = "Some translated text";
        $expectedResult = array(
            'Content' => $value,
        );

        $translator = $this->getMock('\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Translator\TranslatorInterface');
        $translator->expects($this->once())
            ->method('translate')
            ->with("markdown_default_content")
            ->will($this->returnValue($value))
        ;
        $blockManager = new BlockManagerMarkdownBlock(null, null, null, $translator);

        $this->assertEquals($expectedResult, $blockManager->getDefaultValue());
    }

    public function testRenderHtml()
    {
        $value = 'My awesome markdown block';
        $block = $this->initBlock($value);
        $this->blockManager->set($block);        
        $expectedResult = array('RenderView' => array(
            'view' => 'MarkdownBlockBundle:Content:markdown.html.twig',
            'options' => array(
                'block_id' => 2,
                'block_content' => $value,
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