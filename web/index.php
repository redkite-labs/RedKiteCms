<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$rootDir = __DIR__ . '/..';
$siteName = $_SERVER["HTTP_HOST"];
if (preg_match('/(.*?)\.local$/', $siteName, $match))
{
    $siteName = $match[1];
}

$configurationFile = sprintf('%s/app/data/%s/RedKiteCms.php', $rootDir, $siteName);
if (!file_exists($configurationFile)) {
    $configurationFile = $rootDir . "/app/RedKiteCms.php";
}

require_once($configurationFile);
$redKiteCms = new RedKiteCms($app);
$redKiteCms->bootstrap($rootDir, $siteName);

$app->run();
