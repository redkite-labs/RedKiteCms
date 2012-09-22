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

namespace AlphaLemon\BootstrapBundle\Core\Autoloader;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use AlphaLemon\BootstrapBundle\Core\Exception\InvalidProjectException;
use AlphaLemon\BootstrapBundle\Core\Exception\InvalidAutoloaderException;
use AlphaLemon\BootstrapBundle\Core\Json\JsonAutoloader;
use AlphaLemon\BootstrapBundle\Core\Event\BootstrapperEvents;
use AlphaLemon\BootstrapBundle\Core\Event\PackageInstalledEvent;
use AlphaLemon\BootstrapBundle\Core\Event\PackageUninstalledEvent;
use AlphaLemon\BootstrapBundle\Core\Script;
use AlphaLemon\BootstrapBundle\Core\Json\JsonAutoloaderCollection;

/**
 * Parses the bundles installed by composer, checks if the bundle has an autoload.json file in its main root
 * folder and when the file is present, copies the autoloader.json, the routing.yml and config.yml under the
 * app/config/bundles folder, to autoconfigure the bundle.
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class BundlesAutoloader
{
    private $bundles;
    private $instantiatedBundles;
    private $environment;
    private $kernelDir;
    private $vendorDir;
    //private $autoloaders = array();
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

    /**
     * Constructor
     *
     * @param string $kernelDir     The kernel directory path
     * @param string $environment   The current environment
     * @param array $bundles        The bundles already loaded
     * @param Script\Factory\ScriptFactoryInterface $scriptFactory
     */
    public function __construct($kernelDir, $environment, array $bundles, Script\Factory\ScriptFactoryInterface $scriptFactory = null)
    {
        $this->environment = $environment;
        $this->kernelDir = $kernelDir;
        $this->vendorDir = $this->kernelDir . '/../vendor';
        $this->filesystem = new Filesystem();


        $this->setupFolders();

        $this->scriptFactory = (null === $scriptFactory) ? new Script\Factory\ScriptFactory($this->basePath) : $scriptFactory;
        $this->bundles = $bundles;
        foreach ($this->bundles as $bundle) {
            $this->instantiatedBundles[] = get_class($bundle);
        }
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
     * @param type $vendorDir
     * @return \AlphaLemon\BootstrapBundle\Core\Autoloader\BundlesAutoloader
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
        if (!$this->bootstrapped) {
            $this->autoloaderCollection = new JsonAutoloaderCollection($this->vendorDir);
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
            if (!array_key_exists($overriderBundle, $order)) $order[$overriderBundle] = 0;

            // An overrided takes a point everytime is found
            foreach ($overridedBundles as $overriderBundle => $overridedBundle) {
                if (array_key_exists($overridedBundle, $order)) {
                    $order[$overridedBundle] = $order[$overridedBundle] + 1;
                }
                else {
                    $order[$overridedBundle] = 1;
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

                    if (!class_exists($bundleClass)) {
                        throw new InvalidAutoloaderException(sprintf("The bundle class %s does not exist. Check the bundle's autoload.json to fix the problem", $bundleClass, get_class($this)));
                    }

                    if (!in_array($bundle->getId(), $this->bundles)) {
                        $instantiatedBundle = new $bundleClass;
                        $this->bundles[$bundle->getId()] = $instantiatedBundle;
                        $overridedBundles = $bundle->getOverrides();
                        if (!empty($overridedBundles)) $this->overridedBundles[$bundle->getId()] = $overridedBundles;
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
        $installScripts = array();
        foreach ($this->autoloaderCollection as $dir => $jsonAutoloader) {
            $bundleName = $jsonAutoloader->getBundleName();
            $this->installPackage($dir, $jsonAutoloader);

            // Check if the bundle under exam has attached an ActionManager file
            $actionsManager = $jsonAutoloader->getActionManager();
            if ((!array_key_exists($bundleName, $this->installedBundles) && null !== $actionsManager)) {
                if (null !== $jsonAutoloader->getActionManagerClass()) {
                    $installScripts[$bundleName] = $actionsManager;

                    // Copies the current ActionManager class to app/config/bundles/cache folder
                    // because it must be preserved when a bundle is uninstalled
                    $reflection = new \ReflectionClass($actionsManager);
                    $fileName = $reflection->getFileName();
                    $className = $this->cachePath . '/' . $bundleName . '/' . basename($fileName);
                    $this->filesystem->copy($fileName, $className, true);
                }
            }

            unset($this->installedBundles[$bundleName]);
        }

        $installerScript = $this->scriptFactory->createScript('PreBootInstaller');
        $installerScript->executeActions($installScripts);
    }

    /**
     * Installs the autoloader.json, the routing and config files
     *
     * @param string $sourceFolder          The source folder where the autoloader is placed
     * @param JsonAutoloader $autoloader    The generated autoloader object
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
            $this->copyConfigurationFile('routing', $filename, $sourceFolder, $this->routingPath);
        }
    }

    protected function copyConfigurationFile($section, $filename, $sourceFolder, $targetFolder)
    {
        $envConfigFile = sprintf('%s/%s_%s.yml', $sourceFolder, $section, $this->environment);
        $sourceFile = (file_exists($envConfigFile)) ? $envConfigFile : $sourceFolder . '/' . $section . '.yml';
        $this->copy($sourceFile, $targetFolder . '/' . $this->environment . '/' . $filename);
    }

    /**
     * Removes the autoloader and config from the app/config/bundles folder
     */
    protected function uninstall()
    {
        $uninstallScripts = array();
        if (!empty($this->installedBundles)) {
            foreach ($this->installedBundles as $autoloader) {
                $bundleName = $autoloader->getBundleName();
                $uninstallScripts[$bundleName] = $autoloader->getActionManagerClass();
                $this->filesystem->remove($this->autoloadersPath . '/' . $bundleName . '.json');
                $this->filesystem->remove($this->configPath);
                $this->filesystem->remove($this->routingPath);
            }
        }

        $this->requireCachedClasses();
        $uninstallerScript = $this->scriptFactory->createScript('PreBootUninstaller');
        $uninstallerScript->executeActions($uninstallScripts);
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
            $jsonAutoloader = new JsonAutoloader($bundleName, (string)$autoloader);
            $this->installedBundles[$bundleName] = $jsonAutoloader;
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
     * @param string $path The bundle's path
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
     * Sets up the paths and creates the folder if they not exist
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

    /**
     * Requires the cached classes when needed
     */
    private function requireCachedClasses()
    {
        $classPath = $this->cachePath;
        if (is_dir($classPath)) {
            $finder = new Finder();
            $actionManagerFiles = $finder->files()->depth(1)->name('*.php')->in($classPath);
            foreach ($actionManagerFiles as $actionManagerFile) {
                $classFileName = (string)$actionManagerFile;
                $classContents = file_get_contents($classFileName);
                preg_match('/namespace ([\w\\\]+);.*?class ([\w]+).*?{/s', $classContents, $match);
                if (isset($match[1]) && isset($match[2])) {
                    $class = $match[1] . '\\' . $match[2];
                    if (!in_array($class, get_declared_classes())) @require_once $classFileName;
                }
            }
        }
    }
}