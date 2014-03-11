<?php
/*
 * This file is part of the RedKite CMS InstallerBundle and it is distributed
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

use RedKiteLabs\RedKiteCms\InstallerBundle\Core\DsnBuilder\GenericDsnBuilder;

/**
 * GenericDsnBuilderTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class GenericDsnBuilderTest extends BaseDsnBuilderTester
{
    public function dsnProvider()
    {
        return array(
            array(
                array(
                    'driver' => 'mysql',
                    'host' => 'localhost',
                    'database' => 'redkite',
                    'port' => '3306',
                    'user' => 'root',
                    'password' => 'mys3cret',
                ),
                'mysql:host=localhost',
                'mysql:host=localhost;dbname=redkite',
                '%rkcms_database_driver%:host=%rkcms_database_host%;dbname=%rkcms_database_name%',
            ),
        );
    }
    
    protected function setUpDsnBuilder(array $options) 
    {
        return new GenericDsnBuilder($options);
    }
}