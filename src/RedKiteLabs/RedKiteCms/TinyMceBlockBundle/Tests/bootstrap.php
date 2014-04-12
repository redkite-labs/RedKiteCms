<?php

/*
 * This file is part of the RedKiteLabs CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
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

if (0 === strncasecmp(PHP_SAPI, 'cli', 3)) {
    set_include_path(__DIR__ . '/../vendor/propel/propel1'.PATH_SEPARATOR.get_include_path());
    set_include_path(__DIR__ . '/../vendor/phing/phing/classes'.PATH_SEPARATOR.get_include_path());
}

$redKiteCmsFolder = __DIR__ . '/../vendor/redkite-cms/redkite-cms-bundle/RedKiteLabs/RedKiteCms/RedKiteCmsBundle';
require_once $redKiteCmsFolder . '/Tests/Tools/RedKiteCmsPropelQuickBuilder.php';
if (class_exists('TypehintableBehavior')) {
    
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
    $builder = new \RedKiteCmsPropelQuickBuilder();
    $builder->getConfig()->setBuildProperty('behavior.typehintable.class', $class->getFileName());
    $builder->setSchema(file_get_contents($redKiteCmsFolder . '/Resources/config/schema.xml'));
    $builder->buildClasses();
    $builder->buildSQL(\Propel::getConnection());
}