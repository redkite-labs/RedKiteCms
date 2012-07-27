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

namespace AlphaLemon\BootstrapBundle\Core\PackagesBootstrapper;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class PackagesBootstrapper implements PackagesBootstrapperInterface
{
    private $postActions = array();
    private $actionsNotExecuted = array();
    private $filesystem;
    private $cachePath;
    private $basePath;
    private $postActionsFile = null;
    private $container = null;
    

    public function __construct($basePath, ContainerInterface $container = null)
    {
        $this->basePath = $basePath;
        $this->container = $container;
        $this->cachePath = $this->basePath . '/cache';
        $this->postActionsFile = $this->basePath . '/.post-actions';
        $this->filesystem = new Filesystem();
        $this->filesystem->mkdir($this->cachePath);
    }

    public function executeInstallActionPreBoot($bundleName, $actionManager)
    {
        $r = new \ReflectionClass($actionManager);
        $fileName = $r->getFileName();
        $className = $this->cachePath . '/' . $bundleName . '/' . basename($fileName);
        $this->filesystem->copy($fileName, $className, true);

        $this->executeAction($bundleName, $actionManager, 'packageInstalledPreBoot');

        $actionManagerClass = get_class($actionManager);
        $this->postActions['install'][$bundleName] = $actionManagerClass;
    }

    public function executeUninstallActionPreBoot($bundleName, $actionManagerClass)
    {
        $classPath = $this->cachePath . '/' . $bundleName;
        if (is_dir($classPath)) {
            $classFileName = '';
            $finder = new \Symfony\Component\Finder\Finder();
            $actionManagerFiles = $finder->files()->depth(0)->name('*.php')->in($classPath);
            foreach ($actionManagerFiles as $actionManagerFile) {
                $classFileName = $classPath . '/' . $actionManagerFile->getFilename();
                @require_once $classFileName;

                break;
            }

            $actionManager = new $actionManagerClass();
            if (null !== $actionManager) {
                $this->executeAction($bundleName, $actionManager, 'packageUninstalledPreBoot');

                $this->postActions['uninstall'][$bundleName] = array('classFileName' => $classFileName, 'actionManagerClass' => $actionManagerClass);
            }
        }
    }

    public function executeInstallActionPostBoot($bundleName, $actionManager)
    {
        $this->executeAction($bundleName, $actionManager, 'packageInstalledPostBoot');
    }
    
    public function executeUninstallActionPostBoot($bundleName, $actionManager)
    {
        $this->executeAction($bundleName, $actionManager, 'packageUninstalledPostBoot');
    }
    
    public function executePostBootActions()
    {
        $this->executeFailedActions('packageInstalledPostBoot');
        $this->executeFailedActions('packageUninstalledPostBoot');
        if (file_exists($this->postActionsFile)) {
            $actions = $this->decode($this->postActionsFile);
            foreach ($actions as $actionName => $actionManagerClasses) {
                switch($actionName) {
                    case 'install':
                        foreach ($actionManagerClasses as $bundleName => $actionManagerClass) {
                            $actionManager = new $actionManagerClass();
                            $this->executeInstallActionPostBoot($bundleName, $actionManager);
                        }
                        $this->writeFailedActions('.packageInstalledPostBoot');

                        break;
                    case 'uninstall':
                        foreach ($actionManagerClasses as $bundleName => $classesAttributes) {
                            $classFileName = $classesAttributes["classFileName"];
                            $actionManagerClass = $classesAttributes["actionManagerClass"];
                            if (file_exists($classFileName)) {
                                @require_once $classFileName;;
                                $actionManager = new $actionManagerClass();

                                if (null !== $actionManager) {
                                    $res = $this->executeUninstallActionPostBoot($bundleName, $actionManager);
                                    if ($res) {
                                        $this->filesystem->remove(dirname($classFileName));
                                    }
                                }
                            }
                        }
                        $this->writeFailedActions('.packageUninstalledPostBoot');

                        break;
                }
            }
            $this->filesystem->remove($this->postActionsFile);
        }
    }

    public function writePostActions()
    {
        $this->encode($this->postActionsFile, $this->postActions);
    }
    
    public function writeFailedActions($fileName)
    {
        $fileName = $this->basePath . '/' . $fileName;
        if (!empty($this->actionsNotExecuted)) {
            $this->encode($fileName, $this->actionsNotExecuted);
        }
        else {
            $this->filesystem->remove($fileName);
        }

        $this->actionsNotExecuted = array();
    }

    public function executeFailedActions($action)
    {
        $fileName = $this->basePath . '/.' . $action;
        if (file_exists($fileName)) {
            $actions = $this->decode($fileName);
            foreach ($actions as $bundleName => $actionManagerClass) {
                if (class_exists($actionManagerClass)) {
                    $actionManager = new $actionManagerClass();
                    $this->executeAction($bundleName, $actionManager, $action);
                }
            }

            $this->writeFailedActions('.' . $action);
        }
    }
    
    protected function getFileContents($file)
    {
        return (file_exists($file)) ? file_get_contents($file) : "";
    }

    protected function decode($file)
    {
        $contents = $this->getFileContents($file);

        return ($contents != "") ? json_decode($contents, true) : array();
    }

    protected function encode($file, array $values)
    {
        if (!empty($values)) {
            file_put_contents($file, json_encode($values));
        }
    }

    private function executeAction($bundleName, $actionManager, $method)
    {
        $res = (null !== $this->container) ? $actionManager->$method($this->container) : $actionManager->$method();

        $actionManagerClass = get_class($actionManager);
        if (false === $res) {
            $this->actionsNotExecuted[$bundleName] = $actionManagerClass;
        }
        
        return $res;
    }
}