<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
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

namespace AlphaLemon\ThemeEngineBundle\Core\Autoloader\Base;

use Symfony\Component\Finder\Finder;
use AlphaLemon\ThemeEngineBundle\Core\Autoloader\Exception\InvalidAutoloaderException;

/**
 * Instantiates all the bundles saved into a specific folder
 * 
 * @author AlphaLemon
 * @deprecated in favour of https://github.com/alphalemon/BootstrapBundle
 */
abstract class BundlesAutoloader
{
    /**
     * Configures the autoloader
     */
    abstract protected function configure();

    /**
     * Constructor
     */
    public function __construct()
    {
        $paths = $this->configure();
        
        if(null === $paths)
        {
            throw new InvalidAutoloaderException("The bundles autoloader has returned a null path.");
        }
        
        if(!is_array($paths))
        {
            throw new InvalidAutoloaderException("The autoloader configure method must return an array of paths");
        }
        
        if(empty($paths))
        {
            throw new InvalidAutoloaderException("Any path has been configured for autoloading. Please return at least a valid path from your bundles autoloader");
        }
         
        $this->paths = $paths;
    }

    /**
     * Parsers the folder, instantiates the bundles and return them into an array
     * 
     * @return array 
     */
    public function getBundles()
    {
        $bundles = array();        
        foreach($this->paths as $namespace => $paths)
        {
            if(!is_array($paths)) $paths = array($paths);
            foreach($paths as $path)
            {
                if($path == 'composer') {
                    $composer = new BundlesAutoloaderComposer($namespace);
                    $bundles = array_merge($bundles, $composer->getInstantiatedBundles()); 
                }
                else {
                    $finder = new Finder();                    
                    $internalBundles = $finder->depth(0)->directories()->in($path);
                    foreach($internalBundles as $internalBundle)
                    {
                        $bundleFolder = trim($internalBundle->getFileName());
                        
                        $finder = new Finder();
                        $files = $finder->depth(0)->files()->name('*.php')->in((string)$internalBundle);
                        foreach($files as $file)
                        {
                            $bundles[] = $this->instantiateBundle($namespace, $bundleFolder . '\\' . basename($file, '.php'));
                        }
                        
                    }
                }
            }
        }
        
        return $bundles;
    }
    
    protected function instantiateBundle($namespace, $bundle)
    {
        $className = $namespace . "\\" . $bundle; 
        if(!class_exists($className))
        {
            throw new InvalidAutoloaderException(sprintf("The bundle class %s does not exist. Check the %s configure method to fix the problem.", $className, get_class($this)));
        }
        
        return new $className();
    }
}