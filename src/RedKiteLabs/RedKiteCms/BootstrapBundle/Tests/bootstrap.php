<?php

/*
 * This file is part of the RedKiteLabs CMS Application and it is distributed
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
