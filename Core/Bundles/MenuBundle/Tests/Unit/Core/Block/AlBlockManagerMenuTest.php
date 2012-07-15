<?php
/*
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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\MenuBundle\Core\Block\AlBlockManagerMenu;

/**
 * AlBlockManagerMenuTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerMenuTest extends TestCase
{
    public function testDefaultValue()
    {
        $dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $blockManager = new AlBlockManagerMenu($dispatcher);

        $expectedValue = array("HtmlContent" => "<ul><li>Link 1</li><li>Link 2</li><li>Link 3</li></ul>");
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }
}