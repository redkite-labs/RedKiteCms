<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use RedKiteLabs\RedKiteCmsBundle\DependencyInjection\Compiler\RegisterCmsListenersPass;
use RedKiteLabs\RedKiteCmsBundle\Core\Compiler\AlBlocksCompilerPass;

/**
 * RedKiteCmsBundle
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class RedKiteCmsBundle extends Bundle
{

    /**
     * @inheritdoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterCmsListenersPass());
        $container->addCompilerPass(new AlBlocksCompilerPass());
    }
}
