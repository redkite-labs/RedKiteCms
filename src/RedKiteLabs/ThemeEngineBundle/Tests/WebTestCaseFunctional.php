<?php
/*
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

namespace AlphaLemon\ThemeEngineBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * WebTestCaseFunctional
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class WebTestCaseFunctional extends WebTestCase
{
    protected $client;

    protected function setUp()
    {
        $this->client = static::createClient(array(
                'environment' => 'alcms_test',
                'debug'       => true,
            ),
            array(
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW' => 'admin',
            ) );

        $activeThemeManager = $this->client->getContainer()->get('alphalemon_theme_engine.active_theme');
        $activeThemeManager->writeActiveTheme('BusinessWebsiteThemeBundle');
    }
}
