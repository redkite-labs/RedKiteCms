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

namespace AlphaLemon\ThemeEngineBundle\Tests\Functional\Controller;

use AlphaLemon\ThemeEngineBundle\Tests\WebTestCaseFunctional;

/**
 * ThemesControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class ThemesControllerTest extends WebTestCaseFunctional
{
    public function testThemesPanel()
    {
        $crawler = $this->client->request('GET', 'backend/en/al_showThemes');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('#al_themes')->count());
        $this->assertEquals(1, $crawler->filter('#al_themes_table')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("BusinessWebsiteThemeBundle")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("title")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("BusinessWebsiteTheme")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("description")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("The Template\'s Monster Business Website Theme ported for AlphaLemon CMS")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("description")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("author")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Template Monster, AlphaLemon")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("website")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("http://aphalemon.com")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("license")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("MIT LICENSE")')->count());
    }

    public function testActivate()
    {
        $crawler = $this->client->request('GET', 'backend/en/al_activateTheme/FakeTheme');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        
        $this->assertEquals('FakeTheme', file_get_contents($this->client->getContainer()->getParameter('kernel.root_dir') . '/Resources/.tests_active_theme' ));
    }
}
