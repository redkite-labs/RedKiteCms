<?php
/*
 * This file is part of the RedKiteLabsBootstrapBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://redkite-labs.com
 *
 * @license    MIT License
 */

namespace RedKiteLabs\BootstrapBundle\Core\Loader;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Finder\Finder;
use RedKiteLabs\BootstrapBundle\Core\Json\JsonAutoloaderCollection;

/**
 * Automatically loads routes from the predefined routing folder
 */
class RoutingLoader extends YamlFileLoader
{
    private $routingDir;

    /**
     * {@inheritdoc}
     */
    public function __construct(FileLocatorInterface $locator, JsonAutoloaderCollection $autoloaderCollection, $routingDir)
    {
        parent::__construct($locator);

        $this->autoloaderCollection = $autoloaderCollection;
        $this->routingDir = $routingDir;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        $bundles = $this->orderRoutes();
        $collection = new RouteCollection();
        foreach($bundles as $bundle) {
            $routingConfig = $this->routingDir . '/' . strtolower($bundle) . '.yml'; 
            if (file_exists($routingConfig)) {
                $collection->addCollection(parent::load($routingConfig));
                $collection->addResource(new FileResource($routingConfig));
            }
        }

        return $collection;
    }

    protected function orderRoutes()
    {
        $order = array();
        foreach ($this->autoloaderCollection as $autoloader) {
            $bundleName = strtolower($autoloader->getBundleName());
            $routing = $autoloader->getRouting();
            $section = (null !== $routing) ? (int)$routing['priority'] : 0;
            $order[$section][] = $bundleName;
        }
        ksort($order);

        $result = array();
        foreach ($order as $bundle) {
            $result = array_merge($result, $bundle);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return 'bootstrap' === $type;
    }
}
