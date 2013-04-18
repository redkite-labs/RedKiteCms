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
                                    'TemplateName'  => 'empty',
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

        $this->checkCms($crawler);
        $this->assertCount(1, $crawler->filter('#block_20'));
        $this->assertCount(1, $crawler->filter('#block_20')->filter('[data-name="block_20"]'));
        $this->assertCount(1, $crawler->filter('#block_20')->filter('[data-editor="enabled"]'));
        $this->assertCount(1, $crawler->filter('#block_20')->filter('[data-hide-when-edit="false"]'));
        $this->assertCount(1, $crawler->filter('#block_20')->filter('[data-included=""]'));
        $this->assertCount(1, $crawler->filter('#block_20')->filter('[data-type="Text"]'));
        $this->assertCount(1, $crawler->filter('#block_20')->filter('[data-slot-name="content_title_1"]'));
        $this->assertCount(1, $crawler->filter('#block_20')->filter('[data-block-id="20"]'));
        $this->assertCount(1, $crawler->filter('#block_20')->filter('[data-content-editable="true"]'));
        $this->checkIncludedBlock($crawler);
        $this->assertCount(42, $crawler->filter('[data-editor="enabled"]'));
    }

    public function testMovingThroughPages()
    {
        $menu = '<ul class="business-menu">
            <li><a href="this-is-a-website-fake-page">home</a></li>
            <li><a href="page1">Another page</a></li>
        </ul>';

        $block = $this->blockRepository->fromPK(2);
        $block->setType('Text');
        $block->setContent($menu);
        $block->save();
        
        $crawler = $this->client->request('GET', '/backend/en/index');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        
        $link = $crawler->selectLink('Another page')->link();
        $crawler = $this->client->click($link);
        
        $this->assertTrue($crawler->filter('html:contains("This is the AlphaLemon CMS background and usually it should be hide")')->count() == 0);
        $this->assertCount(1, $crawler->filter('#block_24'));
        $this->assertCount(1, $crawler->filter('#block_24')->filter('[data-name="block_24"]'));
        $this->assertCount(1, $crawler->filter('#block_24')->filter('[data-editor="enabled"]'));
        $this->assertCount(1, $crawler->filter('#block_24')->filter('[data-hide-when-edit="false"]'));
        $this->assertCount(1, $crawler->filter('#block_24')->filter('[data-included=""]'));
        $this->assertCount(1, $crawler->filter('#block_24')->filter('[data-type="Text"]'));
        $this->assertCount(1, $crawler->filter('#block_24')->filter('[data-slot-name="page_title"]'));
        $this->assertCount(1, $crawler->filter('#block_24')->filter('[data-block-id="24"]'));
        $this->assertCount(1, $crawler->filter('#block_24')->filter('[data-content-editable="true"]'));
        $this->checkIncludedBlock($crawler);
        $this->assertCount(23, $crawler->filter('[data-editor="enabled"]'));
    }

    public function testOpenPageFromPermalink()
    {
        $crawler = $this->client->request('GET', 'backend/en/this-is-a-website-fake-page');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    private function checkIncludedBlock($crawler)
    {
        $this->assertCount(0, $crawler->filter('#block_27'));
        $this->assertCount(1, $crawler->filter('[data-name="block_27"]'));
        $this->assertCount(1, $crawler->filter('[data-name="block_27"]')->filter('[data-included="1"]'));
        $this->assertCount(1, $crawler->filter('[data-name="block_27"]')->filter('[data-type="Link"]'));
        $this->assertCount(1, $crawler->filter('[data-name="block_27"]')->filter('[data-slot-name="1-0"]'));
    }
    
    private function checkCms($crawler)
    {
        $this->checkToolbar($crawler);
        $this->checkStylesheets($crawler);
        $this->checkJavascripts($crawler);
    }

    private function checkToolbar($crawler)
    {
        $this->assertEquals(1, $crawler->filter('#al_control_panel')->count());
        $this->check($crawler, '#al_start_editor', "Edit");
        $this->check($crawler, '#al_stop_editor', "Stop");
        $this->check($crawler, '#al_open_pages_panel', "Pages");
        $this->check($crawler, '#al_open_languages_panel', "Languages");
        $this->check($crawler, '#al_open_themes_panel', "Themes");
        $this->check($crawler, '#al_open_media_library', "Media Library");        
        $this->check($crawler, '#al_languages_navigator', "en");        
        $el = $crawler->filter('.al_deployer');
        $this->assertEquals(2, $el->count());
        $this->check($crawler, '#al_pages_navigator', "index");
        $this->check($crawler, '#al_available_languages', "English");
    }

    private function checkStylesheets($crawler)
    {
        $assets = $crawler->filter('link')->extract(array('href'));
        $this->assertGreaterThanOrEqual(5, $assets);
        $assets = array_filter($assets, 'self::ignoreAssetic');
        //TODO $this->assertCount(3, $assets);
    }

    private function checkJavascripts($crawler)
    {
        $assets = array_filter($crawler->filter('script')->extract(array('src')));
        $this->assertGreaterThanOrEqual(15, $assets);
        $assets = array_filter($assets, 'self::ignoreAssetic');
        $this->assertCount(14, $assets);
    }

    private function check($crawler, $element, $value)
    {
        $el = $crawler->filter($element);
        $this->assertEquals(1, $el->count());
        $this->assertEquals($value, trim($el->text()));
    }

    private static function ignoreAssetic($key)
    {
        return false !== strpos($key, 'bundles');
    }
}
