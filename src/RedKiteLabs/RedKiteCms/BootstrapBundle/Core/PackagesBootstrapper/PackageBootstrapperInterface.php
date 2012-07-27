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

namespace AlphaLemon\BootstrapBundle\Core\PackagesBootstrapper;

/**
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
interface PackageBootstrapperInterface {
    
    public function executeInstallActionPreBoot($bundleName, $actionManager);
    
    public function executeUninstallActionPreBoot($bundleName, $actionManagerClass);
    
    public function executeInstallActionPostBoot($bundleName, $actionManager);
    
    public function executeUninstallActionPostBoot($bundleName, $actionManager);
    
    public function executePostBootActions();
    
    public function writePostActions();
    
    public function writeFailedActions($fileName);

    public function executeFailedActions($action);
    
}