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
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlSeoRepositoryPropel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel;

/**
 * CmsControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class CmsControllerTest extends WebTestCaseFunctional
{
    private $pageRepository;
    private $seoRepository;
    private $blockRepository;

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
                                    'TemplateName'  => 'fullpage',
                                    'Permalink'     => 'page-1',
                                    'MetaTitle'         => 'page 1 title',
                                    'MetaDescription'   => 'page 1 description',
                                    'MetaKeywords'      => ''));
        self::populateDb();
    }

    protected function setUp()
    {
        parent::setUp();

        $this->pageRepository = new AlPageRepositoryPropel();
        $this->seoRepository = new AlSeoRepositoryPropel();
        $this->blockRepository = new AlBlockRepositoryPropel();
    }

    public function testOpeningAPageThatDoesNotExistShowsTheDefaultWelcomePage()
    {
        $crawler = $this->client->request('GET', 'backend/en/fake');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Welcome to AlphaLemon CMS")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("This is the AlphaLemon CMS background and usually it should be hide")')->count() > 0);
    }

    public function testExistingPageIsOpened()
    {
        $crawler = $this->client->request('GET', 'backend/en/index');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("This is the AlphaLemon CMS background and usually it should be hide")')->count() == 0);

        $expectedStylesheets = array
        (
            "/bundles/businesswebsitetheme/css/reset.css",
            "/bundles/businesswebsitetheme/css/layout.css",
            "/bundles/businesswebsitetheme/css/style.css",
            "/bundles/businesswebsitetheme/css/al_fix_style.css",
            "/bundles/businesswebsitetheme/css/cms_fix.css",
            "/bundles/businessmenu/css/business-menu.css",
            "/bundles/businesscarousel/css/business-carousel.css",
            "/bundles/businesscarousel/css/business-carousel-editor.css",
            "/bundles/businessslider/css/business-slider.css",
            "/bundles/businessdropcap/css/business-dropcap.css",
            "/bundles/businessdropcap/css/business-dropcap-editor.css",
        );

        $expectedJavascripts = array
        (
            "/bundles/businesswebsitetheme/js/cufon-yui.js",
            "/bundles/businesswebsitetheme/js/al-cufon-replace.js",
            "/bundles/businesswebsitetheme/js/Swis721_Cn_BT_400.font.js",
            "/bundles/businesswebsitetheme/js/Swis721_Cn_BT_700.font.js",
            "/bundles/businesswebsitetheme/js/jquery.easing.1.3.js",
            "/bundles/businesswebsitetheme/js/jcarousellite.js",
            "/bundles/businesscarousel/js/carousel.js",
            "/bundles/businessslider/js/tms-0.3.js",
            "/bundles/businessslider/js/tms_presets.js",
            "/bundles/businessslider/js/slider.js",
        );

        $this->checkCms($crawler, $expectedStylesheets, $expectedJavascripts);
        $this->assertEquals(1, $crawler->filter('#block_1')->count());
        $this->assertEquals(1, $crawler->filter('.al_logo')->count());
        $this->assertEquals(1, $crawler->filter('#block_2')->count());
        $this->assertEquals(1, $crawler->filter('.al_nav_menu')->count());
        $this->assertEquals(1, $crawler->filter('#block_16')->count());
        $this->assertEquals(1, $crawler->filter('.al_top_section_2')->count());
        $this->assertEquals(1, $crawler->filter('#block_11')->count());
        $this->assertEquals(1, $crawler->filter('.al_copyright_box')->count());
        $this->assertEquals(22, $crawler->filter('.al_editable')->count());
    }

    public function testMovingThroughPages()
    {
        $menu = '<ul class="business-menu">
            <li><a href="this-is-a-website-fake-page">home</a></li>
            <li><a href="page1">page1</a></li>
        </ul>';

        $block = $this->blockRepository->fromPK(2);
        $block->setType('Text');
        $block->setContent($menu);
        $block->save();

        $crawler = $this->client->request('GET', 'backend/en/index');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $link = $crawler->selectLink('page1')->link();
        $crawler = $this->client->click($link);

        $expectedStylesheets = array
        (
            "/bundles/businesswebsitetheme/css/reset.css",
            "/bundles/businesswebsitetheme/css/layout.css",
            "/bundles/businesswebsitetheme/css/style.css",
            "/bundles/businesswebsitetheme/css/al_fix_style.css",
        );

        $expectedJavascripts = array
        (
            "/bundles/businesswebsitetheme/js/cufon-yui.js",
            "/bundles/businesswebsitetheme/js//al-cufon-replace.js",
            "/bundles/businesswebsitetheme/js/Swis721_Cn_BT_400.font.js",
            "/bundles/businesswebsitetheme/js/Swis721_Cn_BT_700.font.js",
            "/bundles/businesswebsitetheme/js/tabs.js",
        );

        $this->checkCms($crawler, $expectedStylesheets, $expectedJavascripts);
        $this->assertTrue($crawler->filter('html:contains("This is the AlphaLemon CMS background and usually it should be hide")')->count() == 0);
        $this->assertEquals(1, $crawler->filter('#block_1')->count());
        $this->assertEquals(1, $crawler->filter('.al_logo')->count());
        $this->assertEquals(1, $crawler->filter('#block_2')->count());
        $this->assertEquals(1, $crawler->filter('.al_nav_menu')->count());
        $this->assertEquals(1, $crawler->filter('.al_page_content')->count());
        $this->assertEquals(1, $crawler->filter('#block_11')->count());
        $this->assertEquals(1, $crawler->filter('.al_copyright_box')->count());
        $this->assertEquals(12, $crawler->filter('.al_editable')->count());

        $link = $crawler->selectLink('home')->link();
        $crawler = $this->client->click($link);
    }

    public function testOpenPageFromPermalink()
    {
        $crawler = $this->client->request('GET', 'backend/en/this-is-a-website-fake-page');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    private function checkCms($crawler, $expectedStylesheets, $expectedJavascripts)
    {
        $this->checkToolbar($crawler);
        $this->checkStylesheets($crawler, $expectedStylesheets);
        $this->checkJavascripts($crawler, $expectedJavascripts);
    }

    private function checkToolbar($crawler)
    {
        $this->assertEquals(1, $crawler->filter('#al_toolbar')->count());
        $this->check($crawler, '#al_start_editor', "Edit");
        $this->check($crawler, '#al_stop_editor', "Stop");
        $this->check($crawler, '#al_open_pages_panel', "Pages");
        $this->check($crawler, '#al_open_languages_panel', "Languages");
        $this->check($crawler, '#al_open_themes_panel', "Themes");
        $this->check($crawler, '#al_open_media_library', "Media Library");
        $this->check($crawler, '#al_deploy_site', "Deploy");
        $this->check($crawler, '#al_languages_navigator', "en");
        $this->check($crawler, '#al_pages_navigator', "indexpage1");
        $this->check($crawler, '#al_available_languages', "English");
    }

    private function checkStylesheets($crawler, $expectedAssets)
    {
        $assets = $crawler->filter('link')->extract(array('href'));
        $assets = array_filter($assets, 'self::ignoreAssetic');
        $this->assertEquals(count($expectedAssets), count($assets));
        $this->assertEquals(0, count(array_diff($assets, $expectedAssets)));
    }

    private function checkJavascripts($crawler, $expectedAssets)
    {
        $assets = array_filter($crawler->filter('script')->extract(array('src')));
        $assets = array_filter($assets, 'self::ignoreAssetic');
        $this->assertEquals(count($expectedAssets), count($assets));
        $this->assertEquals(0, count(array_diff($assets, $expectedAssets)));
    }

    private function check($crawler, $element, $value)
    {
        $el = $crawler->filter($element);
        $this->assertEquals(1, $el->count());
        $this->assertEquals($value, $el->text());
    }

    private static function ignoreAssetic($key)
    {
        return false !== strpos($key, 'bundles');
    }
}
