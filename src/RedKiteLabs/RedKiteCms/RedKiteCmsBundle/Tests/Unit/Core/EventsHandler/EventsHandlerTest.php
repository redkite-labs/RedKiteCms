<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\EventsHandler;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\EventsHandler\EventsHandler;

class EventsHandlerTester extends EventsHandler
{
    protected function configureMethods()
    {
        return array(
            "setContentManager",
            "setValues",
        );
    }
}

/**
 * EventsHandlerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class EventsHandlerTest extends TestCase
{
    private $dispatcher;
    private $eventsHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->eventsHandler = new EventsHandlerTester($this->dispatcher);
    }

    public function testGetEventDispatcher()
    {
        $this->assertSame($this->dispatcher, $this->eventsHandler->getEventDispatcher());
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException
     * @expectedExceptionMessage {"message":"\"exception_invalid_argument_provided_for_event_name","parameters":{"%className%":"RedKiteLabs\\RedKiteCms\\RedKiteCmsBundle\\Tests\\Unit\\Core\\EventsHandler\\EventsHandlerTester"}}
     */
    public function testCreateEventThrowsAnExceptionWhenEventNameIsNotAString()
    {
        $this->eventsHandler->createEvent(array(), 'fake', array());
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException
     * @expectedExceptionMessage {"message":"exception_invalid_class_name_for_createEvent","parameters":{"%argumentClass%":"RedKiteLabs\\RedKiteCms\\RedKiteCmsBundle\\Tests\\Unit\\Core\\EventsHandler\\EventsHandlerTester","%className%":"RedKiteLabs\\RedKiteCms\\RedKiteCmsBundle\\Tests\\Unit\\Core\\EventsHandler\\EventsHandlerTester"}}
     */
    public function testCreateEventThrowsAnExceptionWhenTypeDoesNotExist()
    {
        $this->eventsHandler->createEvent('an.awesome.event', 'fake', array());
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @expectedExceptionMessage {"message":"exception_invalid_class_instance_for_createEvent","parameters":{"%argumentClass%":"RedKiteLabs\\RedKiteCms\\RedKiteCmsBundle\\Tests\\Unit\\Core\\EventsHandler\\EventsHandlerTester","%className%":"RedKiteLabs\\RedKiteCms\\RedKiteCmsBundle\\Tests\\Unit\\Core\\EventsHandler\\EventsHandlerTester"}}
     */
    public function testCreateEventThrowsAnExceptionWhenClassIsNotInherithedByEventClass()
    {
        $this->eventsHandler->createEvent('an.awesome.event', '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\EventsHandler\EventsHandlerTest', array());
    }

    public function testCreateEventHasInstantiatedANewEvent()
    {
        $class = '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent';
        $this->assertCount(0, $this->eventsHandler->getEvents());
        $this->eventsHandler->createEvent('my.awesome.event', $class, array());
        $this->assertCount(1, $this->eventsHandler->getEvents());
        $event = $this->eventsHandler->getEvent('my.awesome.event');
        $this->assertInstanceOf($class, $event);
        $this->assertNull($event->getContentManager());
        $this->assertNull($event->getValues());
    }

    public function testCreateEventHasInstantiatedANewEventWithOneArgument()
    {
        $contentManager = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\ContentManagerInterface');
        $class = '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent';
        $this->eventsHandler->createEvent('my.awesome.event', $class, array($contentManager));
        $event = $this->eventsHandler->getEvent('my.awesome.event');
        $this->assertInstanceOf($class, $event);
        $this->assertSame($contentManager, $event->getContentManager());
        $this->assertNull($event->getValues());
    }

    public function testCreateEventHasInstantiatedANewEventWithTwoArgument()
    {
        $contentManager = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\ContentManagerInterface');
        $class = '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent';
        $this->eventsHandler->createEvent('my.awesome.event', $class, array($contentManager, array('foo' => 'bar')));
        $event = $this->eventsHandler->getEvent('my.awesome.event');
        $this->assertInstanceOf($class, $event);
        $this->assertSame($contentManager, $event->getContentManager());
        $this->assertEquals(array('foo' => 'bar'), $event->getValues());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage exception_no_events
     */
    public function testAnExceptionIsThrownWhenAnyEventHasBeenSet()
    {
        $this->eventsHandler->dispatch('my.awesome.event');
    }

    public function testTheRequestedEventIsDispatched()
    {
        $this->dispatcher
             ->expects($this->once())
             ->method('dispatch')
             ->with('my.awesome.event');

        $contentManager = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\ContentManagerInterface');
        $class = '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent';
        $this->eventsHandler->createEvent('my.awesome.event', $class, array($contentManager, array('foo' => 'bar')));
        $this->eventsHandler->createEvent('my.second.awesome.event', $class, array($contentManager, array('foo' => 'bar')));
        $this->assertSame($this->eventsHandler, $this->eventsHandler->dispatch('my.awesome.event'));
    }

    public function testWhenAnyEventIsPassedTheLastEventIsDispatched()
    {
        $this->dispatcher
             ->expects($this->once())
             ->method('dispatch')
             ->with('my.second.awesome.event');

        $contentManager = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\ContentManagerInterface');
        $class = '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent';
        $this->eventsHandler->createEvent('my.awesome.event', $class, array($contentManager, array('foo' => 'bar')));
        $this->eventsHandler->createEvent('my.second.awesome.event', $class, array($contentManager, array('foo' => 'bar')));
        $this->assertSame($this->eventsHandler, $this->eventsHandler->dispatch());
    }
}

