<?php

namespace AlphaLemon\ThemeEngineBundle\Core\Autoloader\Base;

use Symfony\Component\Finder\Finder;

class BundlesAutoloaderComposer
{
    private $namespace;
    
    public function __construct($namespace)
    {
        $this->namespace = $namespace;
    }
    
    public function getBundles()
    {
        $path = __DIR__ . '/../../../../../../.composer';
        if(is_dir($path))
        {
            $map = require $path . '/autoload_namespaces.php';
            if(array_key_exists($this->namespace, $map)) {
                $paths = $map[$this->namespace];
                if(!is_array($paths)) $paths = array($paths);
                
                $bundles = array();
                foreach($paths as $path)
                {
                    $finder = new Finder();
                    $internalBundles = $finder->files()->directories()->depth(0)->in($path);
                    foreach($internalBundles as $bundle)
                    {
                        $bundles[] = $this->instantiateBundle($this->namespace, basename($bundle)); //$this->instantiateBundle($this->namespace, $bundleName);
                    }
        
                    
                }
                print_r($bundles);exit;
                return $bundles;
            }
        }
        
        return array();
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