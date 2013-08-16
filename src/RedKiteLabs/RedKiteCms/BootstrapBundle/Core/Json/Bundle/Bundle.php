<?php
/*
 * This file is part of the RedKiteLabsBootstrapBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://redkite-labs.com
 *
 * @license    MIT License
 */

namespace RedKiteLabs\BootstrapBundle\Core\Json\Bundle;

use RedKiteLabs\BootstrapBundle\Core\ActionManager\ActionManagerInterface;
use RedKiteLabs\BootstrapBundle\Core\Exception\InvalidJsonFormatException;
use RedKiteLabs\BootstrapBundle\Core\Exception\InvalidJsonParameterException;

/**
 * Parses a json autoloader and converts it into an object
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class Bundle
{
    private $id = null;
    private $class = null;
    private $overrides = null;

    public function getId()
    {
        return $this->id;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function setClass($class)
    {
        preg_match('/\\\([\w]+Bundle)$/', $class, $match);
        if (empty($match[1])) {
            throw new InvalidJsonParameterException(sprintf("The class %s does not seem to be a valid bundle class. Check your autoloader.json file", $class));
        }
        $this->id = basename($match[1]);
        $this->class = $class;
    }

    public function getOverrides()
    {
        return $this->overrides;
    }

    public function setOverrides(array $overrides)
    {

        $this->overrides = $overrides;
    }

    public function __toString()
    {
        return $this->id;
    }
}