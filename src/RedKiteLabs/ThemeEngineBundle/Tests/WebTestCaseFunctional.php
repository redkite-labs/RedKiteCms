<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\ThemeEngineBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * WebTestCaseFunctional
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class WebTestCaseFunctional extends WebTestCase
{
    protected $client;

    protected function setUp()
    {
        $this->client = static::createClient(
            array(
                'environment' => 'test',
                'debug'       => true,
            )
        );

        $activeThemeManager = $this->client->getContainer()->get('red_kite_labs_theme_engine.active_theme');
        $activeThemeManager->writeActiveTheme('BusinessWebsiteThemeBundle');
    }
}
