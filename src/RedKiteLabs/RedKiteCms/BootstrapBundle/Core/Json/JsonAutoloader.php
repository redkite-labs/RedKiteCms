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

namespace AlphaLemon\BootstrapBundle\Core\Json;

/**
 * Parses a json autoloader and converts it into an objectì
 *
 * @author alphalemon
 */
class JsonAutoloader
{
    private $bundleName;
    private $filename = array();
    private $bundles = array();
    private $installScript = null;
    private $uninstallScript = null;
    private $force = false;
    
    
    /**
     * Constructor
     * 
     * @param string $bundleName    The name of the bundle who the autoloader belongs
     * @param string $filename      The json filename
     */
    public function __construct($bundleName, $filename)
    {
        $this->bundleName = $bundleName;
        $this->filename = $filename;
        $this->setup();
    }
    
    public function getBundleName()
    {
        return $this->bundleName;
    }
    
    public function getFilename()
    {
        return $this->filename;
    }
    
    public function getBundles()
    {
        return $this->bundles;
    }
    
    public function getForce()
    {
        return $this->force;
    }

    public function getInstallScript()
    {
        return $this->installScript;
    }
    
    public function getUninstallScript()
    {
        return $this->uninstallScript;
    }

    /**
     * Parses the json file and sets up the object
     */
    protected function setup()
    {
        $json = file_get_contents($this->filename);
        $autoloader = json_decode($json, true);
        if (null !== $autoloader) { 
            if (isset($autoloader["bundles"])) { 
                foreach ($autoloader["bundles"] as $bundle => $options) { 
                    $environments = $options["environments"];
                    if (!is_array($environments)) $environments = array($environments);
                    foreach ($environments as $environment) {
                        $this->bundles[$environment][] = $bundle;
                    }
                    if(isset($options["force"]) && (bool)$options["force"]) $this->force = (bool)$options["force"];
                }
            }
            else {
                throw new \AlphaLemon\BootstrapBundle\Core\Exception\InvalidJsonFormatException(sprintf('The json file %s requires the bundles section. Please add that section to fix the problem', $file));
            }
            
            if (isset($autoloader["scripts"])) {
               if (isset($autoloader["scripts"]["package-installed"])) $this->installScript = $autoloader["scripts"]["package-installed"];
               if (isset($autoloader["scripts"]["package-uninstalled"])) $this->uninstallScript = $autoloader["scripts"]["package-uninstalled"];
            }
        } else {
            throw new \AlphaLemon\BootstrapBundle\Core\Exception\InvalidJsonFormatException(sprintf('The json file %s is malformed. Please check the file syntax to fix the problem', $file));
        }
    }
}