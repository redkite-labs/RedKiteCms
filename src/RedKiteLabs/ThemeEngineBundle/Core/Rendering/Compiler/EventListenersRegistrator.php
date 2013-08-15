<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 */

namespace RedKiteLabs\ThemeEngineBundle\Core\Rendering\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Register the listeners by their tags
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class EventListenersRegistrator
{
    public static function registerByTaggedServiceId(ContainerBuilder $container, $tagServiceId)
    {
        if (!$container->hasDefinition('event_dispatcher')) {
            return;
        }
        
        $definition = $container->getDefinition('event_dispatcher');
        $registedListenersDefinition = $container->getDefinition('red_kite_labs_theme_engine.registed_listeners');
        foreach ($container->findTaggedServiceIds($tagServiceId) as $id => $events) {
            foreach ($events as $event) {
                $priority = isset($event['priority']) ? $event['priority'] : 0;
                
                if (!isset($event['event'])) {
                    throw new \InvalidArgumentException(sprintf('Service "%s" must define the "event" attribute on "%s" tags.', $id, $tagServiceId));
                }
                
                if (!isset($event['method'])) {
                    $event['method'] = 'on'.preg_replace(array(
                        '/(?<=\b)[a-z]/ie',
                        '/[^a-z0-9]/i'
                    ), array('strtoupper("\\0")', ''), $event['event']);
                }
                
                if ($tagServiceId == 'red_kite_labs_theme_engine.event_listener') {
                    $registedListenersDefinition->addMethodCall('addListenerId', array($id));
                }
                
                $definition->addMethodCall('addListenerService', array($event['event'], array($id, $event['method']), $priority));
            }
        }
    }
}