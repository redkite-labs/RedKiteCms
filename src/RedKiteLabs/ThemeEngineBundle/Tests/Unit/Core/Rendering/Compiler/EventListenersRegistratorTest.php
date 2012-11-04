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

namespace AlphaLemon\ThemeEngineBundle\Tests\Unit\Core\Rendering\Compiler;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\Rendering\Compiler\EventListenersRegistrator;

/**
 * EventListenersRegistratorTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class EventListenersRegistratorTest extends TestCase
{
    public function testEventsDispatcherDefinitionDoesNotExist()
    {
        $definition = $this->getMock('Symfony\Component\DependencyInjection\Definition');
        $definition->expects($this->never())
            ->method('addMethodCall');
        
        $builder = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $builder->expects($this->any())
            ->method('hasDefinition')
            ->will($this->returnValue(false));
        
        EventListenersRegistrator::registerByTaggedServiceId($builder, 'alcms.event');
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Service "my_event_subscriber" must define the "event" attribute on "alcms.event" tags.
     */
    public function testAnExceptionIsThrownWhenEventOptionIsNotProvided()
    {
        $definition = $this->getMock('Symfony\Component\DependencyInjection\Definition');
        
        $builder = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $builder->expects($this->any())
            ->method('hasDefinition')
            ->will($this->returnValue(true));
        
        $builder->expects($this->atLeastOnce())
            ->method('getDefinition')
            ->will($this->returnValue($definition));
        
        $services = array(
            'my_event_subscriber' => array(
                0 => array(
                    'method' => 'event.method', 
                    'priority' => '128'
                ),
            ),
        );
                
        $builder->expects($this->atLeastOnce())
            ->method('findTaggedServiceIds')
            ->will($this->returnValue($services));
        
        EventListenersRegistrator::registerByTaggedServiceId($builder, 'alcms.event');
    }
    
    /**
     * @dataProvider eventsSubscriberProvider
     */
    public function testSubscribeEvents($services, $results)
    {
        $definition = $this->getMock('Symfony\Component\DependencyInjection\Definition');
        $definition->expects($this->atLeastOnce())
            ->method('addMethodCall')
            ->with('addListenerService', $results);
        
        $builder = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $builder->expects($this->any())
            ->method('hasDefinition')
            ->will($this->returnValue(true));
        
        $builder->expects($this->atLeastOnce())
            ->method('getDefinition')
            ->will($this->returnValue($definition));
        
        $builder->expects($this->atLeastOnce())
            ->method('findTaggedServiceIds')
            ->will($this->returnValue($services));
        
        EventListenersRegistrator::registerByTaggedServiceId($builder, 'alcms.event');
    }
    
    public function eventsSubscriberProvider()
    {
        return array(
            array(
                array(
                    'my_event_subscriber' => array(
                        0 => array(
                            'event' => 'event.name', 
                            'method' => 'event.method', 
                            'priority' => '128'
                        ),
                    ),
                ),
                array(
                    'event.name', 
                    array('my_event_subscriber', 'event.method'), '128'
                ),
            ),
            array(
                array(
                    'my_event_subscriber' => array(
                        1 => array(
                            'event' => 'event.name', 
                            'priority' => '-128'
                        ),
                    ),
                ),
                array(
                    'event.name', 
                    array('my_event_subscriber', 'onEventName'), '-128'
                ),
            ),
        );
    }
}
