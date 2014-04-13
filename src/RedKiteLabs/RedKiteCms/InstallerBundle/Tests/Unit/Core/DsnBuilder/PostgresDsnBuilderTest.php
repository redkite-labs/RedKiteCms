<?php
/*
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT LICENSE. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT LICENSE
 *
 */

namespace RedKiteLabs\RedKiteCms\InstallerBundle\Tests\Unit\Core\DsnBuilder;

use RedKiteLabs\RedKiteCms\InstallerBundle\Core\DsnBuilder\PgsqlDsnBuilder;

/**
 * PostgresDsnBuilderTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class PostgresDsnBuilderTest extends BaseDsnBuilderTester
{
    public function dsnProvider()
    {
        return array(
            array(
                array(
                    'driver' => 'pgsql',
                    'host' => 'localhost',
                    'database' => 'redkite',
                    'port' => '5432',
                    'user' => 'postgres',
                    'password' => 'mys3cret',
                ),
                'pgsql:host=localhost;user=postgres;password=mys3cret',
                'pgsql:host=localhost;user=postgres;password=mys3cret;dbname=redkite',
                '%rkcms_database_driver%:host=%rkcms_database_host%;dbname=%rkcms_database_name%;user=%rkcms_database_user%;password=%rkcms_database_password%',
            ),
        );
    }
    
    protected function setUpDsnBuilder(array $options) 
    {
        return new PgsqlDsnBuilder($options);
    }
}