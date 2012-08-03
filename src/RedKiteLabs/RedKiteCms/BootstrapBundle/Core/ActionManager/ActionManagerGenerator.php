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

namespace AlphaLemon\BootstrapBundle\Core\ActionManager;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Util\Filesystem;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use AlphaLemon\BootstrapBundle\Core\Exception\InvalidProjectException;
use AlphaLemon\ThemeEngineBundle\Core\Autoloader\Exception\InvalidAutoloaderException;
use AlphaLemon\BootstrapBundle\Core\Json\JsonAutoloader;
use AlphaLemon\BootstrapBundle\Core\Event\BootstrapperEvents;
use AlphaLemon\BootstrapBundle\Core\Event\PackageInstalledEvent;
use AlphaLemon\BootstrapBundle\Core\Event\PackageUninstalledEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Generates an ActionManager object and implements the methods to retrieve both
 * the generated object and its class. The ActionManager could be generated from
 * an object or from a vald class name
 * 
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class ActionManagerGenerator
{
    private $actionManager = null;
    private $actionManagerClass = null;

    /**
     * Generates the ActionManager object
     *  
     * @param mixed ActionManagerInterface|string $actionManager 
     */
    public function generate($actionManager)
    {
        if ($actionManager instanceof ActionManagerInterface) {
            $this->actionManager = $actionManager;
            $this->actionManagerClass = get_class($this->actionManager);
        }

        if (is_string($actionManager) && class_exists($actionManager)) {
            $this->actionManager = new $actionManager();
            $this->actionManagerClass = $actionManager;
        }
    }

    /**
     * Returns the ActionManager object
     * 
     * @return null|ActionManagerInterface 
     */
    public function getActionManager()
    {
        return $this->actionManager;
    }

    /**
     * Returns the ActionManager's class name
     * 
     * @return null|string 
     */
    public function getActionManagerClass()
    {
        return $this->actionManagerClass;
    }
}