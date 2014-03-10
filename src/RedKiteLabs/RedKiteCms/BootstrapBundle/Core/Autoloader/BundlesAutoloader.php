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

namespace RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Autoloader;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Exception\InvalidAutoloaderException;
use RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Json\JsonAutoloader;
use RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Json\JsonAutoloaderCollection;

/**
 * Parses bundles installed by composer, checks if the bundle has an autoload.json
 * file in its main root folder and when the file is present, copies the autoloader.json,
 * the routing.yml and config.yml under the app/config/bundles folder, to autoconfigure
 * the bundle.
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BundlesAutoloader
{
    private $bundles;
    private $instantiatedBundles;
    private $environment;
    private $kernelDir;
    private $vendorDir;
    private $autoloaderCollection = array();
    private $installedBundles = array();
    private $environmentsBundles = array();
    private $overridedBundles = array();
    private $basePath;
    private $autoloadersPath;
    private $configPath;
    private $routingPath;
    private $cachePath;
    private $filesystem;
    private $bootstrapped = false;
    private $searchFolders;

    /**
     * Constructor
     *
     * @param string     $kernelDir     The kernel directory path
     * @param string     $environment   The current environment
     * @param array      $bundles       The bundles already loaded
     * @param null|array $searchFolders Adds extra folders to be parsed to look for other budles to autoload. When null looks for RedKiteLabs's blocks
     */
    public function __construct($kernelDir, $environment, array $bundles, $searchFolders = null)
    {
        $this->environment = $environment;
        $this->kernelDir = $kernelDir;
        $this->vendorDir = $this->kernelDir . '/../vendor';
        $this->searchFolders = array(
            $this->kernelDir . '/../src/RedKiteCms/Block',
            $this->kernelDir . '/../src/RedKiteCms/Theme',
        );
        if (null !== $searchFolders) {
            $this->searchFolders = $searchFolders;
        }
        $this->filesystem = new Filesystem();

        $this->setupFolders();

        // @codeCoverageIgnoreStart
        $this->bundles = $bundles;
        foreach ($this->bundles as $bundle) {
            $this->instantiatedBundles[] = get_class($bundle);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Returns the instantiated bundles
     *
     * @return array
     */
    public function getBundles()
    {
        $this->run();

        return $this->bundles;
    }

    /**
     * Sets the vendor directory path
     *
     * @param  type                                                           $vendorDir
     * @return \RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Autoloader\BundlesAutoloader
     * @codeCoverageIgnore
     */
    public function setVendorDir($vendorDir)
    {
        $this->vendorDir = $vendorDir;

        return $this;
    }

    /**
     * Runs the process
     */
    protected function run()
    {
        if (! $this->bootstrapped) {
            $this->autoloaderCollection = new JsonAutoloaderCollection($this->vendorDir, $this->searchFolders);
            $this->retrieveInstalledBundles();
            $this->install();
            $this->uninstall();
            $this->arrangeBundlesForEnvironment();

            $this->bootstrapped = true;
        }
    }

    /**
     * Orders the bundles according to bundle's json param "overrided"
     */
    protected function orderBundles()
    {
        // Gives a score to each bundle
        $order = array();
        foreach ($this->overridedBundles as $overriderBundle => $overridedBundles) {

            // An overrider bundle enters in the compilation, but takes any point
            if (!array_key_exists($overriderBundle, $order)) {
                $order[$overriderBundle] = 0;
            }

            // An overrided gets a point everytime is found
            foreach ($overridedBundles as $overriderBundle => $overridedBundle) {
                $order[$overridedBundle] = 1;
                if (array_key_exists($overridedBundle, $order)) {
                    $order[$overridedBundle] = $order[$overridedBundle] + 1;
                }
            }
        }

        arsort($order);

        // Arranges the bundles entries
        foreach ($order as $bundleName => $pos) {
            $bundle = $this->bundles[$bundleName];
            unset($this->bundles[$bundleName]);
            $this->bundles[$bundleName] = $bundle;
        }
    }

    /**
     * Parsers the autoloaders and arranges the bundles for environment
     */
    protected function arrangeBundlesForEnvironment()
    {
        foreach ($this->autoloaderCollection as $autoloader) {
            $autoloaderBundles = $autoloader->getBundles();
            foreach ($autoloaderBundles as $environment => $bundles) {
                foreach ($bundles as $bundle) {
                    $this->environmentsBundles[$environment][] = $bundle;
                }
            }
        }

        $this->register('all');
        $this->register($this->environment);
        $this->orderBundles();
    }

    /**
     * Registers the bundles for the given environment
     *
     * @param string $environment
     */
    protected function register($environment)
    {
        if (isset($this->environmentsBundles[$environment])) {
            foreach ($this->environmentsBundles[$environment] as $bundle) {
                $bundleClass = $bundle->getClass();
                if (empty($this->instantiatedBundles) || !in_array($bundleClass, $this->instantiatedBundles)) {

                    if ( ! class_exists($bundleClass)) {
                        throw new InvalidAutoloaderException(sprintf("The bundle class %s does not exist. Check the bundle's autoload.json to fix the problem", $bundleClass, get_class($this)));
                    }

                    if ( ! in_array($bundle->getName(), $this->bundles)) {
                        $instantiatedBundle = new $bundleClass;
                        $this->bundles[$bundle->getName()] = $instantiatedBundle;
                        $overridedBundles = $bundle->getOverrides();
                        if ( ! empty($overridedBundles)) {
                            $this->overridedBundles[$bundle->getName()] = $overridedBundles;
                        }
                        $this->instantiatedBundles[] = $bundleClass;
                    }
                }
            }
        }
    }

    /**
     * Instantiates the bundles that must be autoconfigured, parsing the autoload_namespaces.php file
     * generated by composer
     */
    protected function install()
    {
        foreach ($this->autoloaderCollection as $dir => $jsonAutoloader) {
            $bundleName = $jsonAutoloader->getBundleName();
            $this->installPackage($dir, $jsonAutoloader);
            unset($this->installedBundles[$bundleName]);
        }
    }

    /**
     * Installs the autoloader.json, the routing and config files
     *
     * @param string         $sourceFolder The source folder where the autoloader is placed
     * @param JsonAutoloader $autoloader   The generated autoloader object
     */
    protected function installPackage($sourceFolder, JsonAutoloader $autoloader)
    {
        $bundleName = $autoloader->getBundleName();

        if (array_key_exists('all', $autoloader->getBundles()) || array_key_exists($this->environment, $autoloader->getBundles())) {
            $target = $this->autoloadersPath . '/' . $bundleName  . '.json';
            $this->copy($autoloader->getFilename(), $target);

            $sourceFolder = $sourceFolder . '/Resources/config';
            $filename = $bundleName . '.yml';
            $this->copyConfigurationFile('config', $filename, $sourceFolder, $this->configPath);
            $this->copy($sourceFolder . '/routing.yml', $this->routingPath . '/' . $filename);
        }
    }

    /**
     * Copies a configuration file from bundle to application folder
     *
     * @param string $section
     * @param string $filename
     * @param string $sourceFolder
     * @param string $targetFolder
     */
    protected function copyConfigurationFile($section, $filename, $sourceFolder, $targetFolder)
    {
        $envConfigFile = sprintf('%s/%s_%s.yml', $sourceFolder, $section, $this->environment);
        $sourceFile = (file_exists($envConfigFile)) ? $envConfigFile : $sourceFolder . '/' . $section . '.yml';
        $this->copy($sourceFile, $targetFolder . '/' . $this->environment . '/' . $filename);
    }

    /**
     * Removes the autoloader and the config from the app/config/bundles folder
     */
    protected function uninstall()
    {
        if (!empty($this->installedBundles)) {
            foreach ($this->installedBundles as $autoloader) {
                $bundleName = $autoloader->getBundleName();
                $this->filesystem->remove($this->autoloadersPath . '/' . $bundleName . '.json');
                $this->filesystem->remove($this->configPath);
                $this->filesystem->remove($this->routingPath);
            }
        }
    }

    /**
     * Retrieves the current installed bundles
     */
    protected function retrieveInstalledBundles()
    {
        $finder = new Finder();
        $autoloaders = $finder->files()->depth(0)->name('*.json')->in($this->autoloadersPath);
        foreach ($autoloaders as $autoloader) {
            $bundleName = strtolower(basename($autoloader->getFilename(), '.json'));
            $jsonAutoloader = new JsonAutoloader($bundleName, (string) $autoloader);
            $this->installedBundles[$bundleName] = $jsonAutoloader;
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
            $finder = new Finder();
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
            $finder = new Finder();
            $bundles = $finder->files()->depth(0)->name('autoload.json')->in($path);
            if (count($bundles) == 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Copies the source file
     *
     * @param string $source
     * @param string $target
     *
     * @return boolean
     */
    protected function copy($source, $target)
    {
        if (is_file($source)) {
            $exists = is_file($target) ? true :false;
            $this->filesystem->copy($source, $target);

            if (!$exists) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sets up the paths and creates the folder when they not exist
     */
    private function setupFolders()
    {
        $this->basePath = $this->kernelDir . '/config/bundles';
        $this->autoloadersPath = $this->basePath . '/autoloaders';
        $this->configPath = $this->basePath . '/config';
        $this->routingPath = $this->basePath . '/routing';
        $this->cachePath = $this->basePath . '/cache';

        $this->filesystem->mkdir(array(
            $this->basePath,
            $this->autoloadersPath,
            $this->configPath,
            $this->routingPath,
            $this->cachePath,));
    }
}
