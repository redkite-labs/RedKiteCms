<?php
/*
 * This file is part of the RedKiteLabsPageTreeBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
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

namespace RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Json;

use RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Exception\InvalidProjectException;
use Symfony\Component\Finder\Finder;

/**
 * Defines an autoloaders objects collection
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class JsonAutoloaderCollection implements \Iterator, \Countable
{
    protected $autoloaders = array();
    protected $extraFolders;

    /**
     * Constructor
     *
     * @param string $vendorDir
     * @param array  $extraFolders
     */
    public function __construct($vendorDir, array $extraFolders = array())
    {
        $this->vendorDir = $vendorDir;
        $this->extraFolders = $extraFolders;

        $this->load();
    }

    /**
     * Return the current element
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return current($this->autoloaders);
    }

    /**
     * Return the key of the current element
     *
     * @link http://php.net/manual/en/iterator.key.php
     * @return scalar scalar on success, or <b>NULL</b> on failure.
     */
    public function key()
    {
        return key($this->autoloaders);
    }

    /**
     * Move forward to next element
     *
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        return next($this->autoloaders);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        return reset($this->autoloaders);
    }

    /**
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     */
    public function valid()
    {
        return (current($this->autoloaders) !== false);
    }

    /**
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
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
        foreach ($map as $namespace => $paths) {
            if ( ! is_array($paths)) {
                $paths = array($paths);
            }

            foreach ($paths as $path) {
                // @codeCoverageIgnoreStart
                if (substr($path, -1) != '/') {
                    $path .= '/';
                }
                // @codeCoverageIgnoreEnd

                $dir = $path . str_replace('\\', '/', $namespace);

                $bundleName = $this->getBundleName($dir);
                $this->addBundle($bundleName, $dir);
            }
        }

        $this->parseExtraFolders();
    }

    /**
     * parses extra folders to look for autoloaders in different path than the ones
     * saved into the composer file
     */
    protected function parseExtraFolders()
    {
        foreach ($this->extraFolders as $folder) {
            $finder = new Finder();
            if (is_dir($folder)) {
                $bundleFolders = $finder->directories()->depth(0)->in($folder);
                foreach ($bundleFolders as $bundleFolder) {
                    $bundleName = basename($bundleFolder);
                    $this->addBundle($bundleName, (string) $bundleFolder);
                }
            }
        }
    }

    /**
     * Retrieves the current bundle class
     *
     * @param  string $path The bundle's path
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
     * @param  string  $path The bundle's path
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

    /**
     * Adds a new autoloader for thr given bundle
     *
     * @param string $bundleName
     * @param string $bundleFolder
     */
    protected function addBundle($bundleName, $bundleFolder)
    {
        if (null !== $bundleName && $this->hasAutoloader($bundleFolder)) {
            // Instantiates the autoload
            $bundleName = strtolower($bundleName);
            $autoloader = $bundleFolder . '/autoload.json';
            $jsonAutoloader = new JsonAutoloader($bundleName, $autoloader);
            $this->autoloaders[$bundleFolder] = $jsonAutoloader;
        }
    }
}
