<?php
/*
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

namespace AlphaLemon\AlphaLemonCmsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use AlphaLemon\ThemeEngineBundle\Core\Compiler\EventListenersRegistrator;

/**
 * Registers the CMS events
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class RegisterCmsListenersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        EventListenersRegistrator::registerByTaggedServiceId($container, 'alcms.event_listener');
    }
}

