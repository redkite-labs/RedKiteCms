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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\ImagesBlock;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\ImagesBlock\ImagesBlockEditedListener;

/**
 * ImagesBlockEditedListenerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class ImagesBlockEditedListenerTest extends TestCase
{
    private $engine;
    private $testListener;

    protected function setUp()
    {
        parent::setUp();

        $this->event = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Event\Actions\Block\BlockEditedEvent')
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->engine = $this->getMock('Symfony\Component\Templating\EngineInterface');
        $this->testListener = new ImagesBlockEditedListener($this->engine);
    }

    public function testResponseIsNotSetWhenTheBlockManagerIsNotInstanceOfAlBlockManagerImages()
    {
        $this->event->expects($this->never())
            ->method('setResponse');

        $blockManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlock')
                             ->disableOriginalConstructor()
                             ->getMock();
        $this->event->expects($this->once())
            ->method('getBlockManager')
            ->will($this->returnValue($blockManager));

        $this->testListener->onBlockEdited($this->event);
    }

    public function testResponseIsNotSetWhenTheBlockManagerIsNotInstanceOfAlBlockManagerImages1()
    {
        $this->event->expects($this->once())
            ->method('setResponse');

        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));

        $blockManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\ImagesBlock\AlBlockManagerImages')
                             ->disableOriginalConstructor()
                             ->getMock();
        $blockManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($block));

        $blockManager->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue(array()));

        $this->event->expects($this->once())
            ->method('getBlockManager')
            ->will($this->returnValue($blockManager));

        $this->engine->expects($this->exactly(2))
            ->method('render')
            ->will($this->returnValue('template rendered'));

        $this->testListener->onBlockEdited($this->event);
    }
}
