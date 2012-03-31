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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\AlScriptBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

/**
 * AlScriptExtension
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class AlScriptExtension extends Extension
{
  public function load(array $configs, ContainerBuilder $container)
  {
      $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
      $loader->load('al_script.xml');
  }

  public function getAlias()
  {
      return 'al_script';
  }
}