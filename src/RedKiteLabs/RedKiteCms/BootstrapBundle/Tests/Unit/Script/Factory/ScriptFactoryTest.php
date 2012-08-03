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

namespace AlphaLemon\BootstrapBundle\Tests\Unit\Script;

use AlphaLemon\BootstrapBundle\Core\Script\Factory\ScriptFactory;
use AlphaLemon\BootstrapBundle\Tests\TestCase;


/**
 * ScriptFactoryTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class ScriptFactoryTest extends TestCase
{
    private $scriptFactory = null;

    protected function setUp()
    {
        parent::setUp();

        $this->scriptFactory = new ScriptFactory('base/path');
    }

    /**
     * @expectedException \AlphaLemon\BootstrapBundle\Core\Exception\CreateScriptException
     * @expectedExceptionMessage ScriptFactory requires a not null string value to be able to create a new Script object
     */
    public function testAnExceptionIsThrownWhenANullValueIsGiven()
    {
        $this->scriptFactory->createScript(null);
    }

    /**
     * @expectedException \AlphaLemon\BootstrapBundle\Core\Exception\CreateScriptException
     * @expectedExceptionMessage ScriptFactory requires a not null string value to be able to create a new Script object
     */
    public function testAnExceptionIsThrownWhenAnInvalidValueIsGiven()
    {
        $this->scriptFactory->createScript(array('fake'));
    }

    /**
     * @expectedException \AlphaLemon\BootstrapBundle\Core\Exception\CreateScriptException
     * @expectedExceptionMessage \AlphaLemon\BootstrapBundle\Core\Script\fakeScript class has not been found
     */
    public function testAnExceptionIsThrownWhenTheClassDoesNotExist()
    {
        $this->scriptFactory->createScript('fake');
    }

    public function testTheScriptObjectHasBeenCreated()
    {
        $this->assertNotNull($this->scriptFactory->createScript('PreBootInstaller'));
    }
}