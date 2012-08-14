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

use AlphaLemon\BootstrapBundle\Core\Json\Bundle\Bundle;
use AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerInterface;
use AlphaLemon\BootstrapBundle\Core\Exception\InvalidJsonFormatException;
use AlphaLemon\BootstrapBundle\Core\Exception\InvalidJsonParameterException;




/**
 * Parses a json autoloader and converts it into an object
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class JsonAutoloader extends JsonToolkit
{
    private $bundleName;
    private $filename = null;
    private $bundles = array();
    private $actionManager = null;
    private $actionManagerClass = null;
    private $json = null;
    private $routing = null;

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

    /**
     * Returns the bundle name
     *
     * @return string
     */
    public function getBundleName()
    {
        return $this->bundleName;
    }

    /**
     * Return the json file name
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Return the bundles declared in the json autoloader
     *
     * @return string
     */
    public function getBundles()
    {
        return $this->bundles;
    }

    /**
     * Returns the ActionManger object from the class declared in the autoloader json
     *
     * @return null|AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerInterface
     */
    public function getActionManager()
    {
        if (null === $this->actionManager) {
            $this->instantiateActionManager();
        }

        return $this->actionManager;
    }

    /**
     * Returns the ActionManger class declared in the autoloader json
     *
     * @return null|string
     */
    public function getActionManagerClass()
    {
        return $this->actionManagerClass;
    }

    /**
     * Returns the json file contents
     */
    public function getSourceJson()
    {
        return $this->json;
    }
    
    /**
     * Returns the json file contents
     */
    public function getRouting()
    {
        return $this->routing;
    }

    /**
     * Parses the json file and sets up the object
     */
    protected function setup()
    {
        $autoloader = $this->decode($this->filename);
        if (null === $autoloader) {
            throw new InvalidJsonFormatException(sprintf('The json file %s is malformed. Please check the file syntax to fix the problem', $this->filename));
        }

        if (empty($autoloader["bundles"])) {
            throw new InvalidJsonFormatException(sprintf('The json file %s requires the bundles section. Please add that section to fix the problem', $this->filename));
        }

        foreach ($autoloader["bundles"] as $bundleClass => $options) {
            $environments = (isset($options["environments"])) ? $options["environments"] : 'all';
            if (!is_array($environments)) $environments = array($environments);
            $overrides = (isset($options["overrides"])) ? $options["overrides"] : array();
            $bundle = new Bundle();
            $bundle->setClass($bundleClass);
            $bundle->setOverrides($overrides);
            foreach ($environments as $environment) {
                $this->bundles[$environment][] = $bundle;
            }
        }

        if (isset($autoloader["actionManager"])) {
            $this->actionManagerClass = $autoloader["actionManager"];
        }
        
        if (isset($autoloader["routing"])) {
            $this->routing = $autoloader["routing"];
        }
    }

    private function instantiateActionManager()
    {
        if (null !== $this->actionManagerClass && class_exists($this->actionManagerClass)) {
            $class = $this->actionManagerClass;
            $this->actionManager = new $class;
            if (!$this->actionManager instanceof ActionManagerInterface) {
                $this->actionManager = null;
                $this->actionManagerClass = null;
            }
        }
    }
}