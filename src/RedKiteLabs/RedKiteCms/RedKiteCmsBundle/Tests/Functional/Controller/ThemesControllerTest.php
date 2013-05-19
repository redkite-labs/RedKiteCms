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
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel;

/**
 * ThemesControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class ThemesControllerTest extends WebTestCaseFunctional
{
    public static function setUpBeforeClass()
    {
        self::$languages = array(array('LanguageName'      => 'en',));

        self::$pages = array(array('PageName'      => 'index',
                                    'TemplateName'  => 'home',
                                    'IsHome'        => '1',
                                    'Permalink'     => 'this is a website fake page',
                                    'MetaTitle'         => 'page title',
                                    'MetaDescription'   => 'page description',
                                    'MetaKeywords'      => 'key'),
                            array('PageName'      => 'page1',
                                    'TemplateName'  => 'empty',
                                    'Permalink'     => 'page-1',
                                    'MetaTitle'         => 'page 1 title',
                                    'MetaDescription'   => 'page 1 description',
                                    'MetaKeywords'      => ''));
        self::populateDb();
    }

    public function testThemeChanger()
    {
        $params = array("themeName" => "BootbusinessThemeBundle");
        $crawler = $this->client->request('POST', 'backend/en/al_showThemeChanger', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(1, $crawler->filter('#al_theme_fixer'));
        $this->assertCount(1, $crawler->filter('#al-theme'));
        $this->assertCount(1, $crawler->filter('#al_theme_changer_form'));
        $this->assertCount(1, $crawler->filter('#al_template_changer'));
        $this->assertCount(1, $crawler->filter('#al_close_dialog'));
    }
/*
    public function testChangeTemplateFailsWhenAnyPagesHasBeenSelected()
    {
        $params = array("themeName" => "BootbusinessThemeBundle", "data" => "al_template=fullpage");
        $crawler = $this->client->request('POST', 'backend/en/al_fixTheme', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("Any page has been selected")')->count());
    }

    public function testChangeTemplateFailsWhenThePagesDoesNotExist()
    {
        $params = array("themeName" => "BootbusinessThemeBundle", "data" => "al_template=fullpage&al_page_to_fix=999");
        $crawler = $this->client->request('POST', 'backend/en/al_fixTheme', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("An error occourced: The following options are required: PageName,TemplateName. The options you gave are TemplateName")')->count());
    }

    public function testChangeTemplate()
    {
        $pageRepository = new AlPageRepositoryPropel();
        $page = $pageRepository->fromPK(2);
        $this->assertEquals('home', $page->getTemplateName());
        $params = array("themeName" => "BootbusinessThemeBundle", "data" => "al_template=fullpage&al_page_to_fix=2");
        $crawler = $this->client->request('POST', 'backend/en/al_fixTheme', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('fullpage', $page->getTemplateName());
    }*/
}
