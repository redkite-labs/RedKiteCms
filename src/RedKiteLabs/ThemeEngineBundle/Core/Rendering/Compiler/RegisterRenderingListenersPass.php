<?php
/**
 * This file is part of the AlphaLemon FrontendBundle and it is distributed
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

namespace AlphaLemon\ThemeEngineBundle\Core\Rendering\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use AlphaLemon\ThemeEngineBundle\Core\Rendering\Compiler\EventListenersRegistrator;

class RegisterRenderingListenersPass implements CompilerPassInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function process(ContainerBuilder $container)
    {
        EventListenersRegistrator::registerByTaggedServiceId($container, 'alpha_lemon_theme_engine.event_listener');
    }
}

