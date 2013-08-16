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

namespace RedKiteLabs\BootstrapBundle\Tests\Unit\Script;

use RedKiteLabs\BootstrapBundle\Core\Script\Factory\ScriptFactory;
use RedKiteLabs\BootstrapBundle\Tests\TestCase;


/**
 * ScriptFactoryTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
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
     * @expectedException \RedKiteLabs\BootstrapBundle\Core\Exception\CreateScriptException
     * @expectedExceptionMessage ScriptFactory requires a not null string value to be able to create a new Script object
     */
    public function testAnExceptionIsThrownWhenANullValueIsGiven()
    {
        $this->scriptFactory->createScript(null);
    }

    /**
     * @expectedException \RedKiteLabs\BootstrapBundle\Core\Exception\CreateScriptException
     * @expectedExceptionMessage ScriptFactory requires a not null string value to be able to create a new Script object
     */
    public function testAnExceptionIsThrownWhenAnInvalidValueIsGiven()
    {
        $this->scriptFactory->createScript(array('fake'));
    }

    /**
     * @expectedException \RedKiteLabs\BootstrapBundle\Core\Exception\CreateScriptException
     * @expectedExceptionMessage \RedKiteLabs\BootstrapBundle\Core\Script\fakeScript class has not been found
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