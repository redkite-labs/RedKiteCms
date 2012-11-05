<?php
/**
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
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

namespace AlphaLemon\ThemeEngineBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use AlphaLemon\ThemeEngineBundle\Core\Rendering\Compiler\RegisterRenderingListenersPass;
use AlphaLemon\ThemeEngineBundle\Core\Compiler\AlThemesCollectionCompilerPass;

class AlphaLemonThemeEngineBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterRenderingListenersPass());
        $container->addCompilerPass(new AlThemesCollectionCompilerPass());
    }
}
