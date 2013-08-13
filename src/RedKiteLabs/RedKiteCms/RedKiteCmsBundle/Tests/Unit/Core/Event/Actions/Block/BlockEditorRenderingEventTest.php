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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Event\Actions\Block;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\Event\Actions\Block\BlockEditorRenderingEvent;

/**
 * BlockEditorEventTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class BlockEditorRenderingEventTest extends TestCase
{
    private $request;
    private $blockManager;
    private $response;
    private $event;

    protected function setUp()
    {
        parent::setUp();

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $this->blockManager = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface');

        $this->event = new BlockEditorRenderingEvent($this->container, $this->request, $this->blockManager);
    }

    public function testContainer()
    {
        $this->assertSame($this->container, $this->event->getContainer());
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->event->setContainer($container);
        $this->assertSame($container, $this->event->getContainer());
    }

    public function testRequest()
    {
        $this->assertSame($this->request, $this->event->getRequest());
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $this->event->setRequest($request);
        $this->assertSame($request, $this->event->getRequest());
    }

    public function testBlockManager()
    {
        $this->assertSame($this->blockManager, $this->event->getBlockManager());
        $blockManager = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface');
        $this->event->setBlockManager($blockManager);
        $this->assertSame($blockManager, $this->event->getBlockManager());
    }
}

