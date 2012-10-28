<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Repository\Propel;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\AlphaLemonCmsBundle;
use AlphaLemon\AlphaLemonCmsBundle\DependencyInjection\Compiler\RegisterCmsListenersPass;
use AlphaLemon\AlphaLemonCmsBundle\Core\Compiler\AlBlocksCompilerPass;

/**
 * AlphaLemonCmsBundleTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
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