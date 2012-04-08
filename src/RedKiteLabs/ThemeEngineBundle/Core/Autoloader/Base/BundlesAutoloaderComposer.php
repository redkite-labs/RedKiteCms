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

/**
 * Retrive the bundles from a given namespace retrieving it from the .composer packages list
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class BundlesAutoloaderComposer
{
    private $namespace;
    
    public function __construct($namespace)
    {
        $this->namespace = $namespace;
    }
    
    public function getBundles()
    {
        $path = __DIR__ . '/../../../../../../../.composer';
        if(is_dir($path))
        {
            $map = require $path . '/autoload_namespaces.php';
            
            $paths = array();
            foreach($map as $namespace => $path)
            {
                if (strpos($namespace, $this->namespace . '\\') !== false) $paths[$namespace] = $path;
            }
                
            $bundles = array();
            foreach($paths as $namespace => $path)
            {
                $finder = new Finder();
                $internalBundles = $finder->files()->directories()->depth(0)->in($path . str_replace('\\', '/', $this->namespace));
                foreach($internalBundles as $bundle)
                {
                    $bundles[$namespace] = (string)$bundle; 
                }
            }
            
            return $bundles;
        }
        
        return array();
    }
    
    public function getInstantiatedBundles()
    {
        $bundles = array();
        $internalBundles = $this->getBundles();
        foreach($internalBundles as $namespace => $bundle)
        {
            $bundles[] = $this->instantiateBundle($namespace, basename($bundle)); 
        }
        
        return $bundles;
    }
    
    protected function instantiateBundle($namespace, $bundle)
    {
        if(method_exists($bundle, 'getAlphaLemonBundleClassAlias'))
        {
            $bundle = $bundle->getAlphaLemonBundleClassAlias();
        }

        $className = $namespace . "\\" . $bundle; 
        if(!class_exists($className))
        {
            throw new InvalidAutoloaderException(sprintf("The bundle class %s does not exist. Check the autoloader configure method ", $className));
        }
        
        return new $className();
    }
    
    protected function retrieveBundleName($path)
    {
        $finder = new Finder();
        $internalBundles = $finder->files()->name('*Bundle.php')->depth(0)->in($path);
        foreach($internalBundles as $bundle)
        {
            return basename($bundle->getFilename(), '.php');
        }
    }
}