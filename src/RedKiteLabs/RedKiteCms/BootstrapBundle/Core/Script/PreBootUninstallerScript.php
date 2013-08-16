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

namespace RedKiteLabs\BootstrapBundle\Core\Script;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\BootstrapBundle\Core\ActionManager\ActionManagerInterface;
use RedKiteLabs\BootstrapBundle\Core\ActionManager\ActionManagerFactoryInterface;

/**
 * Defines the Script object to execute the uninstall actions when the kernel is not booted yet
 * 
 * @author RedKite Labs <webmaster@redkite-labs.com>
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