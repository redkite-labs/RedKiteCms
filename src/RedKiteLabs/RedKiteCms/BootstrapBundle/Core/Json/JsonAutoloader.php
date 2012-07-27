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

use AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerInterface;

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
    private $actionManager = null;
    private $actionManagerClass = null;
    private $force = false;
    private $json = null;

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

    public function getActionManager()
    {
        if (null === $this->actionManager) {
            $this->instantiateActionManager();
        }

        return $this->actionManager;
    }

    public function getActionManagerClass()
    {
        return $this->actionManagerClass;
    }

    public function getSourceJson()
    {
        return $this->json;
    }

    /**
     * Parses the json file and sets up the object
     */
    protected function setup()
    {
        $this->json = file_get_contents($this->filename);
        $autoloader = json_decode($this->json, true);
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
                throw new \AlphaLemon\BootstrapBundle\Core\Exception\InvalidJsonFormatException(sprintf('The json file %s requires the bundles section. Please add that section to fix the problem', $this->filename));
            }

            if (isset($autoloader["actionManager"])) {
                $this->actionManagerClass = $autoloader["actionManager"];

            }
        } else {
            throw new \AlphaLemon\BootstrapBundle\Core\Exception\InvalidJsonFormatException(sprintf('The json file %s is malformed. Please check the file syntax to fix the problem', $this->filename));
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