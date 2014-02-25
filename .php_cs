<?php
/**
 * PHP Coding Standards Fixer Configuration file
 * @see http://cs.sensiolabs.org/
 */
$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->exclude(array('Model/map', 'Model/om', 'Tests', 'Resources', 'vendor'))
    ->in(__DIR__)
;

return Symfony\CS\Config\Config::create()
    ->finder($finder)
;
