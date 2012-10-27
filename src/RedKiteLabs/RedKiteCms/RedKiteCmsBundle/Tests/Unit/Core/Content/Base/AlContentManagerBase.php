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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Base;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;


/**
 * AlBlockManagerBase
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlContentManagerBase extends TestCase
{
    protected $eventsHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->eventsHandler = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface');
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