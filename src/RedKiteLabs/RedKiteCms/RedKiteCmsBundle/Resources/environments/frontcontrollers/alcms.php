<?php

require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';
//require_once __DIR__.'/../app/bootstrap_cache.php.cache';
//require_once __DIR__.'/../app/AppCache.php';

use Symfony\Component\HttpFoundation\Request;

$kernel = new AppKernel('alcms', false);
$kernel->loadClassCache();
$kernel->handle(Request::createFromGlobals())->send();
