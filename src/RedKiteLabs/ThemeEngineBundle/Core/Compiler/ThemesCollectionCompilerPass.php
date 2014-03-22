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
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 */

namespace RedKiteLabs\ThemeEngineBundle\Core\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Register the themes by their tags
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ThemesCollectionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('red_kite_labs_theme_engine.themes')) {
            return;
        }

        $definition = $container->getDefinition('red_kite_labs_theme_engine.themes');
        foreach ($container->findTaggedServiceIds('red_kite_labs_theme_engine.themes.theme') as $id => $attributes) {
            $definition->addMethodCall('addTheme', array(new Reference($id)));
            $themeSlotsId = $id . '_slots';
            if ($container->hasDefinition($themeSlotsId)) {
                $themeSlotsDefinition = $container->getDefinition($themeSlotsId);
                $templateSlotsTag = $id . '.template.slot';
                foreach ($container->findTaggedServiceIds($templateSlotsTag) as $slotId => $templateSlotsAttributes) {
                    foreach ($templateSlotsAttributes as $templateSlotsTagAttributes) {
                        $themeSlotsDefinition->addMethodCall('addSlot', array(new Reference($slotId)));
                    }
                }
            }
            
            $templateDefinition = $container->getDefinition($id);
            $templateId = $id . '.template';
            foreach ($container->findTaggedServiceIds($templateId) as $id => $templateAttributes) {
                foreach ($templateAttributes as $templateTagAttributes) {
                    $templateDefinition->addMethodCall('addTemplate', array(new Reference($id)));
                }
            }
        }
    }
}
