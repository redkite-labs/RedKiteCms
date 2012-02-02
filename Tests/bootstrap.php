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
 * Based on https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Tests/bootstrap.php which gets all the credits
 */

if (file_exists($file = __DIR__.'/autoload.php')) {
    require_once $file;
} elseif (file_exists($file = __DIR__.'/autoload.php.dist')) {
    require_once $file;
}

if (class_exists('PropelQuickBuilder') && class_exists('TypehintableBehavior')) {
    $class = new \ReflectionClass('TypehintableBehavior');
    $builder = new \PropelQuickBuilder();
    $builder->getConfig()->setBuildProperty('behavior.typehintable.class', $class->getFileName());
    $builder->setSchema(file_get_contents(__DIR__.'/../Resources/config/schema.xml'));
    $builder->buildClasses();
    
    $builder = new \PropelQuickBuilder();
    $builder->getConfig()->setBuildProperty('behavior.typehintable.class', $class->getFileName());
    $builder->setSchema(file_get_contents(__DIR__.'/../vendor/AlphaLemon/ThemeEngineBundle/Resources/config/schema.xml'));
    $builder->buildClasses();
    
    $queries = explode(";", file_get_contents(__DIR__ . '/Resources/sql/database.sql'));
    mysql_connect('localhost', 'root', 'passera73');
    mysql_select_db('alphalemon_test');
    foreach($queries as $query)
    {
        $query = trim($query);
        if(!empty($query) != "") mysql_query($query);
    }
    mysql_close();
}