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

namespace RedKiteLabs\BootstrapBundle\Tests\Unit\ActionManager;

use RedKiteLabs\BootstrapBundle\Core\ActionManager\ActionManagerGenerator;
use RedKiteLabs\BootstrapBundle\Core\ActionManager\ActionManager;
use RedKiteLabs\BootstrapBundle\Tests\TestCase;


class FakeActionManager extends ActionManager
{
}

/**
 * ActionManagerGeneratorTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class ActionManagerGeneratorTest extends TestCase
{
    private $actionManagerGenerator = null;

    protected function setUp()
    {
        parent::setUp();

        $this->actionManagerGenerator = new ActionManagerGenerator();
    }

    public function testNothingIsGeneratedWhenAnInvalidValueIsGiven()
    {
        $this->actionManagerGenerator->generate(array('fake'));
        $this->assertNull($this->actionManagerGenerator->getActionManager());
        $this->assertNull($this->actionManagerGenerator->getActionManagerClass());
    }

    public function testNothingIsGeneratedWhenANotExistentClassIsGiven()
    {
        $this->actionManagerGenerator->generate('RedKiteLabs\BootstrapBundle\Tests\Unit\ActionManager\FakeManager');
        $this->assertNull($this->actionManagerGenerator->getActionManager());
        $this->assertNull($this->actionManagerGenerator->getActionManagerClass());
    }

    public function testNothingIsGeneratedWhenAnInvalidObjectClassIsGiven()
    {
        $scriptFactory = $this->getMock('RedKiteLabs\BootstrapBundle\Core\Script\Factory\ScriptFactoryInterface');
        $this->actionManagerGenerator->generate($scriptFactory);
        $this->assertNull($this->actionManagerGenerator->getActionManager());
        $this->assertNull($this->actionManagerGenerator->getActionManagerClass());
    }

    public function testActionManagerGeneratorHasBeenInstantiatedFromAnActionManagerObject()
    {
        $actionManager = new FakeActionManager();
        $this->actionManagerGenerator->generate($actionManager);
        $this->assertEquals($actionManager, $this->actionManagerGenerator->getActionManager());
        $this->assertEquals('RedKiteLabs\BootstrapBundle\Tests\Unit\ActionManager\FakeActionManager', $this->actionManagerGenerator->getActionManagerClass());
    }

    public function testActionManagerGeneratorHasBeenInstantiatedFromAnExistingClass()
    {
        $class = 'RedKiteLabs\BootstrapBundle\Tests\Unit\ActionManager\FakeActionManager';
        $this->actionManagerGenerator->generate($class);
        $this->assertInstanceOf($class, $this->actionManagerGenerator->getActionManager());
        $this->assertEquals($class, $this->actionManagerGenerator->getActionManagerClass());
    }
}