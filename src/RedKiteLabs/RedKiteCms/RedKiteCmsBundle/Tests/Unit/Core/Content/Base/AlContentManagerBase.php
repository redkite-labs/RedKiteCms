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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Base;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;


/**
 * AlBlockManagerBase
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
abstract class AlContentManagerBase extends TestCase
{
    protected $eventsHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->eventsHandler = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface');
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