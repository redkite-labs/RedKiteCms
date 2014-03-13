<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\CommandsProcessor;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * Processes console commands
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
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
     *
     * @api
     */
    public function __construct($consoleDir)
    {
        $this->consoleDir = $consoleDir;
        $phpFinder = new PhpExecutableFinder;
        $pathToPhp = $phpFinder->find();
        $this->php = $pathToPhp ? escapeshellarg($pathToPhp) : '';
        $this->console = realpath($this->consoleDir . '/console');
        if(empty($this->console)) $this->console = $this->consoleDir . '/console';
    }

    /**
     * Sets the console dir path
     *
     * @param  string              $consoleDir
     * @return AlCommandsProcessor
     *
     * @api
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
     *
     * @api
     */
    public function getConsoleDir()
    {
        return $this->consoleDir;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function executeCommand($cmd, \Closure $closure = null, Process $process = null)
    {
        try {
            $cmd = $this->php.' '.$this->console.' '.$cmd;
            if (null === $process) {
                $process =  new Process($cmd);
            } else {
                $process->setCommandLine($cmd);
            }

            return $process->run($closure);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function executeCommands(array $commands, \Closure $closure = null, Process $process = null)
    {
        foreach ($commands as $command => $commandClosure) {
            $currentClosure = (null !== $commandClosure) ? $commandClosure : $closure;
            $processResult = $this->executeCommand($command, $currentClosure, $process);
            if ($processResult === -1 || $processResult === 255) {
                return false;
            }
        }

        return true;
    }
}
