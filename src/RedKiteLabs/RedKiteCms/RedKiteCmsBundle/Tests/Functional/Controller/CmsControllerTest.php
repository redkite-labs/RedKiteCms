<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\PageRepositoryPropel;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\SeoRepositoryPropel;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\BlockRepositoryPropel;

/**
 * CmsControllerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
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

        $this->pageRepository = new PageRepositoryPropel();
        $this->seoRepository = new SeoRepositoryPropel();
        $this->blockRepository = new BlockRepositoryPropel();
    }

    public function testOpeningAPageThatDoesNotExistShowsTheDefaultWelcomePage()
    {
        $crawler = $this->client->request('GET', 'backend/en/fake');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp(
            '/welcome_title|Welcome to RedKite CMS/si',
            $response->getContent()
        );
        $this->assertRegExp(
            '/welcome_background|This is the RedKite CMS background and usually it should be hide/si',
            $response->getContent()
        );
    }

    public function testExistingPageIsOpened()
    {
        $crawler = $this->client->request('GET', 'backend/en/index');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("This is the RedKiteCms background and usually it should be hide")')->count() == 0);

        $this->checkCms($crawler);
        $this->assertCount(1, $crawler->filter('#block_21'));
        $this->assertCount(1, $crawler->filter('#block_21')->filter('[data-name="block_21"]'));
        $this->assertCount(1, $crawler->filter('#block_21')->filter('[data-editor="enabled"]'));
        $this->assertCount(1, $crawler->filter('#block_21')->filter('[data-hide-when-edit="false"]'));
        $this->assertCount(1, $crawler->filter('#block_21')->filter('[data-included=""]'));
        $this->assertCount(1, $crawler->filter('#block_21')->filter('[data-type="Text"]'));
        $this->assertCount(1, $crawler->filter('#block_21')->filter('[data-slot-name="content_title_1"]'));
        $this->assertCount(1, $crawler->filter('#block_21')->filter('[data-block-id="21"]'));
        $this->assertCount(1, $crawler->filter('#block_21')->filter('[data-content-editable="true"]'));
        $this->checkIncludedBlock($crawler);
        $this->assertGreaterThan(0, count($crawler->filter('[data-editor="enabled"]')));
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
        
        $this->assertTrue($crawler->filter('html:contains("This is the RedKiteCms background and usually it should be hide")')->count() == 0);
        $this->assertCount(1, $crawler->filter('#block_25'));
        $this->assertCount(1, $crawler->filter('#block_25')->filter('[data-name="block_25"]'));
        $this->assertCount(1, $crawler->filter('#block_25')->filter('[data-editor="enabled"]'));
        $this->assertCount(1, $crawler->filter('#block_25')->filter('[data-hide-when-edit="false"]'));
        $this->assertCount(1, $crawler->filter('#block_25')->filter('[data-included=""]'));
        $this->assertCount(1, $crawler->filter('#block_25')->filter('[data-type="Text"]'));
        $this->assertCount(1, $crawler->filter('#block_25')->filter('[data-slot-name="page_title"]'));
        $this->assertCount(1, $crawler->filter('#block_25')->filter('[data-block-id="25"]'));
        $this->assertCount(1, $crawler->filter('#block_25')->filter('[data-content-editable="true"]'));
        $this->checkIncludedBlock($crawler);
        $this->assertGreaterThan(0, count($crawler->filter('[data-editor="enabled"]')));
    }

    public function testOpenPageFromPermalink()
    {
        $crawler = $this->client->request('GET', 'backend/en/this-is-a-website-fake-page');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    private function checkIncludedBlock($crawler)
    {
        $this->assertCount(0, $crawler->filter('#block_28'));
        $this->assertCount(1, $crawler->filter('[data-name="block_28"]'));
        $this->assertCount(3, $crawler->filter('[data-name="block_28"]')->filter('[data-included="1"]'));
        $this->assertCount(2, $crawler->filter('[data-name="block_28"]')->filter('[data-type="Link"]'));
        $this->assertCount(1, $crawler->filter('[data-name="block_28"]')->filter('[data-slot-name="1-0"]'));
    }
    
    private function checkCms($crawler)
    {
        $this->checkToolbar($crawler);
        $this->checkStylesheets($crawler);
        $this->checkJavascripts($crawler);
    }

    private function checkToolbar($crawler)
    {
        $this->assertEquals(1, $crawler->filter('.rk-control-panel')->count());
        $this->assertEquals(1, $crawler->filter('#rk-up')->count());
        $this->assertEquals(1, $crawler->filter('#rk-down')->count());
        $this->assertEquals(1, $crawler->filter('.rk-control-panel-mini')->count());
        $this->assertEquals(1, $crawler->filter('#rk-control-panel-full')->count());
        $this->assertEquals(2, $crawler->filter('.rk-start-editor')->count());
        $this->assertEquals(2, $crawler->filter('.rk-stop-editor')->count());
        $this->assertEquals(1, $crawler->filter('#rk-navigation-minimized')->count());
        $this->assertEquals(1, $crawler->filter('.rk-user')->count());
        $this->assertEquals(1, $crawler->filter('.rk-navigation-panel')->count());
        $this->assertEquals(2, $crawler->filter('.rk-languages-navigator-box')->count());
        $this->assertEquals(2, $crawler->filter('.rk-pages-navigator-box')->count());
        $this->assertEquals(1, $crawler->filter('.rk-commands')->count());
        $this->assertEquals(1, $crawler->filter('#rk-navigation-full-container')->count());        
        $this->check($crawler, '#al_open_pages_panel', "/cms_controller_label_pages|Pages/si");
        $this->check($crawler, '#al_open_languages_panel', "/cms_controller_label_languages|Languages/si");
        $this->check($crawler, '#al_open_themes_panel', "/cms_controller_label_themes|Themes/si");
        $this->check($crawler, '#al_open_media_library', "/cms_controller_label_media_library|Media Library/si");  
        $el = $crawler->filter('.al_deployer');
        $this->assertEquals(2, $el->count());
        $this->check($crawler, '#al_available_languages', "/EnglishItalian/si");
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
        //TODO $this->assertCount(14, $assets);
    }

    private function check($crawler, $element, $value, $elements = 1)
    {
        $el = $crawler->filter($element);
        $this->assertEquals($elements, $el->count());
        $this->assertRegExp(
            $value,
            trim($el->text())
        );
    }

    private static function ignoreAssetic($key)
    {
        return false !== strpos($key, 'bundles');
    }
}
