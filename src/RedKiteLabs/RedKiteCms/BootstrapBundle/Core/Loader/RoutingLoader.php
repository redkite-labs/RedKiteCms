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

namespace RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Loader;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Resource\FileResource;
use RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Json\JsonAutoloaderCollection;

/**
 * Defines a routing loader object to automatically load routes from a predefined
 * routing folder
 */
class RoutingLoader extends YamlFileLoader
{
    private $routingDir;

    /**
     * Constructor
     *
     * @param \Symfony\Component\Config\FileLocatorInterface                  $locator
     * @param \RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Json\JsonAutoloaderCollection $autoloaderCollection
     * @param string                                                          $routingDir
     */
    public function __construct(FileLocatorInterface $locator, JsonAutoloaderCollection $autoloaderCollection, $routingDir)
    {
        parent::__construct($locator);

        $this->autoloaderCollection = $autoloaderCollection;
        $this->routingDir = $routingDir;
    }

    /**
     * Loads a Yaml file.
     *
     * @param string      $file A Yaml file path
     * @param string|null $type The resource type
     *
     * @return RouteCollection A RouteCollection instance
     *
     * @throws \InvalidArgumentException When a route can't be parsed because YAML is invalid
     */
    public function load($resource, $type = null)
    {
        $bundles = $this->orderRoutes();
        $collection = new RouteCollection();
        foreach ($bundles as $bundle) {
            $routingConfig = $this->routingDir . '/' . strtolower($bundle) . '.yml';
            if (file_exists($routingConfig)) {
                $collection->addCollection(parent::load($routingConfig));
                $collection->addResource(new FileResource($routingConfig));
            }
        }

        return $collection;
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return Boolean true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return 'bootstrap' === $type;
    }

    private function orderRoutes()
    {
        $order = array();
        foreach ($this->autoloaderCollection as $autoloader) {
            $bundleName = strtolower($autoloader->getBundleName());
            $routing = $autoloader->getRouting();
            $section = (null !== $routing) ? (int) $routing['priority'] : 0;
            $order[$section][] = $bundleName;
        }
        ksort($order);

        $result = array();
        foreach ($order as $bundle) {
            $result = array_merge($result, $bundle);
        }

        return $result;
    }
}
