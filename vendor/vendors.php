#!/usr/bin/env php
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

/**
 * Based on https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/vendor/vendor.php which gets all the credits
 */

set_time_limit(0);

if (isset($argv[1])) {
    $_SERVER['SYMFONY_VERSION'] = $argv[1];
}

$vendorDir = __DIR__;
$deps = array(
    array('symfony', 'http://github.com/symfony/symfony', isset($_SERVER['SYMFONY_VERSION']) ? $_SERVER['SYMFONY_VERSION'] : 'origin/master', ''),
    array('twig', 'http://github.com/fabpot/Twig.git', 'origin/master', ''),
    array('PropelBundle', 'https://github.com/propelorm/PropelBundle.git', 'origin/2.0', 'Propel'),
    array('propel', 'http://github.com/propelorm/Propel.git', 'origin/master', ''),
    array('propel-behavior', 'http://github.com/willdurand/TypehintableBehavior.git', 'origin/master', ''),
    array('phing', 'http://github.com/Xosofox/phing.git', 'origin/master', ''),
    array('ThemeEngineBundle', 'http://github.com/alphalemon/ThemeEngineBundle.git', 'origin/master', 'AlphaLemon'),
    array('PageTreeBundle', 'http://github.com/alphalemon/PageTreeBundle.git', 'origin/master', 'AlphaLemon'),
    array('AlValumUploaderBundle', 'http://github.com/alphalemon/AlValumUploaderBundle.git', 'origin/master', 'AlphaLemon'),
    array('AlphaLemonThemeBundle', 'http://github.com/alphalemon/AlphaLemonThemeBundle.git', 'origin/master', 'AlphaLemon/ThemeEngineBundle/Themes'),
    array('ElFinderBundle', 'http://github.com/alphalemon/ElFinderBundle.git', 'origin/master', 'AlphaLemon'),
    //array('AlphaLemonCmsBundle', 'http://github.com/alphalemon/AlphaLemonCmsBundle.git', 'origin/master', 'AlphaLemon'),
    array('FrontendBundle', 'http://github.com/alphalemon/FrontendBundle.git', 'origin/master', 'AlphaLemon'),
);

foreach ($deps as $dep) {
    list($name, $url, $rev, $extraPath) = $dep;

    echo "> Installing/Updating $name\n";

    if($extraPath != '') $extraPath .= '/';
    $installDir = $vendorDir.'/'.$extraPath.$name;
    if (!is_dir($installDir)) {
        $return = null;
        system(sprintf('git clone -q %s %s', escapeshellarg($url), escapeshellarg($installDir)), $return);
        if ($return > 0) {
            exit($return);
        }
    }

    $return = null;
    system(sprintf('cd %s && git fetch -q origin && git reset --hard %s', escapeshellarg($installDir), escapeshellarg($rev)), $return);
    if ($return > 0) {
        exit($return);
    }
}
