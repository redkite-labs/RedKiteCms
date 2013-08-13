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
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Listener\JsonBlock;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;

/**
 * RenderingListEditorListenerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class BaseTestRenderingEditorListener extends TestCase
{
    protected $testListener;
    protected $event;
    protected $container;
    protected $engine;
    protected $blockManager;
    protected $block;

    protected function setUp()
    {
        parent::setUp();

        $this->event = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Event\Actions\Block\BlockEditorRenderingEvent')
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->engine = $this->getMock('Symfony\Component\Templating\EngineInterface');
        $this->blockManager = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManager')
                            ->disableOriginalConstructor()
                            ->getMock();
    }

    protected function setUpEvents($expectedCalls = 1)
    {
        $this->event->expects($this->exactly($expectedCalls))
            ->method('getBlockManager')
            ->will($this->returnValue($this->blockManager));

        $this->event->expects($this->exactly($expectedCalls))
            ->method('getContainer')
            ->will($this->returnValue($this->container));
    }

    protected function setUpBlockManager()
    {
        $value = '{
                "0" : {
                    "field1" : "value1",
                    "field2" : "value2"
                }
            }';

        $this->block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');

        $this->block->expects($this->once())
            ->method('getType')
            ->will($this->returnValue('Class'));

        $this->block->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($value));

        $this->blockManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->block));
    }
}
