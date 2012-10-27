<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Controller;

use AlphaLemon\AlphaLemonCmsBundle\Tests\WebTestCaseFunctional;

/**
 * BaseSecured
 *
 * @author alphalemon <webmaster@alphalemon.com>
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
                'environment' => 'alcms_test',
                'debug'       => true,
            ),
            $credentials
        );

        return $client;
    }
}
