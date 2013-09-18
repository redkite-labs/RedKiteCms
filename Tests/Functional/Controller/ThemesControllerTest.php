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
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel;

/**
 * ThemesControllerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
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
        $params = array(
            "themeName" => "BootbusinessThemeBundle"
        );
        $crawler = $this->client->request('POST', '/backend/en/al_showThemeChanger', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(1, $crawler->filter('#al_theme_fixer'));
        $this->assertCount(1, $crawler->filter('#al-theme'));
        $this->assertCount(1, $crawler->filter('#al_theme_changer_form'));
        $this->assertCount(1, $crawler->filter('#al_template_changer'));
        $this->assertCount(1, $crawler->filter('#al_close_dialog'));
    }
    
    public function testToChangeAThemeAllTemplatesMustBeMapped()
    {
        $params = array("themeName" => "BootbusinessThemeBundle", "data" => "al-template=home&al-mapped-template=home&al-template=empty&al-mapped-template=");
        $crawler = $this->client->request('POST', 'backend/en/al_changeTheme', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertCount(1, $crawler->filter('html:contains("themes_controller_some_templates_not_mapped")'));
        
    }
    
    public function testChangeTheme()
    {
        $blockRepository = new AlBlockRepositoryPropel();
        $this->assertCount(0, $blockRepository->retrieveContents(null, null, null, 2));
        
        $pageRepository = new AlPageRepositoryPropel();
        $page = $pageRepository->fromPageName('index');
        $this->assertEquals('home', $page->getTemplateName());
        
        $page = $pageRepository->fromPageName('page1');
        $this->assertEquals('empty', $page->getTemplateName());
        
        $params = array("themeName" => "SunshineThemeBundle", "data" => "al-template=home&al-mapped-template=home&al-template=empty&al-mapped-template=internal");
        $crawler = $this->client->request('POST', 'backend/en/al_changeTheme', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp(
            '/themes_controller_theme_changed|The theme has been changed. Please wait while your site is reloading/si',
            $response->getContent()
        );
       
        $crawler = $this->client->request('GET', 'backend');
           
        $this->assertNotCount(0, $blockRepository->retrieveContents(null, null, null, 2));
        
        $page = $pageRepository->fromPageName('index');
        $this->assertEquals('home', $page->getTemplateName());
        
        $page = $pageRepository->fromPageName('page1');
        $this->assertEquals('internal', $page->getTemplateName());
    }
    
    public function testChangeSlot()
    {
        $blockRepository = new AlBlockRepositoryPropel();
        $sourceBlocks = $blockRepository->retrieveContents(null, null, 'slider_box', 2);
        $this->assertCount(1, $sourceBlocks);
        $targetBlocks = $blockRepository->retrieveContents(null, null, 'logo');
        $this->assertCount(1, $targetBlocks);
        
        $params = array("languageId" => 2, "pageId" => 2, "sourceSlotName" => "slider_box", "targetSlotName" => "logo");
        $crawler = $this->client->request('POST', 'backend/en/al_changeSlot', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp('/Content-Type:  application\/json/s', $response->__toString());
        
        $json = json_decode($response->getContent(), true);
        $this->assertEquals(2, count($json));
        $this->assertTrue(array_key_exists("key", $json[0]));
        $this->assertEquals("message", $json[0]["key"]);
        $this->assertTrue(array_key_exists("value", $json[0]));
        $this->assertEquals("themes_controller_slot_changed", $json[0]["value"]);
        
        $this->assertTrue(array_key_exists("key", $json[1]));
        $this->assertEquals("slots", $json[1]["key"]);
        $this->assertTrue(array_key_exists("value", $json[1]));
        
        $changedSourceBlocks = $blockRepository->retrieveContents(null, null, 'slider_box', 3);
        $this->assertCount(1, $changedSourceBlocks);
        $changedTargetBlocks = $blockRepository->retrieveContents(null, null, 'logo');
        $this->assertCount(1, $changedTargetBlocks);
        
        $this->assertEquals($changedTargetBlocks[0]->getContent(), $sourceBlocks[0]->getContent());
        $this->assertEquals($changedSourceBlocks[0]->getContent(), $targetBlocks[0]->getContent());
    }
    
    public function testShowThemesFinalizerChanger()
    {
        $crawler = $this->client->request('GET', 'backend/en/al_showThemesFinalizer');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(1, $crawler->filter('#al-partial-finalizer'));
        $this->assertCount(1, $crawler->filter('#al-full-finalizer'));
        $this->assertCount(1, $crawler->filter('#al-close-finalizer'));
        $this->assertRegExp(
            '/themes_controller_finalize_change_theme_explanation|Finalizes the change of theme/si',
            $response->getContent()
        );
    }
    
    public function testPartialFinalization()
    {
        $blockRepository = new AlBlockRepositoryPropel();
        $this->assertCount(1, $blockRepository->retrieveContents(null, null, null, 3));
        
        $params = array('action' => 'partial');
        $crawler = $this->client->request('POST', 'backend/en/al_finalizeTheme', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        
        $this->assertCount(0, $blockRepository->retrieveContents(null, null, null, 3));
    }
    
    public function testFullFinalization()
    {
        $fileStructure = $this->client->getContainer()->getParameter('red_kite_cms.theme_structure_file');
        $blockRepository = new AlBlockRepositoryPropel();
        $this->assertNotCount(0, $blockRepository->retrieveContents(null, null, null, 2));
        $this->assertFileExists($fileStructure);
        
        $params = array('action' => 'full');
        $crawler = $this->client->request('POST', 'backend/en/al_finalizeTheme', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        
        $this->assertCount(0, $blockRepository->retrieveContents(null, null, null, 2));
        $this->assertFileNotExists($fileStructure);
    }
    
    public function testStartFromScratch()
    {
        $params = array('themeName' => 'SunshineThemeBundle');
        $crawler = $this->client->request('POST', 'backend/en/startFromTheme', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode()); 
        $this->assertRegExp(
            '/themes_controller_site_bootstrapped|The site has been bootstrapped with the new theme. This page is reloading/si',
            $response->getContent()
        );
    }
}
