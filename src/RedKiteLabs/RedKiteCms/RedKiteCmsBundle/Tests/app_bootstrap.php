<?php
if (!@include __DIR__ . '/../../../../../autoload.php') {
    die(<<<'EOT'
You must set up the project dependencies, run the following commands:
wget http://getcomposer.org/composer.phar
php composer.phar install
EOT
    );
}

if (0 === strncasecmp(PHP_SAPI, 'cli', 3)) {
    set_include_path(__DIR__ . '/../../../../../propel/propel1'.PATH_SEPARATOR.get_include_path());
    set_include_path(__DIR__ . '/../../../../../phing/phing/classes'.PATH_SEPARATOR.get_include_path());
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
$builder->buildSQL(\Propel::getConnection());

require_once __DIR__ . '/../../../../../../app/bootstrap.php.cache';
