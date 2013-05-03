<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block\ImagesBlock;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\InlineTextBlock\AlBlockManagerInlineTextBlock;

class AlBlockManagerInlineTextTester extends AlBlockManagerInlineTextBlock
{
    public function getDefaultValue()
    {
        return "my value";
    }
    
    public function getEditInline()
    {
        return $this->editInline();
    }
}

/**
 * AlBlockManagerInlineTextBlock
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerInlineTextBlockTest extends TestCase
{
    public function testAnExceptionIsThrownWhenTheSavedJsonContentIsNotDecodable()
    {
        $blockManager = new AlBlockManagerInlineTextTester();
        $this->assertTrue($blockManager->getEditInline());
    }
}
