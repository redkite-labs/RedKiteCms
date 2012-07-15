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
use AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText;

/**
 * AlBlockManagerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerTextTest extends TestCase
{
    public function testDefaultValue()
    {
        $dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $blockManager = new AlBlockManagerText($dispatcher);

        $expectedValue = array("HtmlContent" => 'This is the default text for a new text content');
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }
}