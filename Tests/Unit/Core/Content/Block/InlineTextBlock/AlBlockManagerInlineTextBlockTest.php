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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\ImagesBlock;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\InlineTextBlock\AlBlockManagerInlineTextBlock;

class AlBlockManagerInlineTextTester extends AlBlockManagerInlineTextBlock
{    
    public function getEditInline()
    {
        return $this->editInline();
    }
}

/**
 * AlBlockManagerInlineTextBlock
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlBlockManagerInlineTextBlockTest extends TestCase
{
    public function testDefaultValue()
    {
        $blockManager = new AlBlockManagerInlineTextTester();
        $this->assertEquals(array("Content" => "This is the default content for a new hypertext block"), $blockManager->getDefaultValue());
    }
    
    public function testDefaultValueIsTranslated()
    {
        $this->translator = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Translator\AlTranslatorInterface');
        $this->translator
            ->expects($this->once())
            ->method('translate')
            ->with("This is the default content for a new hypertext block");
        
        $blockManager = new AlBlockManagerInlineTextTester(null, null, null, $this->translator);
        $blockManager->getDefaultValue();
    }
    
    public function testEditInline()
    {
        $blockManager = new AlBlockManagerInlineTextTester();
        $this->assertTrue($blockManager->getEditInline());
    }
}
