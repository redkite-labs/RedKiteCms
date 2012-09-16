<?php
/*
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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\CommandsProcessor\AlCommandsProcessor;
use org\bovigo\vfs\vfsStream;

/**
 * AlBlockManagerFactoryItemTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
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

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage An error has occoured executing the "my:other:command" command
     */
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

        $this->commandsProcessor->executeCommands($commands, null, $process);
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
