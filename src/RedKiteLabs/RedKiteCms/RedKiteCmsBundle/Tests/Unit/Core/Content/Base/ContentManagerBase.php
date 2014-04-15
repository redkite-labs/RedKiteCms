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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Base;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;


/**
 * ContentManagerBase
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class ContentManagerBase extends TestCase
{
    protected $eventsHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->eventsHandler = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\EventsHandler\EventsHandlerInterface');
    }

    protected function setUpEventsHandler($event, $times = 1)
    {
        $this->eventsHandler->expects($this->exactly($times))
             ->method('createEvent')
             ->will($this->returnSelf());

        $this->eventsHandler->expects($this->exactly($times))
            ->method('dispatch')
             ->will($this->returnSelf());

        if (null !== $event) {
            $this->eventsHandler->expects($this->once())
                            ->method('getEvent')
                            ->will($this->returnValue($event));
        }
    }
}