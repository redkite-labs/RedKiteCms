<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Functional\Controller;

use RedKiteLabs\RedKiteCmsBundle\Tests\WebTestCaseFunctional;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlSeoRepositoryPropel;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel;

/**
 * PagesControllerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class PagesControllerTest extends WebTestCaseFunctional
{
    private $pageRepository;
    private $seoRepository;
    private $blockRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->pageRepository = new AlPageRepositoryPropel();
        $this->seoRepository = new AlSeoRepositoryPropel();
        $this->blockRepository = new AlBlockRepositoryPropel();
    }

    public function testFormElements()
    {
        $crawler = $this->client->request('GET', '/backend/en/al_showPages');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('#pages_pageName')->count());
        $this->assertEquals(1, $crawler->filter('#pages_template')->count());
        $this->assertEquals(1, $crawler->filter('#pages_isHome')->count());
        $this->assertEquals(1, $crawler->filter('#pages_isPublished')->count());
        $this->assertEquals(1, $crawler->filter('#seo_attributes_permalink')->count());
        $this->assertEquals(1, $crawler->filter('#seo_attributes_title')->count());
        $this->assertEquals(1, $crawler->filter('#seo_attributes_description')->count());
        $this->assertEquals(1, $crawler->filter('#seo_attributes_keywords')->count());
        $this->assertEquals(1, $crawler->filter('#seo_attributes_sitemapChangeFreq')->count());
        $this->assertEquals(1, $crawler->filter('#seo_attributes_sitemapPriority')->count());
        $this->assertEquals(1, $crawler->filter('#al_page_saver')->count());
        $this->assertEquals(1, $crawler->filter('#al_pages_list')->count());
        $this->assertEquals(1, $crawler->filter('#al_pages_list .al_element_selector')->count());
        $this->assertEquals(1, $crawler->filter('.rk-page-remover')->count());
    }
    
    /**
     * @dataProvider addFailsProvider
     */
    public function testAddPageFails($params, $message)
    {
        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertRegExp(
            $message,
            $response->getContent()
        );
    }
    
    public function addFailsProvider()
    {
        return array(
            array(
                array(
                    'page' => 'index',
                    'language' => 'en',
                    "pageName" => "al_temp"
                ),
                '/pages_controller_al_prefix_not_permitted|The prefix \[ al_ \] is not permitted to avoid conflicts with the application internal routes/si',
            ),
            array(
                array(
                    'page' => 'index',
                    'language' => 'en',
                    'templateName' => "home",
                    'permalink' => "page 1",
                    'title' => 'A title',
                    'description' => 'A description',
                    'keywords' => 'Some keywords'
                ),
                '/exception_invalid_page_name|The name to assign to the page cannot be null. Please provide a valid page name to add your page/si',
            ),
            array(
                array(
                    'page' => 'index',
                    'language' => 'en',
                    'pageName' => "page1",
                    'permalink' => "page 1",
                    'title' => 'A title',
                    'description' => 'A description',
                    'keywords' => 'Some keywords'
                ),
                '/exception_page_template_param_missing|The page requires at least a template. Please provide the template name to add your page/si',
            ),
        );
    }

    public function testAddPage()
    {
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageName' => "page1",
            'templateName' => "home",
            'permalink' => "page 1",
            'isPublished' => "0",
            'title' => 'A title',
            'description' => 'A description',
            'keywords' => 'Some keywords',
            'sitemapChangeFreq' => '',
            'sitemapPriority' => '',
        );

        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(4, count($json));
        $this->assertTrue(array_key_exists("key", $json[0]));
        $this->assertEquals("message", $json[0]["key"]);       
        $this->assertTrue(array_key_exists("value", $json[0])); 
        $this->assertRegExp(
            '/pages_controller_page_saved|The page has been successfully saved/si',
            $json[0]["value"]
        );
        $this->assertTrue(array_key_exists("key", $json[1]));
        $this->assertEquals("pages_list", $json[1]["key"]);
        $this->assertTrue(array_key_exists("value", $json[1]));
        $this->assertRegExp("/\<a[^\>]+data-page-id=\"2\"\>index\<\/a\>/s", $json[1]["value"]);        
        $this->assertRegExp("/\index/s", $json[1]["value"]);
        $this->assertRegExp("/\<a[^\>]+data-page-id=\"3\"\>page1\<\/a\>/s", $json[1]["value"]);
        $this->assertTrue(array_key_exists("key", $json[2]));
        $this->assertEquals("permalinks", $json[2]["key"]);
        $this->assertTrue(array_key_exists("value", $json[2]));
        $this->assertRegExp("/\<select id=\"al_page_name\"[^\>]+\>/s", $json[2]["value"]);
        $this->assertRegExp("/\<option value=\"1\" rel=\"page-1\"[\s]+?\>page-1/s", $json[2]["value"]);
        $this->assertRegExp("/\<option value=\"2\" rel=\"this-is-a-website-fake-page\"[\s]+?\>this-is-a-website-fake-page/s", $json[2]["value"]);
        $this->assertEquals("pages", $json[3]["key"]);
        $this->assertTrue(array_key_exists("value", $json[3])); 
        $this->assertRegExp("/\<ul class=\"dropdown-menu[^\>]+\>/s", $json[3]["value"]);
        $this->assertRegExp("/\<li id=\"2\"[^\>]+\>\<a href=\"#\"\>index\<\/a\>/s", $json[3]["value"]);
        $this->assertRegExp("/\<li id=\"3\"[^\>]+\>\<a href=\"#\"\>page1\<\/a\>/s", $json[3]["value"]);

        $page = $this->pageRepository->fromPk(3);
        $this->assertNotNull($page);
        $this->assertEquals('page1', $page->getPageName());
        $this->assertEquals('home', $page->getTemplateName());
        $this->assertEquals(0, $page->getIsHome());

        $seo = $this->seoRepository->fromPageAndLanguage(2, 3);
        $this->assertNotNull($seo);
        $this->assertEquals('page-1', $seo->getPermalink());
        $this->assertEquals('A title', $seo->getMetaTitle());
        $this->assertEquals('A description', $seo->getMetaDescription());
        $this->assertEquals('Some keywords', $seo->getMetaKeywords());

        // Repeated contents have not been added
        $pagesSlots = $this->retrievePageSlots();
        $this->assertEquals(count($pagesSlots), count($this->blockRepository->retrieveContents(2, 3)));

        $crawler = $this->client->request('GET', '/backend/en/page1');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(0, $crawler->filter('#block_1')); 
        $this->assertCount(1, $crawler->filter('#block_25'));
        $this->assertCount(1, $crawler->filter('#block_25')->filter('[data-name="block_25"]'));
        $this->assertCount(0, $crawler->filter('#block_31'));
        $this->assertCount(1, $crawler->filter('[data-name="block_30"]'));
    }

    public function testPageJustAddedSeoAttributes()
    {
        $params = array('pageId' => 3, 'languageId' => 2);
        $crawler = $this->client->request('POST', '/backend/en/al_loadSeoAttributes', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $json = json_decode($response->getContent(), true);
        $this->assertEquals("#pages_pageName", $json[0]["name"]);
        $this->assertEquals("page1", $json[0]["value"]);
        $this->assertEquals("#pages_template", $json[1]["name"]);
        $this->assertEquals("home", $json[1]["value"]);
        $this->assertEquals("#pages_isHome", $json[2]["name"]);
        $this->assertEquals("0", $json[2]["value"]);
        $this->assertEquals("#pages_isPublished", $json[3]["name"]);
        $this->assertEquals("0", $json[3]["value"]);
        $this->assertEquals("#seo_attributes_permalink", $json[4]["name"]);
        $this->assertEquals("page-1", $json[4]["value"]);
        $this->assertEquals("#seo_attributes_title", $json[5]["name"]);
        $this->assertEquals("A title", $json[5]["value"]);
        $this->assertEquals("#seo_attributes_description", $json[6]["name"]);
        $this->assertEquals("A description", $json[6]["value"]);
        $this->assertEquals("#seo_attributes_keywords", $json[7]["name"]);
        $this->assertEquals("Some keywords", $json[7]["value"]);
    }

    public function testAddPageFailsWhenThePageNameAlreadyExists()
    {
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageName' => "page1",
            'templateName' => "home",
            'isPublished' => "0",
            'permalink' => "page 1",
            'title' => 'A title',
            'description' => 'A description',
            'keywords' => 'Some keywords',
            'sitemapChangeFreq' => '',
            'sitemapPriority' => '',
        );

        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertRegExp(
            '/exception_page_already_exists|The web site already contains the page you are trying to add. Please use another name for that page/si',
            $response->getContent()
        );
    }

    public function testAddANewHomePage()
    {
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageName' => "page2",
            'templateName' => "home",
            'isPublished' => "0",
            'isHome' => '1',
            'permalink' => "page 2",
            'title' => 'A title',
            'description' => 'A description',
            'keywords' => 'Some keywords',
            'sitemapChangeFreq' => '',
            'sitemapPriority' => '',
        );

        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse(); 
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals(3, count($this->pageRepository->activePages()));
        $this->assertEquals(4, $this->pageRepository->homePage()->getId());

        // Previous home page has been degraded
        $page = $this->pageRepository->fromPk(2);
        $this->assertEquals(0, $page->getIsHome());

        $seo = $this->seoRepository->fromPageAndLanguage(2, 4);
        $this->assertNotNull($seo);

        // Repeated contents have not been added
        $this->assertCount(5, $this->blockRepository->retrieveContents(2, 4));
    }

    public function testAddNewPageWithATemplateDifferentThanTheOneOfCurrentPage()
    {
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageName' => "another-page",
            'templateName' => "empty",
            'permalink' => "another-page",
            'isPublished' => "0",
            'title' => 'A title',
            'description' => 'A description',
            'keywords' => 'Some keywords',
            'sitemapChangeFreq' => '',
            'sitemapPriority' => '',
        );
        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $crawler = $this->client->request('GET', '/backend/en/another-page');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(0, $crawler->filter('#block_1'));        
        $this->assertCount(1, $crawler->filter('#block_30'));
        $this->assertCount(1, $crawler->filter('#block_30')->filter('[data-name="block_30"]'));
        $this->assertCount(0, $crawler->filter('#block_31'));
        $this->assertCount(1, $crawler->filter('[data-name="block_31"]'));
    }

    public function testEditPage()
    {
        // Saves a link that contains the permalink we are going to change
        $block = $this->blockRepository->fromPK(15);
        $block->setContent('<a href="page-1">Go to page 1</a>');
        $block->save();

        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => 3,
                        'languageId' => 2,
                        'pageName' => "page1 edited",
                        'permalink' => "page-1 edited",);

        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $page = $this->pageRepository->fromPk(3);
        $this->assertEquals('page1-edited', $page->getPageName());

        $seo = $this->seoRepository->fromPk(2);
        $this->assertEquals('page-1-edited', $seo->getPermalink());
    }

    public function testPermalinksHaveBeenChanged()
    {
        $crawler = $this->client->request('GET', '/backend/en/index');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $link = $crawler->selectLink('Go to page 1')->link();
        $this->assertEquals('http://localhost/backend/en/page-1-edited', $link->getUri());
    }

    public function testEditPageToBePublishable()
    {
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageId' => 3,
            'languageId' => 2,
            'isPublished' => 1,
        );

        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $page = $this->pageRepository->fromPk(3);
        $this->assertEquals(1, $page->getIsPublished());
    }

    public function testChangeThePageTemplate()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => 3,
                        'languageId' => 2,
                        'templateName' => 'empty',);

        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $page = $this->pageRepository->fromPK(3);
        $this->assertEquals('empty', $page->getTemplateName());
    }

    /**
     * @dataProvider deleteFailsProvider
     */
    public function testDeletePageFails($params, $message)
    {
        $crawler = $this->client->request('POST', '/backend/en/al_deletePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertRegExp(
            $message,
            $response->getContent()
        );
    }
    
    public function deleteFailsProvider()
    {
        return array(
            array(
                array(
                    'page' => 'index',
                    'language' => 'en',
                    'pageId' => 'none',
                    'languageId' => 2,
                ),
                '/pages_controller_any_page_selected|Any page has been selected for removing/si',
            ),
            array(
                array(
                    'page' => 'index',
                    'language' => 'en',
                    'pageId' => 999,
                    'languageId' => 2,
                ),
                '/pages_controller_any_page_selected|Any page has been selected for removing/si',
            ),
            array(
                array(
                    'page' => 'index',
                    'language' => 'en',
                    'pageId' => 4,
                    'languageId' => 2
                ),
                '/exception_home_page_cannot_be_removed|It is not allowed to remove the website\'s home page. Promote another page as the home of your website, then remove this one/si',
            ),
        );
    }

    public function testDeletePage()
    {
        $page = $this->pageRepository->fromPk(2);
        $this->assertEquals(0, $page->getToDelete());
        
        $seo = $this->seoRepository->fromPageAndLanguage(2, 2);
        $this->assertNotNull($seo);
        
        $this->assertCount(17, $this->blockRepository->retrieveContents(2, 2));
        
        $params = array('page' => 'page-2-edited',
                        'language' => 'en',
                        'pageId' => 2,
                        'languageId' => 'none');

        $crawler = $this->client->request('POST', '/backend/en/al_deletePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(3, count($this->pageRepository->activePages()));

        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(4, count($json));
        $this->assertTrue(array_key_exists("key", $json[0]));
        $this->assertEquals("message", $json[0]["key"]);
        $this->assertTrue(array_key_exists("value", $json[0]));
        $this->assertRegExp(
            '/pages_controller_page_removed|The page has been successfully removed/si',
            $json[0]["value"]
        );
        $this->assertTrue(array_key_exists("key", $json[1]));
        $this->assertEquals("pages_list", $json[1]["key"]);
        $this->assertTrue(array_key_exists("value", $json[1]));
        $this->assertRegExp("/\<a[^\>]+data-page-id=\"3\"\>page1-edited\<\/a\>/s", $json[1]["value"]);
        $this->assertRegExp("/\<a[^\>]+data-page-id=\"4\"\>page2\<\/a\>/s", $json[1]["value"]);
        $this->assertTrue(array_key_exists("key", $json[2]));
        $this->assertEquals("permalinks", $json[2]["key"]);
        $this->assertTrue(array_key_exists("value", $json[2]));
        $this->assertRegExp("/\<option value=\"2\" rel=\"another-page\"[\s]+?\>another-page/s", $json[2]["value"]);
        $this->assertRegExp("/\<option value=\"3\" rel=\"page-2\"[\s]+?\>page-2/s", $json[2]["value"]);
        $this->assertEquals("pages", $json[3]["key"]);
        $this->assertTrue(array_key_exists("value", $json[3])); 
        $this->assertRegExp("/\<li id=\"5\"[^\>]+\>\<a href=\"#\"\>another-page\<\/a\>/s", $json[3]["value"]);
        $this->assertRegExp("/\<li id=\"3\"[^\>]+\>\<a href=\"#\"\>page1-edited\<\/a\>/s", $json[3]["value"]);
        $this->assertRegExp("/\<li id=\"4\"[^\>]+\>\<a href=\"#\"\>page2\<\/a\>/s", $json[3]["value"]);
        
        $page = $this->pageRepository->fromPk(2);
        $this->assertEquals(1, $page->getToDelete());

        $seo = $this->seoRepository->fromPageAndLanguage(2, 2);
        $this->assertNull($seo);
        
        $this->assertCount(0, $this->blockRepository->retrieveContents(2, 2));
    }

    public function testAddSomePages()
    {
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageName' => "another-page-1",
            'templateName' => "empty",
            'isPublished' => "0",
            'permalink' => "internal page 1",
            'title' => 'A title',
            'description' => 'A description',
            'keywords' => 'Some keywords',
            'sitemapChangeFreq' => '',
            'sitemapPriority' => '',
        );

        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());
        $json = json_decode($response->getContent(), true);
        $this->assertRegExp(
            '/pages_controller_page_saved|The page has been successfully saved/si',
            $json[0]["value"]
        );

        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageName' => "another-page-2",
            'templateName' => "empty",
            'isPublished' => "0",
            'permalink' => "internal page 2",
            'title' => 'A title',
            'description' => 'A description',
            'keywords' => 'Some keywords',
            'sitemapChangeFreq' => '',
            'sitemapPriority' => '',
        );

        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());
        $json = json_decode($response->getContent(), true);
        $this->assertRegExp(
            '/pages_controller_page_saved|The page has been successfully saved/si',
            $json[0]["value"]
        );
        
        $this->doPageFromDbTest(6, 'another-page-1', 'empty', 0, 0);
        $this->doPageFromDbTest(7, 'another-page-2', 'empty', 0, 0);
    }

    public function testAddAPagePublishedByDefault()
    {
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageName' => "another-page-3",
            'templateName' => "home",
            'permalink' => "page 3",
            'isPublished' => "1",
            'title' => 'A title',
            'description' => 'A description',
            'keywords' => 'Some keywords',
            'sitemapChangeFreq' => '',
            'sitemapPriority' => '',
        );

        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());

        $this->doPageFromDbTest(8, 'another-page-3', 'home', 0, 1);
    }
    
    public function testAddPageSuccededWhenPageIdIsGiven()
    {
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageId' => '8',
            'pageName' => "another-page-4",
            'templateName' => "home",
            'permalink' => "page 4",
            'isPublished' => "0",
            'title' => 'A title',
            'description' => 'A description',
            'keywords' => 'Some keywords',
            'sitemapChangeFreq' => '',
            'sitemapPriority' => '',
        );

        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());
        
        $json = json_decode($response->getContent(), true);
        $this->assertRegExp(
            '/pages_controller_page_saved|The page has been successfully saved/si',
            $json[0]["value"]
        );
        
        $this->doPageFromDbTest(9, 'another-page-4', 'home', 0, 0);
    }
    
    public function testHomePageIsNotDegraded()
    {
        $homePage = $this->pageRepository->homePage();
        
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageId' => $homePage->getId(),
            'languageId' => 2,
            'isHome' => 0,
        );

        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());

        $this->assertRegExp(
            '/exception_home_page_cannot_be_degraded|Current home page cannot be degraded. To change the website home page you must promote another page as main and this one will be automatically degraded/si',
            $response->getContent()
        );
    }
    
    public function testPageIsPromotedAsHomePage()
    {
        $homePage = $this->pageRepository->homePage();
        
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageId' => 5,
            'languageId' => 2,
            'isHome' => 1,
        );

        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $page = $this->pageRepository->fromPk(5);
        $this->assertEquals(1, $page->getIsHome());
        $this->assertEquals(0, $homePage->getIsHome());
    }

    private function retrievePageSlots()
    {
        $pageTree = $this->client->getContainer()->get('red_kite_cms.page_tree');
        $templateSlots = $pageTree->getTemplateManager()->getTemplateSlots();
        $slots = $templateSlots->toArray();

        return $slots['page'];
    }
    
    private function doPageFromDbTest($id, $pageName, $template, $isHome, $isPublished)
    {
        $page = $this->pageRepository->fromPk($id);
        $this->assertNotNull($page);
        $this->assertEquals($pageName, $page->getPageName());
        $this->assertEquals($template, $page->getTemplateName());
        $this->assertEquals($isHome, $page->getIsHome());
        $this->assertEquals($isPublished, $page->getIsPublished());
    }
}