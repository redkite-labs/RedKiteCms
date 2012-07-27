<?php

namespace AlphaLemon\BootstrapBundle\Core\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use AlphaLemon\BootstrapBundle\Core\PackagesBootstrapper\PackagesBootstrapper;

/**
 * Dispatches the bootstrap events when required
 */
class ExecutePostActionsListener
{
    private $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->basePath = $this->container->getParameter('kernel.root_dir') . '/config/bundles';
    }

    /**
     * Executes the action when the onKernelRequest event is dispatched
     */
    public function onKernelRequest()
    {
        $packagesBootstrapper = new PackagesBootstrapper($this->basePath, $this->container);
        $packagesBootstrapper->executePostBootActions();
    }
}
