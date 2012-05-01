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
    private $eventsNotExecuted = array();
    
    /**
     * Constructor 
     * 
     * @param ContainerInterface $container 
     */
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->listenersPath = $this->container->getParameter('kernel.root_dir') . '/config/bundles';
    }
    
    /**
     * Executes the action when the onKernelRequest event is dispatched
     */
    public function onKernelRequest()
    {
        // looks for the .listeners file        
        $listeners = array();
        
        $notExecutedListeners = array();
        $jsonFile = $this->listenersPath . '/.listeners-not-executed'; 
        if (is_file($jsonFile)) {
            $notExecutedListeners = $this->getJsonFilecontents($jsonFile);
        }
        
        $listenersFile = $this->listenersPath . '/.listeners'; 
        if (is_file($listenersFile)) {
            $listeners = $this->getJsonFilecontents($listenersFile);
        }
        
        $listeners = array_merge($listeners, $notExecutedListeners);
        if(!empty($listeners)) {
            foreach ($listeners as $listenerName => $listenerClasses) {
                // dispatches the events
                switch($listenerName) {
                    case BootstrapperEvents::PACKAGE_INSTALLED:
                        foreach ($listenerClasses as $listener) {
                            $this->container->get('event_dispatcher')->addListener($listenerName, array(new $listener(), 'onPackageInstalled')); 
                            $event = new PackageInstalledEvent($this->container);
                            $this->container->get('event_dispatcher')->dispatch(BootstrapperEvents::PACKAGE_INSTALLED, $event);
                            if (!$event->getSuccess()) {
                                $this->addEventNotExecuted($listenerName, $listener);
                                if ($event->getAlertWhenFails()) throw new \RuntimeException(sprintf("The event %s required by %s has not been executed. Please check the bundle you are trying to install or uninstall it if the problem persist.", $listenerName, $listener));
                            } else {
                                if (!empty($notExecutedListeners) && in_array($listener, $notExecutedListeners[$listenerName])) unset($notExecutedListeners[$listenerName]);
                            }
                                
                        }
                    break;
                    case BootstrapperEvents::PACKAGE_UNINSTALLED:
                        foreach ($listenerClasses as $listener) {
                            $this->container->get('event_dispatcher')->addListener($listenerName, array(new $listener(), 'onPackageUninstalled')); 
                            $this->container->get('event_dispatcher')->dispatch(BootstrapperEvents::PACKAGE_UNINSTALLED, new PackageUninstalledEvent($this->container));
                            if (!$event->getSuccess()) {
                                $this->addEventNotExecuted($listenerName, $listener);
                                if ($event->getAlertWhenFails()) throw new \RuntimeException(sprintf("The event %s required by %s has not been executed. You should do the uninstall operations manually", $listenerName, $listener));
                            } else {
                                if (!empty($notExecutedListeners) && in_array($listener, $notExecutedListeners[$listenerName])) unset($notExecutedListeners[$listenerName]);
                            }
                        }
                    break;
                }
            }
            if(is_file($listenersFile)) unlink($listenersFile);
            $this->writeEventsNotExecuted();
        }
    }
    
    private function getJsonFilecontents($file)
    {
        $json = file_get_contents($file);
        return json_decode($json, true); 
    }
    
    private function addEventNotExecuted($listenerName, $listener)
    {
        if (empty($this->eventsNotExecuted) || !in_array($listener, $this->eventsNotExecuted[$listenerName])) {
            $this->eventsNotExecuted[$listenerName][] = $listener; 
        }
    }
    
    private function writeEventsNotExecuted()
    {
        $fileName = $this->listenersPath . '/.listeners-not-executed';
        if (!empty($this->eventsNotExecuted)) {
            $json = json_encode($this->eventsNotExecuted);
            file_put_contents($fileName, $json);
        }
        else {
            @unlink($fileName);
        }
    }
}
