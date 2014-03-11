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

namespace RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Json\Bundle;

use RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Exception\InvalidJsonParameterException;

/**
 * Parses a json autoloader and converts it into an object
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class Bundle
{
    private $name = null;
    private $class = null;
    private $overrides = null;

    /**
     * Returns the bundle name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the class that defines the bundle
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Sets the bundle class
     *
     * @param  string                                               $class
     * @return \RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Json\Bundle\Bundle
     * @throws InvalidJsonParameterException
     */
    public function setClass($class)
    {
        preg_match('/\\\([\w]+Bundle)$/', $class, $match);
        if (empty($match[1])) {
            throw new InvalidJsonParameterException(sprintf("The class %s does not seem to be a valid bundle class. Check your autoloader.json file", $class));
        }
        $this->name = basename($match[1]);
        $this->class = $class;

        return $this;
    }

    /**
     * Returns an array which contains the overrided bundles or null
     *
     * @return array|null
     */
    public function getOverrides()
    {
        return $this->overrides;
    }

    /**
     * Sets the overrides bundles
     *
     * @param  array                                                $overrides
     * @return \RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Json\Bundle\Bundle
     */
    public function setOverrides(array $overrides)
    {
        $this->overrides = $overrides;

        return $this;
    }
}
