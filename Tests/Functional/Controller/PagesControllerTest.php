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
 * PagesControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
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
        $this->assertEquals(1, $crawler->filter('#seo_attributes_idLanguage')->count());
        $this->assertEquals(1, $crawler->filter('#al_page_saver')->count());
        $this->assertEquals(1, $crawler->filter('#al_pages_list')->count());
        $this->assertEquals(2, $crawler->filter('#al_pages_list .al_element_selector')->count());
        $this->assertEquals(1, $crawler->filter('#al_pages_remover')->count());
    }

    public function testAddPageFailsWhenPagenameContainsInvalidPrefix()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        "pageName" => "al_temp");

        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("The prefix [ al_ ] is not permitted to avoid conflicts with the application internal routes")')->count() > 0);
    }

    public function testAddPageFailsWhenPageNameParamIsMissing()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'templateName' => "home",
                        'permalink' => "page 1",
                        'title' => 'A title',
                        'description' => 'A description',
                        'keywords' => 'Some keywords');

        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('The name to assign to the page cannot be null. Please provide a valid page name to add your page', $crawler->text());
    }

    public function testAddPageFailsWhenTemplateNameParamIsMissing()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageName' => "page1",
                        'permalink' => "page 1",
                        'title' => 'A title',
                        'description' => 'A description',
                        'keywords' => 'Some keywords');

        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('The page requires at least a template. Please provide the template name to add your page', $crawler->text());
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
        $this->assertEquals("The page has been successfully saved", $json[0]["value"]);
        $this->assertTrue(array_key_exists("key", $json[1]));
        $this->assertEquals("pages_list", $json[1]["key"]);
        $this->assertTrue(array_key_exists("value", $json[1]));
        $this->assertRegExp("/\<a[^\>]+ref=\"2\"\>index\<\/a\>/s", $json[1]["value"]);
        $this->assertRegExp("/\<a[^\>]+ref=\"3\"\>page1\<\/a\>/s", $json[1]["value"]);
        $this->assertTrue(array_key_exists("key", $json[2]));
        $this->assertEquals("permalinks", $json[2]["key"]);
        $this->assertTrue(array_key_exists("value", $json[2]));
        $this->assertRegExp("/\<select id=\"al_page_name\"[^\>]+\>/s", $json[2]["value"]);
        $this->assertRegExp("/\<option value=\"page-1\"\>page-1/s", $json[2]["value"]);
        $this->assertRegExp("/\<option value=\"this-is-a-website-fake-page\"\>this-is-a-website-fake-page/s", $json[2]["value"]);
        $this->assertEquals("pages", $json[3]["key"]);
        $this->assertTrue(array_key_exists("value", $json[3])); //<ul class="dropdown-menu dropdown-height dropdown-zindex">
        $this->assertRegExp("/\<ul class=\"dropdown-menu[^\>]+\>/s", $json[3]["value"]);
        $this->assertRegExp("/\<li id=\"none\"[^\>]+\>\<a href=\"#\"\> \<\/a\>/s", $json[3]["value"]);
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
        $this->assertCount(0, $crawler->filter('#block_20'));
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
        $this->assertEquals('The web site already contains the page you are trying to add. Please use another name for that page', $crawler->text());
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
        $this->assertCount(8, $this->blockRepository->retrieveContents(2, 4));
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
        $this->assertCount(0, $crawler->filter('#block_20'));
        $this->assertCount(1, $crawler->filter('#block_57'));
        $this->assertCount(1, $crawler->filter('#block_57')->filter('[data-name="block_57"]'));
        $this->assertCount(0, $crawler->filter('#block_31'));
        $this->assertCount(1, $crawler->filter('[data-name="block_31"]'));
    }

    public function testEditPage()
    {
        // Saves a link that contains the permalink we are going to change
        $block = $this->blockRepository->fromPK(15);
        $block->setContent('<a href="page-2">Go to page 2</a>');
        $block->save();

        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => 4,
                        'languageId' => 2,
                        'pageName' => "page2 edited",
                        'permalink' => "page-2 edited",);

        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $page = $this->pageRepository->fromPk(4);
        $this->assertEquals('page2-edited', $page->getPageName());

        $page = $this->seoRepository->fromPk(3);
        $this->assertEquals('page-2-edited', $page->getPermalink());
    }

    public function testPermalinksHaveBeenChanged()
    {
        $crawler = $this->client->request('GET', '/backend/en/index');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $link = $crawler->selectLink('Go to page 2')->link();
        $this->assertEquals('http://localhost/backend/en/page-2-edited', $link->getUri());
    }

    public function testEditPageToBePublishable()
    {
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageId' => 3,
            'languageId' => 2,
            'isPublished' => 1,
            'permalink' => "page-2 edited",
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
                        'pageId' => 4,
                        'languageId' => 2,
                        'templateName' => 'empty',
                        'permalink' => "page-2 edited",);

        $crawler = $this->client->request('POST', '/backend/en/al_savePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $page = $this->pageRepository->fromPK(4);
        $this->assertEquals('empty', $page->getTemplateName());
    }

    public function testDeletePageFailsBecauseAnyPageIdIsGiven()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => 'none',
                        'languageId' => 2,
                        'languageId' => 'none');

        $crawler = $this->client->request('POST', '/backend/en/al_deletePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Any page has been choosen for removing', $crawler->text());
    }

    public function testDeletePageFailsBecauseAnInvalidPageIdIsGiven()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => 999,
                        'languageId' => 2,
                        'languageId' => 'none');

        $crawler = $this->client->request('POST', '/backend/en/al_deletePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Any page has been choosen for removing', $crawler->text());
    }

    public function testDeleteTheHomePageIsForbidden()
    {
        $page = $this->pageRepository->homePage();
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => $page->getId(),
                        'languageId' => 2,
                        'languageId' => 'none');

        $crawler = $this->client->request('POST', '/backend/en/al_deletePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('It is not allowed to remove the website\'s home page. Promote another page as the home of your website, then remove this one', $crawler->text());
    }

    public function testDeletePageSeoAttributes()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'pageId' => 3,
                        'languageId' => 2);

        $crawler = $this->client->request('POST', '/backend/en/al_deletePage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(4, count($this->pageRepository->activePages()));
        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());

        $seo = $this->seoRepository->fromPageAndLanguage(2, 3);
        $this->assertNull($seo);

        $this->assertEquals(0, count($this->blockRepository->retrieveContents(3, 2)));
    }

    public function testPageJustDeletedSeoAttributes()
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
        $this->assertEquals("1", $json[3]["value"]);
        $this->assertEquals("#seo_attributes_permalink", $json[4]["name"]);
        $this->assertEquals("", $json[4]["value"]);
        $this->assertEquals("#seo_attributes_title", $json[5]["name"]);
        $this->assertEquals("", $json[5]["value"]);
        $this->assertEquals("#seo_attributes_description", $json[6]["name"]);
        $this->assertEquals("", $json[6]["value"]);
        $this->assertEquals("#seo_attributes_keywords", $json[7]["name"]);
        $this->assertEquals("", $json[7]["value"]);
    }

    public function testDeletePage()
    {
        $page = $this->pageRepository->fromPk(2);
        $this->assertEquals(0, $page->getToDelete());
        
        $seo = $this->seoRepository->fromPageAndLanguage(2, 2);
        $this->assertNotNull($seo);
        
        $this->assertCount(21, $this->blockRepository->retrieveContents(2, 2));
        
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
        $this->assertEquals("The page has been successfully removed", $json[0]["value"]);
        $this->assertTrue(array_key_exists("key", $json[1]));
        $this->assertEquals("pages_list", $json[1]["key"]);
        $this->assertTrue(array_key_exists("value", $json[1]));
        $this->assertRegExp("/\<a[^\>]+ref=\"3\"\>page1\<\/a\>/s", $json[1]["value"]);
        $this->assertRegExp("/\<a[^\>]+ref=\"4\"\>page2-edited\<\/a\>/s", $json[1]["value"]);
        $this->assertTrue(array_key_exists("key", $json[2]));
        $this->assertEquals("permalinks", $json[2]["key"]);
        $this->assertTrue(array_key_exists("value", $json[2]));
        $this->assertRegExp("/\<option value=\"another-page\"\>another-page/s", $json[2]["value"]);
        $this->assertRegExp("/\<option value=\"page-2-edited\"\>page-2-edited/s", $json[2]["value"]);$this->assertEquals("pages", $json[3]["key"]);
        $this->assertTrue(array_key_exists("value", $json[3])); 
        $this->assertRegExp("/\<li id=\"none\"[^\>]+\>\<a href=\"#\"\> \<\/a\>/s", $json[3]["value"]);
        $this->assertRegExp("/\<li id=\"5\"[^\>]+\>\<a href=\"#\"\>another-page\<\/a\>/s", $json[3]["value"]);
        $this->assertRegExp("/\<li id=\"3\"[^\>]+\>\<a href=\"#\"\>page1\<\/a\>/s", $json[3]["value"]);
        $this->assertRegExp("/\<li id=\"4\"[^\>]+\>\<a href=\"#\"\>page2-edited\<\/a\>/s", $json[3]["value"]);
        
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
        $this->assertEquals("The page has been successfully saved", $json[0]["value"]);

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
        $this->assertEquals("The page has been successfully saved", $json[0]["value"]);

        $page = $this->pageRepository->fromPk(6);
        $this->assertNotNull($page);
        $this->assertEquals('another-page-1', $page->getPageName());
        $this->assertEquals('empty', $page->getTemplateName());
        $this->assertEquals(0, $page->getIsHome());
        $this->assertEquals(0, $page->getIsPublished());

        $page = $this->pageRepository->fromPk(7);
        $this->assertNotNull($page);
        $this->assertEquals('another-page-2', $page->getPageName());
        $this->assertEquals('empty', $page->getTemplateName());
        $this->assertEquals(0, $page->getIsHome());
        $this->assertEquals(0, $page->getIsPublished());
    }

    public function testAddAPagePublishedByDefault()
    {
        $params = array(
            'page' => 'index',
            'language' => 'en',
            'pageName' => "another-page-3",
            'templateName' => "home",
            'permalink' => "page 1",
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

        $page = $this->pageRepository->fromPk(8);
        $this->assertNotNull($page);
        $this->assertEquals('another-page-3', $page->getPageName());
        $this->assertEquals('home', $page->getTemplateName());
        $this->assertEquals(0, $page->getIsHome());
        $this->assertEquals(1, $page->getIsPublished());
    }

    private function retrievePageSlots()
    {
        $pageTree = $this->client->getContainer()->get('alpha_lemon_cms.page_tree');
        $templateSlots = $pageTree->getTemplateManager()->getTemplateSlots();
        $slots = $templateSlots->toArray();

        return $slots['page'];
    }
}
