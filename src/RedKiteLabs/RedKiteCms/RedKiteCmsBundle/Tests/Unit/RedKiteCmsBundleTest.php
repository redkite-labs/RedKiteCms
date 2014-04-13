<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Repository\Propel;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\DependencyInjection\Compiler\RegisterCmsListenersPass;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Compiler\BlocksCompilerPass;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\RedKiteCmsBundle;

/**
 * RedKiteCmsBundleTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class RedKiteCmsBundleTest extends TestCase
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
             ->with(new BlocksCompilerPass())
        ;
        
        $redKiteCmsBundle = new RedKiteCmsBundle();
        $redKiteCmsBundle->build($containerBuilder);
    }
}