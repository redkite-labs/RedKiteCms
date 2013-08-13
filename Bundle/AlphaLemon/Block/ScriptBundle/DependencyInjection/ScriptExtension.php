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
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\Block\ScriptBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

/**
 * ScriptExtension
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class ScriptExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
      $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
      $loader->load('services.xml');
    }

    public function getAlias()
    {
      return 'script';
    }
}
