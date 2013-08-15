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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Register the available blocks by their tags
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlBlocksCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('red_kite_cms.block_manager_factory')) {
            return;
        }

        $definition = $container->getDefinition('red_kite_cms.block_manager_factory');
        foreach ($container->findTaggedServiceIds('alphalemon_cms.blocks_factory.block') as $id => $attributes) {
            foreach ($attributes as $tagAttributes) {
                $tagAttributes['id'] = $id;
                $definition->addMethodCall('addBlockManager', array(new Reference($id), $tagAttributes));
            }
        }
    }
}
