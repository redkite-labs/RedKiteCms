<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AlphaLemon\ElFinderBundle\Composer;

use Composer\Script\Event;
use Symfony\Component\Process\Process;

/**
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class ScriptHandler
{
    public static function installElFinderLibrary($event)
    {
        $process = new Process('git submodule init');
        $process->run();
        
        $process = new Process('git submodule update');
        $process->run();
    }
}
