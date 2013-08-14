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
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Event\EventsHandler;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\EventsHandler\AlContentEventsHandler;

class AlContentEventsHandlerTester extends AlContentEventsHandler
{
    public function getConfiguredMethods()
    {
        return $this->configureMethods();
    }
}

/**
 * AlContentEventsHandlerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlContentEventsHandlerTest extends TestCase
{
    public function testGetEventDispatcher()
    {
        $dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $eventsHandler = new AlContentEventsHandlerTester($dispatcher);

        $expectedMethods = array(
            "setContentManager",
            "setValues",
        );
        $this->assertEquals($expectedMethods, $eventsHandler->getConfiguredMethods());
    }
}