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

namespace RedKiteLabs\BootstrapBundle\Core\Script\Base;

use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\BootstrapBundle\Core\Exception\MissingDependencyException;
use RedKiteLabs\BootstrapBundle\Core\Script\PostScriptInterface;
use RedKiteLabs\BootstrapBundle\Core\ActionManager\ActionManagerGenerator;

/**
 * Extends the BaseScript object for the post scripts
 * 
 * @author RedKite Labs <info@redkite-labs.com>
 */
abstract class BasePostScript extends BaseScript implements PostScriptInterface
{
    protected $container = null;

    /**
     * Constructor
     * 
     * @param type $basePath
     * @param ActionManagerGenerator $actionManagerGenerator
     * @param ContainerInterface $container 
     */
    public function __construct($basePath,  ActionManagerGenerator $actionManagerGenerator = null, ContainerInterface $container = null)
    {
        parent::__construct($basePath, $actionManagerGenerator);

        $this->container = $container;
    }

    /**
     * Sets the container
     * 
     * @param ContainerInterface $container
     * @return \RedKiteLabs\BootstrapBundle\Core\Script\Base\BasePostScript 
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Execute the actions from a json file
     * 
     * @param string $fileName 
     */
    public function executeActionsFromFile($fileName)
    {
        $actions = array();
        if (file_exists($fileName)) {
            $actionsClasses = $this->decode($fileName);

            foreach ($actionsClasses as $bundleName => $actionsClass){
                $this->actionManagerGenerator->generate($actionsClass);
                $actions[$bundleName] = $this->actionManagerGenerator->getActionManager();
            }

            $this->filesystem->remove($fileName);
        }
        $this->executeActions($actions);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute($method, array $actionManagers)
    {
        if (null === $this->container) {
            throw new MissingDependencyException("You must give a ContainerInterface object to execute a post action. Use the setContainer method to fix it up");
        }

        $actions = array('post' => array(), 'notExecuted' => array());
        foreach ($actionManagers as $bundleName => $actionManager) {
            $this->actionManagerGenerator->generate($actionManager);
            $actionManagerClass = $this->actionManagerGenerator->getActionManagerClass();
            $actionManager = $this->actionManagerGenerator->getActionManager();
            if (null === $actionManager) {
                continue;
            }
            
            if (false === $actionManager->$method($this->container)) {
                $actions['notExecuted'][$bundleName] = $actionManagerClass;
            }
            $actions['post'][$bundleName] = $actionManagerClass;
        }

        return $actions;
    }
}