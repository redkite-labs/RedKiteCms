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
 * Defines the methods to execute one or more console commands
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface AlCommandsProcessorInterface
{
    /**
     * Executes a console command
     *
     * @param string $cmd
     * @param \Closure $closure
     * @return int
     */
    public function executeCommand($cmd, \Closure $closure = null);

    /**
     * Executes the console commands defined in the $commands array
     *
     * @param array $commands
     * @param \Closure $closure A global closure used when the specific one has not been defined
     * @throws \RuntimeException
     */
    public function executeCommands(array $commands, \Closure $closure = null);
}
