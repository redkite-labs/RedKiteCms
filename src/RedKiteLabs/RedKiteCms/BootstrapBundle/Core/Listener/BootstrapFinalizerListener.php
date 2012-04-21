<?php

namespace AlphaLemon\BootstrapBundle\Core\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\BootstrapBundle\Core\Event\BootstrapperEvents;
use AlphaLemon\BootstrapBundle\Core\Event\PackageInstalledEvent;
use AlphaLemon\BootstrapBundle\Core\Event\PackageUninstalledEvent;

/**
 * Dispatches the bootstrap events when required
 */
class BootstrapFinalizerListener
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
    }
    
    /**
     * Executes the action when the onKernelRequest event is dispatched
     */
    public function onKernelRequest()
    {
        // looks for the .listeners file
        $listenersFile = $this->container->getParameter('kernel.root_dir') . '/config/bundles/.listeners'; 
        if (is_file($listenersFile)) {
            
            // Retrieve the listeners
            $json = file_get_contents($listenersFile);
            $listeners = json_decode($json, true); 
            foreach ($listeners as $listenerName => $listenerClasses) {
                // dispatches the events
                switch($listenerName) {
                    case BootstrapperEvents::PACKAGE_INSTALLED:
                        foreach ($listenerClasses as $listener) {
                            $this->container->get('event_dispatcher')->addListener($listenerName, array(new $listener(), 'onPackageInstalled')); 
                            $this->container->get('event_dispatcher')->dispatch(BootstrapperEvents::PACKAGE_INSTALLED, new PackageInstalledEvent($this->container));
                        }
                    break;
                    case BootstrapperEvents::PACKAGE_UNINSTALLED:
                        foreach ($listenerClasses as $listener) {
                            $this->container->get('event_dispatcher')->addListener($listenerName, array(new $listener(), 'onPackageUninstalled')); 
                            $this->container->get('event_dispatcher')->dispatch(BootstrapperEvents::PACKAGE_UNINSTALLED, new PackageUninstalledEvent($this->container));
                        }
                    break;
                }
            }
            unlink($listenersFile);
        }
    }
}