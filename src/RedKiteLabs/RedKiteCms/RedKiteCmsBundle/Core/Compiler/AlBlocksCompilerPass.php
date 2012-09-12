<?php
/*
 * This file is part of the AlphaLemonPageTreeBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 *
 * @license    MIT License
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Register the available blocks by their tags
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlocksCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('alpha_lemon_cms.block_manager_factory')) {
            return;
        }

        $definition = $container->getDefinition('alpha_lemon_cms.block_manager_factory');
        foreach ($container->findTaggedServiceIds('alphalemon_cms.blocks_factory.block') as $id => $attributes) {
            foreach ($attributes as $tagAttributes) {
                $tagAttributes['id'] = $id;
                $definition->addMethodCall('addBlockManager', array(new Reference($id), $tagAttributes));
            }
        }
    }
}
