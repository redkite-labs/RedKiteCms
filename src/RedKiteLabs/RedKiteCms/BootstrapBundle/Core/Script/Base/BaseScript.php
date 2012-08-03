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

namespace AlphaLemon\BootstrapBundle\Core\Script\Base;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\BootstrapBundle\Core\Script\ScriptInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerGenerator;
use AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerInterface;
use AlphaLemon\BootstrapBundle\Core\Json\JsonToolkit;

/**
 * Defines the base object to execute a script
 * 
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
abstract class BaseScript extends JsonToolkit implements ScriptInterface
{
    protected $filesystem;
    protected $basePath;

    /**
     * Constructor
     * 
     * @param string $basePath
     * @param ActionManagerGenerator $actionManagerGenerator 
     */
    public function __construct($basePath,  ActionManagerGenerator $actionManagerGenerator = null)
    {
        $this->basePath = $basePath;
        $this->actionManagerGenerator = (null === $actionManagerGenerator) ? new ActionManagerGenerator() : $actionManagerGenerator;
        $this->filesystem = new Filesystem();
    }

    /**
     * Executes the given method defined by the given ActionsManager 
     * 
     * @param string $method
     * @param array $actionManagers
     * @return array An array that contains the post actions and the not execution action 
     */
    protected function doExecuteActions($method, array $actionManagers)
    {
        $actions = array('post' => array(), 'notExecuted' => array());
        $this->executeFailedActions($method);
        if (0 !== count($actionManagers)){
            $actions = $this->execute($method, $actionManagers);
            if(!empty($actions['notExecuted'])) $this->writeFailedActions('.' . $method, $actions['notExecuted']);
        }

        return $actions;
    }

    /**
     * Executes the failed action
     * 
     * @param string $action 
     */
    protected function executeFailedActions($action)
    {
        $fileName = $this->basePath . '/.' . $action;
        if (file_exists($fileName)) {
            $actionManagers = $this->decode($fileName);
            $actions = $this->execute($action, $actionManagers);
            $this->writeFailedActions('.' . $action, $actions['notExecuted']);
        }
    }

    /**
     * Executes the actions
     * 
     * @param type $method
     * @param array $actionManagers
     * @return type
     * @throws MissingDependencyException 
     */
    protected function execute($method, array $actionManagers)
    {
        $actions = array('post' => array(), 'notExecuted' => array());
        foreach ($actionManagers as $bundleName => $actionManager) {
            $this->actionManagerGenerator->generate($actionManager);
            $actionManagerClass = $this->actionManagerGenerator->getActionManagerClass();
            if (false === $this->actionManagerGenerator->getActionManager()->$method()) $actions['notExecuted'][$bundleName] = $actionManagerClass;
            $actions['post'][$bundleName] = $actionManagerClass;
        }

        return $actions;
    }

    /**
     * Writes the post actions file
     * 
     * @param string $fileName
     * @param array $actions 
     */
    protected function writePostActions($fileName, array $actions)
    {
        $fileName = $this->basePath . '/' . $fileName;
        $this->encode($fileName, $actions);
    }

    /**
     * Writes the failed actions file
     * 
     * @param string $fileName
     * @param type $actions 
     */
    protected function writeFailedActions($fileName, $actions)
    {
        $fileName = $this->basePath . '/' . $fileName;
        $this->filesystem->remove($fileName);
        if (!empty($actions)) $this->encode($fileName, $actions);
    }
}