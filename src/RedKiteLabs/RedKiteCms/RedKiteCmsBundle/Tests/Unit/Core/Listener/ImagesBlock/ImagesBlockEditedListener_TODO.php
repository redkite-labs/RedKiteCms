<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Listener\ImagesBlock;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Listener\ImagesBlock\ImagesBlockEditedListener;

/**
 * ImagesBlockEditedListenerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ImagesBlockEditedListenerTest extends TestCase
{
    private $engine;
    private $testListener;

    protected function setUp()
    {
        parent::setUp();

        $this->event = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Actions\Block\BlockEditedEvent')
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->engine = $this->getMock('Symfony\Component\Templating\EngineInterface');
        $this->testListener = new ImagesBlockEditedListener($this->engine);
    }

    public function testResponseIsNotSetWhenTheBlockManagerIsNotInstanceOfBlockManagerImages()
    {
        $this->event->expects($this->never())
            ->method('setResponse');

        $blockManager = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\JsonBlock\BlockManagerJsonBlock')
                             ->disableOriginalConstructor()
                             ->getMock();
        $this->event->expects($this->once())
            ->method('getBlockManager')
            ->will($this->returnValue($blockManager));

        $this->testListener->onBlockEdited($this->event);
    }

    public function testResponseIsNotSetWhenTheBlockManagerIsNotInstanceOfBlockManagerImages1()
    {
        $this->event->expects($this->once())
            ->method('setResponse');

        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
        $block->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));

        $blockManager = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\ImagesBlock\BlockManagerImages')
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
