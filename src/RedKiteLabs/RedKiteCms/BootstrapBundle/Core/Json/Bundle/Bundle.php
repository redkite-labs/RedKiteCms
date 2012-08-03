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

namespace AlphaLemon\BootstrapBundle\Core\Json\Bundle;

use AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerInterface;
use AlphaLemon\BootstrapBundle\Core\Exception\InvalidJsonFormatException;
use AlphaLemon\BootstrapBundle\Core\Exception\InvalidJsonParameterException;

/**
 * Parses a json autoloader and converts it into an object
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
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