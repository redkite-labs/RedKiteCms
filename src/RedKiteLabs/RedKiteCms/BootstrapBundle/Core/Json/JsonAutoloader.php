<?php
/*
 * This file is part of the RedKiteLabsBootstrapBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://redkite-labs.com
 *
 * @license    MIT License
 */

namespace RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Json;

use RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Json\Bundle\Bundle;
use RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Exception\InvalidJsonFormatException;

/**
 * Parses a json autoloader and converts it into an object
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class JsonAutoloader extends BaseJson
{
    private $bundleName;
    private $filename = null;
    private $bundles = array();
    private $routing = null;

    /**
     * Constructor
     *
     * @param string $bundleName The name of the bundle who the autoloader belongs
     * @param string $filename   The json filename
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
     * Returns the routing option when specified in the autoloader file or null
     *
     * return array|null
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
            $bundle
                ->setClass($bundleClass)
                ->setOverrides($overrides)
            ;
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
}
