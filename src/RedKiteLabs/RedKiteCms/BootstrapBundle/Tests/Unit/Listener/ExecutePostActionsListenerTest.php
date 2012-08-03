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

namespace AlphaLemon\BootstrapBundle\Tests\Unit\Listener;

use AlphaLemon\BootstrapBundle\Core\Listener\ExecutePostActionsListener;
use AlphaLemon\BootstrapBundle\Tests\TestCase;


/**
 * ExecutePostActionsListenerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class ExecutePostActionsListenerTest extends TestCase
{
    private $scriptFactory = null;

    protected function setUp()
    {
        parent::setUp();

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->container->expects($this->once())
             ->method('getParameter')
             ->will($this->returnValue('base/path'));

        $this->scriptFactory = $this->getMock('AlphaLemon\BootstrapBundle\Core\Script\Factory\ScriptFactoryInterface');
        $this->listener = new ExecutePostActionsListener($this->container, $this->scriptFactory);
    }

    public function testAnExceptionIsThrownWhenANullValueIsGiven()
    {
        $script1 = $this->initScript();
        $script2 = $this->initScript();
        $this->scriptFactory->expects($this->exactly(2))
             ->method('createScript')
             ->will($this->onConsecutiveCalls($script1, $script2));

        $this->listener->onKernelRequest();
    }

    private function initScript()
    {
        $script = $this->getMock('AlphaLemon\BootstrapBundle\Core\Script\PostScriptInterface');
        $script->expects($this->once())
               ->method('setContainer')
               ->will($this->returnSelf());

        $script->expects($this->once())
               ->method('executeActionsFromFile');

        return $script;
    }
}