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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Block;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\CommandsProcessor\AlCommandsProcessor;
use org\bovigo\vfs\vfsStream;

/**
 * AlBlockManagerFactoryItemTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlCommandsProcessorTest extends TestCase
{
    private $commandsProcessor;

    protected function setUp()
    {
        $structure =
            array('app' =>
                array('console' => ""),
            );

        $this->root = vfsStream::setup('root', null, $structure);

        $this->commandsProcessor = new AlCommandsProcessor(vfsStream::url('root/app'));
    }

    public function testExecuteCommandReturnsFalseWhenAnExceptionOccours()
    {
        $process = $this->getMockBuilder('Symfony\Component\Process\Process')
                        ->disableOriginalConstructor()
                        ->getMock();
        $process->expects($this->once())
                ->method('run')
                ->will($this->throwException(new \RuntimeException()));
        $this->assertFalse($this->commandsProcessor->executeCommand('my:command', null, $process));
    }

    public function testExecuteCommandIsExecuted()
    {
        $process = $this->getMockBuilder('Symfony\Component\Process\Process')
                        ->disableOriginalConstructor()
                        ->getMock();
        $process->expects($this->once())
                ->method('run')
                ->will($this->returnValue(1));
        $this->assertEquals(1, $this->commandsProcessor->executeCommand('my:command', null, $process));
    }
    
    public function testANewProcessObjectIsInstantiate()
    {
        $this->assertEquals(1, $this->commandsProcessor->executeCommand('my:command'));
    }

    public function testWhenACommandFailsAnExceptionIsThrown()
    {
        $process = $this->getMockBuilder('Symfony\Component\Process\Process')
                        ->disableOriginalConstructor()
                        ->getMock();
        $process->expects($this->exactly(2))
                ->method('run')
                ->will($this->onConsecutiveCalls(1, -1));

        $commands = array(
            'my:command' => null,
            'my:other:command' => null,
        );

        $this->assertFalse($this->commandsProcessor->executeCommands($commands, null, $process));
    }

    public function testExecuteCommandsAreExecuted()
    {
        $process = $this->getMockBuilder('Symfony\Component\Process\Process')
                        ->disableOriginalConstructor()
                        ->getMock();
        $process->expects($this->exactly(2))
                ->method('run')
                ->will($this->returnValue(1));

        $commands = array(
            'my:command' => null,
            'my:other:command' => null,
        );
        $this->assertTrue($this->commandsProcessor->executeCommands($commands, null, $process));
    }

    public function testChangeConsoleDir()
    {
        $consoleDir = '/new/console/dir';
        $this->assertEquals($this->commandsProcessor, $this->commandsProcessor->setConsoleDir($consoleDir));
        $this->assertEquals($consoleDir, $this->commandsProcessor->getConsoleDir());
    }
}