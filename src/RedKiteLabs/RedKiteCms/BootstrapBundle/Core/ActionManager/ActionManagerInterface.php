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

namespace RedKiteLabs\BootstrapBundle\Core\ActionManager;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 
 * 
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
interface ActionManagerInterface
{
    /**
     * The action executed after bundles have been installed and the kernel is not booted yet 
     */
    public function packageInstalledPreBoot();
    
    /**
     * The action executed after one or more bundles have been uninstalled and the kernel is not booted yet 
     */
    public function packageUninstalledPreBoot();
    
    /**
     * The action executed after bundles have been installed and the kernel has been booted
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function packageInstalledPostBoot(ContainerInterface $container);
    
    /**
     * The action executed after one or more bundles have been uninstalled and the kernel has been booted
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function packageUninstalledPostBoot(ContainerInterface $container);
}