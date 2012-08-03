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

namespace AlphaLemon\BootstrapBundle\Tests\Unit\ActionManager;

use AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerGenerator;
use AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManager;
use AlphaLemon\BootstrapBundle\Tests\TestCase;


class FakeActionManager extends ActionManager
{
}

/**
 * ActionManagerGeneratorTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
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
        $this->actionManagerGenerator->generate('AlphaLemon\BootstrapBundle\Tests\Unit\ActionManager\FakeManager');
        $this->assertNull($this->actionManagerGenerator->getActionManager());
        $this->assertNull($this->actionManagerGenerator->getActionManagerClass());
    }

    public function testNothingIsGeneratedWhenAnInvalidObjectClassIsGiven()
    {
        $scriptFactory = $this->getMock('AlphaLemon\BootstrapBundle\Core\Script\Factory\ScriptFactoryInterface');
        $this->actionManagerGenerator->generate($scriptFactory);
        $this->assertNull($this->actionManagerGenerator->getActionManager());
        $this->assertNull($this->actionManagerGenerator->getActionManagerClass());
    }

    public function testActionManagerGeneratorHasBeenInstantiatedFromAnActionManagerObject()
    {
        $actionManager = new FakeActionManager();
        $this->actionManagerGenerator->generate($actionManager);
        $this->assertEquals($actionManager, $this->actionManagerGenerator->getActionManager());
        $this->assertEquals('AlphaLemon\BootstrapBundle\Tests\Unit\ActionManager\FakeActionManager', $this->actionManagerGenerator->getActionManagerClass());
    }

    public function testActionManagerGeneratorHasBeenInstantiatedFromAnExistingClass()
    {
        $class = 'AlphaLemon\BootstrapBundle\Tests\Unit\ActionManager\FakeActionManager';
        $this->actionManagerGenerator->generate($class);
        $this->assertInstanceOf($class, $this->actionManagerGenerator->getActionManager());
        $this->assertEquals($class, $this->actionManagerGenerator->getActionManagerClass());
    }
}