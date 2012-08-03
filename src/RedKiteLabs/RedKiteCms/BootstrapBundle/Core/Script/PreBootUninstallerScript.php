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

namespace AlphaLemon\BootstrapBundle\Core\Script;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerInterface;
use AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerFactoryInterface;

/**
 * Defines the Script object to execute the uninstall actions when the kernel is not booted yet
 * 
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class PreBootUninstallerScript extends Base\BaseScript
{
    /**
     * {@inheritdoc}
     */
    public function executeActions(array $actionManagers)
    {
        $actions = $this->doExecuteActions('packageUninstalledPreBoot', $actionManagers);
        $this->writePostActions('.PostUninstall', $actions['post']);
    }
}