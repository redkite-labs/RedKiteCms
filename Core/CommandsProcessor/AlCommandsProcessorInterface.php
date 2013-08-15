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

namespace RedKiteLabs\RedKiteCmsBundle\Core\CommandsProcessor;

/**
 * Defines the methods to execute one or more console commands
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
interface AlCommandsProcessorInterface
{
    /**
     * Executes a console command
     *
     * @param  string   $cmd
     * @param  \Closure $closure
     * @return int
     *
     * @api
     */
    public function executeCommand($cmd, \Closure $closure = null);

    /**
     * Executes the console commands defined in the $commands array
     *
     * @param  array             $commands
     * @param  \Closure          $closure  A global closure used when the specific one has not been defined
     * @throws \RuntimeException
     *
     * @api
     */
    public function executeCommands(array $commands, \Closure $closure = null);
}
