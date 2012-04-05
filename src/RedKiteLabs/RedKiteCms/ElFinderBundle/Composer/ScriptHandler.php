<?php

namespace AlphaLemon\ElFinderBundle\Composer;

use Composer\Script\Event;
use Symfony\Component\Process\Process;

class ScriptHandler
{
    public static function installElFinderLibrary($event)
    {
        chdir(__DIR__ . '/../');
        
        $process = new Process('git submodule init');
        $process->run();
        
        $process = new Process('git submodule update');
        $process->run();
    }
}
