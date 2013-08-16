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

/**
 * Defines the Script object to execute the install actions when the kernel has been booted
 * 
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class PostBootInstallerScript extends Base\BasePostScript
{
    /**
     * {@inheritdoc}
     */
    public function executeActions(array $actionManagers)
    {
        $this->doExecuteActions('packageInstalledPostBoot', $actionManagers);
    }
}