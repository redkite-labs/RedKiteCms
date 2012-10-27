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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Event\EventsHandler;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\EventsHandler\AlContentEventsHandler;

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
 * @author alphalemon <webmaster@alphalemon.com>
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