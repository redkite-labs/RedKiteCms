<?php

/*
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
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
 * Based on https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Tests/bootstrap.php which gets all the credits
 */

if (!@include __DIR__ . '/../vendor/autoload.php') {
    die(<<<'EOT'
You must set up the project dependencies, run the following commands:
wget http://getcomposer.org/composer.phar
php composer.phar install
EOT
    );
}

//require_once __DIR__ . '/../vendor/propel/propel1/runtime/lib/Propel.php';
if (0 === strncasecmp(PHP_SAPI, 'cli', 3)) {
    set_include_path(__DIR__ . '/../vendor/propel/propel1'.PATH_SEPARATOR.get_include_path());
    set_include_path(__DIR__ . '/../vendor/phing/phing/classes'.PATH_SEPARATOR.get_include_path());
}

require_once __DIR__ . '/Tools/AlphaLemonPropelQuickBuilder.php';
if ( ! class_exists('TypehintableBehavior')) {
    die("TypehintableBehavior non installed: updated your vendors including devs");
}

$config = array("datasources" => array (
    "default" => array (
        "adapter" => "sqlite",
        "connection" => array
        (
            "dsn" => "sqlite::memory:",
            "classname" => "DebugPDO",
            "options" => array(),
            "attributes" => array (),
            "settings" => array (),
        )
    )
));
\Propel::setConfiguration($config);
\Propel::initialize();

$class = new \ReflectionClass('TypehintableBehavior');
$builder = new \AlphaLemonPropelQuickBuilder();
$builder->getConfig()->setBuildProperty('behavior.typehintable.class', $class->getFileName());
$builder->setSchema(file_get_contents(__DIR__.'/../Resources/config/schema.xml'));
$builder->buildClasses();
$builder->buildSQL(\Propel::getConnection());