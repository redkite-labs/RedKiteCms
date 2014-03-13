<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Functional\Controller;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\WebTestCaseFunctional;

/**
 * BaseSecured
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class BaseSecured extends WebTestCaseFunctional
{
    protected function setUpClient(array $credentials = null)
    {
        if (null === $credentials) {
            $credentials = array(
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW' => 'admin',
            );
        }

        $client = static::createClient(
            array(
                'environment' => 'rkcms_test',
                'debug'       => true,
            ),
            $credentials
        );

        return $client;
    }
}
