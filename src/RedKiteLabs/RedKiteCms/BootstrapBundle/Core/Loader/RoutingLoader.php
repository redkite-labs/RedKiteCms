<?php
/*
 * This file is part of the AlphaLemonBootstrapBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
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

namespace AlphaLemon\BootstrapBundle\Core\Loader;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Finder\Finder;

/**
 * Automatically loads routes from the predefined routing folder
 */
class RoutingLoader extends YamlFileLoader
{

    /**
     * {@inheritdoc}
     * 
     */
    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();
        
        $routingFolder = __DIR__ . '/../../../../../../../app/config/bundles/routing';
        $finder = new Finder();
        $configs = $finder->depth(0)->name('*.yml')->in($routingFolder);        
        foreach($configs as $config) {
            $routingConfig = (string)$config;
            $collection->addCollection(parent::load($routingConfig));
            $collection->addResource(new FileResource($routingConfig));
        }
        
        return $collection;
    }

    /**
     * {@inheritdoc}
     * 
     */
    public function supports($resource, $type = null)
    {
        return 'bootstrap' === $type;
    }
}
