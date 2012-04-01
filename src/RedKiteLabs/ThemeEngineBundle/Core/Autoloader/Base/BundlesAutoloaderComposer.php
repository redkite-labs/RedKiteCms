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
                     $bundles[] = $this->namespace . '\\' . $this->retrieveBundleName($path);
                }
                
                return $bundles;
            }
        }
        
        return array();
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