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

namespace AlphaLemon\BootstrapBundle\Core\Json;

use AlphaLemon\BootstrapBundle\Core\Exception\InvalidProjectException;

/**
 * Class for iterating over a list of autoloaders elements
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class JsonAutoloaderCollection implements \Iterator, \Countable
{
    protected $autoloaders = array();
    protected $vendorDir;

    /**
     * Constructor
     *
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     * @param array $autoloaders
     */
    public function __construct($vendorDir)
    {
        $this->vendorDir = $vendorDir;
        $this->load();
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->autoloaders);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->autoloaders);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return next($this->autoloaders);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        return reset($this->autoloaders);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return (current($this->autoloaders) !== false);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->autoloaders);
    }


    /**
     * Loads the bundles when the autoload.json file exists, parsing the autoload_namespaces.php file generated
     * by composer
     *
     * @throws InvalidProjectException
     */
    protected function load()
    {
        $path = $this->vendorDir . '/composer';
        if (!is_dir($path)) throw new InvalidProjectException('"composer" folder has not been found. Be sure to use this bundle on a project managed by Composer');

        $map = require $path . '/autoload_namespaces.php';
        foreach ($map as $namespace => $path) {
            $dir = $path . str_replace('\\', '/', $namespace);
            $bundleName = $this->getBundleName($dir);

            // The bundle is inclued only when the autoloader.json file exists
            if (null !== $bundleName && $this->hasAutoloader($dir)) {
                // Instantiates the autoload
                $bundleName = strtolower($bundleName);
                $autoloader = $dir . '/autoload.json';
                $jsonAutoloader = new JsonAutoloader($bundleName, $autoloader);
                $this->autoloaders[$dir] = $jsonAutoloader;
            }
        }
    }

    /**
     * Retrieves the current bundle class
     *
     * @param string $path The bundle's path
     * @return string
     */
    protected function getBundleName($path)
    {
        if (is_dir($path)) {
            $finder = new \Symfony\Component\Finder\Finder();
            $bundles = $finder->files()->depth(0)->name('*Bundle.php')->in($path);
            foreach ($bundles as $bundle) {
                return basename($bundle->getFilename(), 'Bundle.php');
            }
        }

        return null;
    }

    /**
     * Checks if the bundle has an autoloader.json file
     *
     * @param string $path The bundle's path
     * @return boolean
     */
    protected function hasAutoloader($path)
    {
        if (is_dir($path)) {
            $finder = new \Symfony\Component\Finder\Finder();
            $bundles = $finder->files()->depth(0)->name('autoload.json')->in($path);
            if (count($bundles) == 1) {
                return true;
            }
        }

        return false;
    }
}
