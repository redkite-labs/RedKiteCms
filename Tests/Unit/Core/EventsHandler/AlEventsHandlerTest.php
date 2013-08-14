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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\EventsHandler;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandler;

class AlEventsHandlerTester extends AlEventsHandler
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
 * AlEventsHandlerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlEventsHandlerTest extends TestCase
{
    private $dispatcher;
    private $eventsHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->eventsHandler = new AlEventsHandlerTester($this->dispatcher);
    }

    public function testGetEventDispatcher()
    {
        $this->assertSame($this->dispatcher, $this->eventsHandler->getEventDispatcher());
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException
     * @expectedExceptionMessage {"message":"\"%className%\" createEvent method requires the eventName argument to be a string","parameters":{"%className%":"AlphaLemon\\AlphaLemonCmsBundle\\Tests\\Unit\\Core\\EventsHandler\\AlEventsHandlerTester"}}
     */
    public function testCreateEventThrowsAnExceptionWhenEventNameIsNotAString()
    {
        $this->eventsHandler->createEvent(array(), 'fake', array());
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException
     * @expectedExceptionMessage {"message":"The class \"%argumentClass%\" passed as argument for the \"%className%\" createEvent method does not exist","parameters":{"%argumentClass%":"AlphaLemon\\AlphaLemonCmsBundle\\Tests\\Unit\\Core\\EventsHandler\\AlEventsHandlerTester","%className%":"AlphaLemon\\AlphaLemonCmsBundle\\Tests\\Unit\\Core\\EventsHandler\\AlEventsHandlerTester"}}
     */
    public function testCreateEventThrowsAnExceptionWhenTypeDoesNotExist()
    {
        $this->eventsHandler->createEvent('an.awesome.event', 'fake', array());
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @expectedExceptionMessage {"message":"The class \"%argumentClass%\" passed as argument for the \"%className%\" createEvent must be an instance of \"Symfony\\Component\\EventDispatcher\\Event\"","parameters":{"%argumentClass%":"AlphaLemon\\AlphaLemonCmsBundle\\Tests\\Unit\\Core\\EventsHandler\\AlEventsHandlerTester","%className%":"AlphaLemon\\AlphaLemonCmsBundle\\Tests\\Unit\\Core\\EventsHandler\\AlEventsHandlerTester"}}
     */
    public function testCreateEventThrowsAnExceptionWhenClassIsNotInherithedByEventClass()
    {
        $this->eventsHandler->createEvent('an.awesome.event', '\RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\EventsHandler\AlEventsHandlerTest', array());
    }

    public function testCreateEventHasInstantiatedANewEvent()
    {
        $class = '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent';
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
        $contentManager = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\AlContentManagerInterface');
        $class = '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent';
        $this->eventsHandler->createEvent('my.awesome.event', $class, array($contentManager));
        $event = $this->eventsHandler->getEvent('my.awesome.event');
        $this->assertInstanceOf($class, $event);
        $this->assertSame($contentManager, $event->getContentManager());
        $this->assertNull($event->getValues());
    }

    public function testCreateEventHasInstantiatedANewEventWithTwoArgument()
    {
        $contentManager = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\AlContentManagerInterface');
        $class = '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent';
        $this->eventsHandler->createEvent('my.awesome.event', $class, array($contentManager, array('foo' => 'bar')));
        $event = $this->eventsHandler->getEvent('my.awesome.event');
        $this->assertInstanceOf($class, $event);
        $this->assertSame($contentManager, $event->getContentManager());
        $this->assertEquals(array('foo' => 'bar'), $event->getValues());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Any event has been found to be dispatched
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

        $contentManager = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\AlContentManagerInterface');
        $class = '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent';
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

        $contentManager = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\AlContentManagerInterface');
        $class = '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent';
        $this->eventsHandler->createEvent('my.awesome.event', $class, array($contentManager, array('foo' => 'bar')));
        $this->eventsHandler->createEvent('my.second.awesome.event', $class, array($contentManager, array('foo' => 'bar')));
        $this->assertSame($this->eventsHandler, $this->eventsHandler->dispatch());
    }
}

