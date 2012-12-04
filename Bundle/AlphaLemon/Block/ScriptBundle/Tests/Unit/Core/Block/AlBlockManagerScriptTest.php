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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\Block\ScriptBundle\Core\Block\AlBlockManagerScript;

/**
 * AlBlockManagerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerScriptTest extends TestCase
{
    protected function setUp()
    {
        $factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $eventsHandler = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface');
        $this->blockManager = new AlBlockManagerScript($eventsHandler, $factoryRepository);
    }

    public function testDefaultValue()
    {
        $expectedValue = array(
            'Content' => '',
            'InternalJavascript' => '',
            'ExternalJavascript' => ''
        );
        $this->assertEquals($expectedValue, $this->blockManager->getDefaultValue());
    }

    public function testContentDisplaysTheContentWhenAnyJavascriptTagExists()
    {
        $htmlContent = 'A fancy javascript';
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($htmlContent));
        $this->blockManager->set($block);        
        $blockManagerArray = $this->blockManager->toArray();
        $this->assertEquals($htmlContent, $blockManagerArray['Content']);
    }

    public function testHideInEditMode()
    {
        $this->assertTrue($this->blockManager->getHideInEditMode());
    }

    public function testReloadSuggested()
    {
        $this->assertTrue($this->blockManager->getReloadSuggested());
    }

    private function initBlock($htmlContent)
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($htmlContent));

        $block->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));

        return $block;
    }

}
