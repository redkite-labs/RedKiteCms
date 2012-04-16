<?php
/*
 * This file is part of the BusinessDropCapBundle and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 * 
 * @license    GPL LICENSE Version 2.0
 * 
 */

namespace AlphaLemon\ElFinderBundle\Core\Listener; 

use AlphaLemon\BootstrapBundle\Core\Event\PackageInstalledEvent;

/**
 * Manipulates the block's editor response when the editor has been rendered 
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class PackageInstalledListener 
{
    public function onPackageInstalled(PackageInstalledEvent $event)
    {
        chdir(__DIR__ . '/../../');
        
        $process = new Process('git submodule init');
        $process->run();
        
        $process = new Process('git submodule update');
        $process->run();
    }
}
