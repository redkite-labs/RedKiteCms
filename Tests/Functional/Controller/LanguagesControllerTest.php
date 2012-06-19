<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infpageModelation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Controller;

use AlphaLemon\AlphaLemonCmsBundle\Tests\WebTestCaseFunctional;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlLanguageModelPropel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlSeoModelPropel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlBlockModelPropel;

/**
 * LanguagesControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class LanguagesControllerTest extends WebTestCaseFunctional
{
    private $languageModel;
    private $seoModel;
    private $blockModel;

    protected function setUp()
    {
        parent::setUp();

        $this->languageModel = new AlLanguageModelPropel();
        $this->seoModel = new AlSeoModelPropel();
        $this->blockModel = new AlBlockModelPropel();
    }

    public function testFormElements()
    {
        $crawler = $this->client->request('GET', 'backend/en/al_showLanguages');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('#languages_language')->count());
        $this->assertEquals(1, $crawler->filter('#languages_isMain')->count());
        $this->assertEquals(1, $crawler->filter('#al_language_saver')->count());
    }

    public function testAddLanguageFailsWhenPageNameParamIsMissing()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'isMain' => '0',);

        $crawler = $this->client->request('POST', 'backend/en/al_saveLanguage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('A language cannot be null. Please provide a valid language name to add the language', $crawler->text());
    }

    public function testAddLanguage()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'newLanguage' => 'fr',
                        'isMain' => '0',);

        $crawler = $this->client->request('POST', 'backend/en/al_saveLanguage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(3, count($json));
        $this->assertTrue(array_key_exists("key", $json[0]));
        $this->assertEquals("message", $json[0]["key"]);
        $this->assertTrue(array_key_exists("value", $json[0]));
        $this->assertEquals("The language has been successfully saved", $json[0]["value"]);
        $this->assertTrue(array_key_exists("key", $json[1]));
        $this->assertEquals("languages", $json[1]["key"]);
        $this->assertTrue(array_key_exists("value", $json[1]));
        $this->assertRegExp("/\<a[^\>]+ref=\"2\"\>en\<\/a\>/s", $json[1]["value"]);
        $this->assertRegExp("/\<a[^\>]+ref=\"3\"\>fr\<\/a\>/s", $json[1]["value"]);
        $this->assertTrue(array_key_exists("key", $json[2]));
        $this->assertEquals("languages_menu", $json[2]["key"]);
        $this->assertTrue(array_key_exists("value", $json[2]));
        $this->assertRegExp("/\<select[^\>]+id=\"al_languages_navigator\"[^\>]+\>/s", $json[2]["value"]);
        $this->assertRegExp("/\<option[^\>]+rel=\"en\"[^\>]+\>en\<\/option\>/s", $json[2]["value"]);
        $this->assertRegExp("/\<option[^\>]+rel=\"fr\"[^\>]+\>fr\<\/option\>/s", $json[2]["value"]);

        $language = $this->languageModel->fromPk(3);
        $this->assertNotNull($language);
        $this->assertEquals('fr', $language->getLanguage());
        $this->assertEquals(0, $language->getMainLanguage());

        $seo = $this->seoModel->fromPageAndLanguage(3, 2);
        $this->assertNotNull($seo);
        $this->assertEquals('this-is-a-website-fake-page', $seo->getPermalink());

        // Repeated contents have not been added
        $pagesSlots = $this->retrievePageSlots();
        $this->assertEquals(count($pagesSlots), count($this->blockModel->retrieveContents(3, 2, $pagesSlots)));
    }

    public function testLanguageJustAdded()
    {
        $crawler = $this->client->request('GET', 'backend/fr/this-is-a-website-fake-page');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('#block_1')->count());
        $this->assertEquals(1, $crawler->filter('#block_32')->count());
        $this->assertEquals(22, $crawler->filter('.al_editable')->count());
    }

    public function testAddLanguageFailsWhenTheLanguageNameAlreadyExists()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'newLanguage' => 'fr',
                        'isMain' => '0',);
        $crawler = $this->client->request('POST', 'backend/en/al_saveLanguage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('The language you are trying to add, already exists in the website', $crawler->text());
    }

    public function testAddANewMainLanguage()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'newLanguage' => 'es',
                        'isMain' => '1',);
        $crawler = $this->client->request('POST', 'backend/en/al_saveLanguage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals(3, count($this->languageModel->activeLanguages()));
        $this->assertEquals(4, $this->languageModel->mainLanguage()->getId());

        // Previous home page has been degraded
        $language = $this->languageModel->fromPk(2);
        $this->assertEquals(0, $language->getMainLanguage());

        $seo = $this->seoModel->fromPageAndLanguage(4, 2);
        $this->assertNotNull($seo);
    }

    public function testEditLanguage()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'idLanguage' => 3,
                        'newLanguage' => "it",);

        $crawler = $this->client->request('POST', 'backend/en/al_saveLanguage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $language = $this->languageModel->fromPk(3);
        $this->assertEquals('it', $language->getLanguage());
    }

    public function testDeleteLanguageFailsBecauseAnyLanguageIdIsGiven()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'idLanguage' => 'none');

        $crawler = $this->client->request('POST', 'backend/en/al_deleteLanguage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Any language has been choosen for removing', $crawler->text());
    }

    public function testDeleteLanguageFailsBecauseAnInvalidLanguaageIdIsGiven()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'idLanguage' => 999);

        $crawler = $this->client->request('POST', 'backend/en/al_deleteLanguage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Any language has been choosen for removing', $crawler->text());
    }

    public function testDeleteTheMainLanaguageIsForbidden()
    {
        $language = $this->languageModel->mainLanguage();
        $params = array('page' => 'index',
                        'language' => 'en',
                        'idLanguage' => $language->getId());

        $crawler = $this->client->request('POST', 'backend/en/al_deleteLanguage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('The website main language cannot be deleted. To delete this language promote another one as main language, then delete it again', $crawler->text());
    }

    public function testDeleteLanguage()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'idLanguage' => 2);

        $crawler = $this->client->request('POST', 'backend/en/al_deleteLanguage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2, count($this->languageModel->activeLanguages()));

        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(3, count($json));
        $this->assertTrue(array_key_exists("key", $json[0]));
        $this->assertEquals("message", $json[0]["key"]);
        $this->assertTrue(array_key_exists("value", $json[0]));
        $this->assertEquals("The language has been successfully removed", $json[0]["value"]);
        $this->assertTrue(array_key_exists("key", $json[1]));
        $this->assertEquals("languages", $json[1]["key"]);
        $this->assertTrue(array_key_exists("value", $json[1]));
        $this->assertRegExp("/\<a[^\>]+ref=\"3\"\>it<\/a\>/s", $json[1]["value"]);
        $this->assertRegExp("/\<a[^\>]+ref=\"4\"\>es\<\/a\>/s", $json[1]["value"]);
        $this->assertTrue(array_key_exists("key", $json[2]));
        $this->assertEquals("languages_menu", $json[2]["key"]);
        $this->assertTrue(array_key_exists("value", $json[2]));
        $this->assertRegExp("/\<select[^\>]+id=\"al_languages_navigator\"[^\>]+\>/s", $json[2]["value"]);
        $this->assertRegExp("/\<option[^\>]+rel=\"it\"[^\>]+\>it\<\/option\>/s", $json[2]["value"]);
        $this->assertRegExp("/\<option[^\>]+rel=\"es\"[^\>]+\>es\<\/option\>/s", $json[2]["value"]);

        $page = $this->languageModel->fromPk(2);
        $this->assertEquals(1, $page->getToDelete());

        $seo = $this->seoModel->fromPageAndLanguage(2, 2);
        $this->assertNull($seo);

        // Repeated contents have not been added
        $pagesSlots = $this->retrievePageSlots();
        $this->assertEquals(0, count($this->blockModel->retrieveContents(2, 2, $pagesSlots)));
    }

    private function retrievePageSlots()
    {
        $pageTree = $this->client->getContainer()->get('al_page_tree');
        $templateSlots = $pageTree->getTemplateManager()->getTemplateSlots();
        $slots = $templateSlots->toArray();

        return $slots['page'];
    }
}
