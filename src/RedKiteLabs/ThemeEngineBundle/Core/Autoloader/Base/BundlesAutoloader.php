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
 * Instantiates all the bundles saved into a specific folder
 *
 * @author AlphaLemon
 */
abstract class BundlesAutoloader
{
    private $pathToSeek;
    private $nameSpace;

    /**
     * Configures the autoloader
     */
    abstract protected function configure();

    /**
     * Constructor
     */
    public function __construct()
    {
         $params = $this->configure();
         
         $required = array("pathToSeek" => "", "nameSpace" => ""); 
         if(count(array_diff_key($required, $params)) != 0)
         {
             throw new \InvalidArgumentException(sprintf("Autoloader needs the following options: %s", implode(',', array_keys($required))));
         }
         
         if(!is_dir($params["pathToSeek"]))
         {
             throw new \InvalidArgumentException(sprintf("The directory %s does not exist", $params["pathToSeek"]));
         }
         
         $this->pathToSeek = $params["pathToSeek"];
         $this->nameSpace = $params["nameSpace"];
    }

    /**
     * Parsers the folder, instantiates the bundles and return them into an array
     * 
     * @return array 
     */
    public function getBundles()
    {
        $bundles = array();
        $finder = new Finder();
        $internalBundles = $finder->directories()->depth(0)->directories()->in($this->pathToSeek); 
        
        foreach($internalBundles as $internalBundle)
        {
            $bundle = $internalBundle->getFileName();
            $className = $this->nameSpace . "\\" . $bundle. "\\" . $bundle; 
            if(!class_exists($className))
            {
                throw new \InvalidArgumentException(sprintf("The bundle class %s does not exist. Check your configure method ", $className));
            }
            $bundles[] = new $className();
        }

        return $bundles;
    }
}