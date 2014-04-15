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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Event\Content\Base;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Base\BaseActionEvent;

class BaseActionEventTester extends BaseActionEvent
{
}

/**
 * BaseActionEventTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BaseActionEventTest extends TestCase
{
    private $blockManager;

    protected function setUp()
    {
        parent::setUp();

        $this->blockManager = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\ContentManagerInterface');

        $this->event = new BaseActionEventTester($this->blockManager);
    }

    public function testContentManager()
    {
        $this->assertSame($this->blockManager, $this->event->getContentManager());
        $blockManager = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\ContentManagerInterface');
        $this->event->setContentManager($blockManager);
        $this->assertSame($blockManager, $this->event->getContentManager());
    }
}

