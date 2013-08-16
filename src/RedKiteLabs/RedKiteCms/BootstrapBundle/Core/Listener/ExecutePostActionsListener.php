<?php

namespace RedKiteLabs\BootstrapBundle\Core\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use RedKiteLabs\BootstrapBundle\Core\Script\Factory as ScriptFactory;

/**
 * Executes the post action
 */
class ExecutePostActionsListener
{
    private $container;
    private $basePath;
    private $executed = false;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     * @param ScriptFactoryInterface $scriptFactory
     */
    public function __construct(ContainerInterface $container, ScriptFactory\ScriptFactoryInterface $scriptFactory = null)
    {
        $this->container = $container;

        $this->basePath = $this->container->getParameter('kernel.root_dir') . '/config/bundles';
        $this->scriptFactory = (null === $scriptFactory) ? new ScriptFactory\ScriptFactory($this->basePath) : $scriptFactory;
    }

    /**
     * Executes the action when the onKernelRequest event is dispatched
     */
    public function onKernelRequest()
    {
        if ($this->executed) return;
        
        $installerScript = $this->scriptFactory->createScript('PostBootInstaller');
        $installerScript->setContainer($this->container)
                        ->executeActionsFromFile($this->basePath . '/.PostInstall');

        $installerScript = $this->scriptFactory->createScript('PostBootUninstaller');
        $installerScript->setContainer($this->container)
                        ->executeActionsFromFile($this->basePath . '/.PostUninstall');

        $this->executed = true;
    }
}
