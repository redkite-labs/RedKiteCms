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
use RedKiteLabs\RedKiteCmsBundle\Core\Event\Actions\Block\BlockEditorRenderedEvent;

/**
 * BlockEditorRenderedEvent
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class BlockEditorRenderedEventTest extends TestCase
{
    private $blockManager;
    private $response;
    private $event;

    protected function setUp()
    {
        parent::setUp();

        $this->response = $this->getMock('Symfony\Component\HttpFoundation\Response');
        $this->blockManager = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface');

        $this->event = new BlockEditorRenderedEvent($this->response, $this->blockManager);
    }

    public function testBlockManager()
    {
        $this->assertSame($this->blockManager, $this->event->getBlockManager());
        $blockManager = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface');
        $this->event->setBlockManager($blockManager);
        $this->assertSame($blockManager, $this->event->getBlockManager());
    }

    public function testResponse()
    {
        $this->assertSame($this->response, $this->event->getResponse());
        $response = $this->getMock('Symfony\Component\HttpFoundation\Response');
        $this->event->setResponse($response);
        $this->assertSame($response, $this->event->getResponse());
    }
}

