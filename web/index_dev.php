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

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !in_array(@$_SERVER['REMOTE_ADDR'], array(
        '127.0.0.1',
        '::1',
    ))
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app["debug"] = true;

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