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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\CommandsProcessor;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * Processes console commands
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlCommandsProcessor implements AlCommandsProcessorInterface
{
    protected $php;
    protected $consoleDir;
    protected $console;

    /**
     * Constructor
     *
     * @param string
     */
    public function __construct($consoleDir)
    {
        $this->consoleDir = $consoleDir;
        $phpFinder = new PhpExecutableFinder;
        $this->php = escapeshellarg($phpFinder->find());
        $this->console = realpath($this->consoleDir . '/console');
        if(empty($this->console)) $this->console = $this->consoleDir . '/console';
    }

    /**
     * Sets the console dir path
     *
     * @param type $consoleDir
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\CommandsProcessor\AlCommandsProcessor
     */
    public function setConsoleDir($consoleDir)
    {
        $this->consoleDir = $consoleDir;

        return $this;
    }

    /**
     * Returns the console directory
     *
     * @return string
     */
    public function getConsoleDir()
    {
        return $this->consoleDir;
    }

    /**
     * {@inheritdoc}
     */
    public function executeCommand($cmd, \Closure $closure = null, Process $process = null)
    {
        $cmd = $this->php.' '.$this->console.' '.$cmd;
        if (null === $process) {
            $process =  new Process($cmd);
        } else {
            $process->setCommandLine($cmd);
        }

        return $process->run($closure);
    }

    /**
     * {@inheritdoc}
     */
    public function executeCommands(array $commands, \Closure $closure = null, Process $process = null)
    {
        foreach ($commands as $command => $commandClosure) {
            $currentClosure = (null !== $commandClosure) ? $commandClosure : $closure;
            $processResult = $this->executeCommand($command, $currentClosure, $process);
            if (-1 === $processResult) {
                throw new \RuntimeException(sprintf('An error has occoured executing the "%s" command', $command));
            }
        }

        return true;
    }
}
