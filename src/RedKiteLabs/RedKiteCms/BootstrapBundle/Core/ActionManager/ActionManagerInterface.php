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

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 
 * 
 * @author AlphaLemon <webmaster@alphalemon.com>
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