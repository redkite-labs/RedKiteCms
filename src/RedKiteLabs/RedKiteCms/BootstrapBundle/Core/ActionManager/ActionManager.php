<?php
/*
 * This file is part of the RedKiteLabsBootstrapBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
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
 * Implements all the methods declared by ActionManagerInterface executing a NOP action
 * 
 * @author RedKite Labs <info@redkite-labs.com>
 */
abstract class ActionManager implements ActionManagerInterface
{
    /**
     * {@inheritdoc] 
     */
    public function packageInstalledPreBoot()
    {
    }

    /**
     * {@inheritdoc] 
     */
    public function packageUninstalledPreBoot()
    {
    }

    /**
     * {@inheritdoc] 
     */
    public function packageInstalledPostBoot(ContainerInterface $container)
    {
    }

    /**
     * {@inheritdoc] 
     */
    public function packageUninstalledPostBoot(ContainerInterface $container)
    {
    }
}