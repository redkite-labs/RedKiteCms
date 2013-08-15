<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Repository\Propel;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\AlphaLemonCmsBundle;
use RedKiteLabs\RedKiteCmsBundle\DependencyInjection\Compiler\RegisterCmsListenersPass;
use RedKiteLabs\RedKiteCmsBundle\Core\Compiler\AlBlocksCompilerPass;

/**
 * AlphaLemonCmsBundleTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlphaLemonCmsBundleTest extends TestCase
{
    public function testPdoConnectionInjectedBySetters()
    {
        $containerBuilder = 
            $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $containerBuilder
             ->expects($this->at(0))
             ->method('addCompilerPass')
             ->with(new RegisterCmsListenersPass())
        ;
        
        $containerBuilder
             ->expects($this->at(1))
             ->method('addCompilerPass')
             ->with(new AlBlocksCompilerPass())
        ;
        
        $this->alphaLemonCmsBundle = new AlphaLemonCmsBundle();
        $this->alphaLemonCmsBundle->build($containerBuilder);
    }
}