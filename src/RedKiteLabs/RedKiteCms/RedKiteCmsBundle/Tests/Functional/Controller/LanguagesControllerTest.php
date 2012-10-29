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
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlSeoRepositoryPropel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel;

/**
 * LanguagesControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class LanguagesControllerTest extends WebTestCaseFunctional
{
    private $languageRepository;
    private $seoRepository;
    private $blockRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->languageRepository = new AlLanguageRepositoryPropel();
        $this->seoRepository = new AlSeoRepositoryPropel();
        $this->blockRepository = new AlBlockRepositoryPropel();
    }

    public function testFormElements()
    {
        $crawler = $this->client->request('POST', '/backend/en/al_showLanguages');
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

        $crawler = $this->client->request('POST', '/backend/en/al_saveLanguage', $params);
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

        $crawler = $this->client->request('POST', '/backend/en/al_saveLanguage', $params);
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

        $language = $this->languageRepository->fromPk(3);
        $this->assertNotNull($language);
        $this->assertEquals('fr', $language->getLanguageName());
        $this->assertEquals(0, $language->getMainLanguage());

        $seo = $this->seoRepository->fromPageAndLanguage(3, 2);
        $this->assertNotNull($seo);
        $this->assertEquals('fr-this-is-a-website-fake-page', $seo->getPermalink());

        // Repeated contents have not been added
        $pagesSlots = $this->retrievePageSlots();
        $this->assertEquals(count($pagesSlots), count($this->blockRepository->retrieveContents(3, 2, $pagesSlots)));
    }

    public function testLanguageJustAdded()
    {
        $crawler = $this->client->request('GET', '/backend/fr/this-is-a-website-fake-page');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('#block_1')->count());
        $this->assertEquals(1, $crawler->filter('#block_32')->count());
        $this->assertEquals(22, $crawler->filter('.al_editable')->count());
    }
    
    public function testLoadLanguageAttributes()
    {
        $params = array('languageId' => 2);
        $crawler = $this->client->request('POST', '/backend/en/al_loadLanguageAttributes', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $json = json_decode($response->getContent(), true);
        $this->assertEquals("#languages_language", $json[0]["name"]);
        $this->assertEquals("en", $json[0]["value"]);
        $this->assertEquals("#languages_isMain", $json[1]["name"]);
        $this->assertEquals("1", $json[1]["value"]);
        
        $params = array('languageId' => 3);
        $crawler = $this->client->request('POST', '/backend/en/al_loadLanguageAttributes', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $json = json_decode($response->getContent(), true);
        $this->assertEquals("#languages_language", $json[0]["name"]);
        $this->assertEquals("fr", $json[0]["value"]);
        $this->assertEquals("#languages_isMain", $json[1]["name"]);
        $this->assertEquals("0", $json[1]["value"]);
    }

    public function testAddLanguageFailsWhenTheLanguageNameAlreadyExists()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'newLanguage' => 'fr',
                        'isMain' => '0',);
        $crawler = $this->client->request('POST', '/backend/en/al_saveLanguage', $params);
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
        $crawler = $this->client->request('POST', '/backend/en/al_saveLanguage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals(3, count($this->languageRepository->activeLanguages()));
        $this->assertEquals(4, $this->languageRepository->mainLanguage()->getId());

        // Previous home page has been degraded
        $language = $this->languageRepository->fromPk(2);
        $this->assertEquals(0, $language->getMainLanguage());

        $seo = $this->seoRepository->fromPageAndLanguage(4, 2);
        $this->assertNotNull($seo);
    }

    public function testEditLanguage()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'languageId' => 3,
                        'newLanguage' => "it",);

        $crawler = $this->client->request('POST', '/backend/en/al_saveLanguage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $language = $this->languageRepository->fromPk(3);
        $this->assertEquals('it', $language->getLanguageName());
    }

    public function testDeleteLanguageFailsBecauseAnyLanguageIdIsGiven()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'languageId' => 'none');

        $crawler = $this->client->request('POST', '/backend/en/al_deleteLanguage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Any language has been choosen for removing', $crawler->text());
    }

    public function testDeleteLanguageFailsBecauseAnInvalidLanguageIdIsGiven()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'languageId' => 999);

        $crawler = $this->client->request('POST', '/backend/en/al_deleteLanguage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Any language has been choosen for removing', $crawler->text());
    }

    public function testDeleteTheMainLanaguageIsForbidden()
    {
        $language = $this->languageRepository->mainLanguage();
        $params = array('page' => 'index',
                        'language' => 'en',
                        'languageId' => $language->getId());

        $crawler = $this->client->request('POST', '/backend/en/al_deleteLanguage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('The website main language cannot be deleted. To delete this language promote another one as main language, then delete it again', $crawler->text());
    }

    public function testDeleteLanguage()
    {
        $params = array('page' => 'index',
                        'language' => 'en',
                        'languageId' => 2);

        $crawler = $this->client->request('POST', '/backend/en/al_deleteLanguage', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2, count($this->languageRepository->activeLanguages()));

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

        $page = $this->languageRepository->fromPk(2);
        $this->assertEquals(1, $page->getToDelete());

        $seo = $this->seoRepository->fromPageAndLanguage(2, 2);
        $this->assertNull($seo);

        // Repeated contents have not been added
        $pagesSlots = $this->retrievePageSlots();
        $this->assertEquals(0, count($this->blockRepository->retrieveContents(2, 2, $pagesSlots)));
    }

    private function retrievePageSlots()
    {
        $pageTree = $this->client->getContainer()->get('alpha_lemon_cms.page_tree');
        $templateSlots = $pageTree->getTemplateManager()->getTemplateSlots();
        $slots = $templateSlots->toArray();

        return $slots['page'];
    }
}
