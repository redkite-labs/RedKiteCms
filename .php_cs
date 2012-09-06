<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->exclude('Model')
    ->in(__DIR__)
;

return Symfony\CS\Config\Config::create()
    ->finder($finder)
;
