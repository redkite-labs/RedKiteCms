<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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
        $this->assertRegExp(
            '/themes_controller_some_templates_not_mapped|It seems you have not mapped the "empty" template. To change a theme each template must be mapped with a template from the new theme/si',
            $response->getContent()
        );
    }
    
    public function testChangeTheme()
    {
        $pageRepository = new PageRepositoryPropel();
        $page = $pageRepository->fromPageName('index');
        $this->assertEquals('home', $page->getTemplateName());
        
        $page = $pageRepository->fromPageName('page1');
        $this->assertEquals('empty', $page->getTemplateName());
        
        $params = array("themeName" => "ModernBusinessThemeBundle", "data" => "al-template=home&al-mapped-template=home&al-template=empty&al-mapped-template=internal");
        $crawler = $this->client->request('POST', 'backend/en/al_changeTheme', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertRegExp(
            '/themes_controller_theme_changed|The theme has been changed. Please wait while your site is reloading/si',
            $response->getContent()
        );
       
        $crawler = $this->client->request('GET', 'backend');
        
        $page = $pageRepository->fromPageName('index');
        $this->assertEquals('home', $page->getTemplateName());
        
        $page = $pageRepository->fromPageName('page1');
        $this->assertEquals('internal', $page->getTemplateName());
    }
    
    public function testStartFromScratch()
    {
        $params = array('themeName' => 'ModernBusinessThemeBundle');
        $crawler = $this->client->request('POST', 'backend/en/startFromTheme', $params);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode()); 
        $this->assertRegExp(
            '/themes_controller_site_bootstrapped|The site has been bootstrapped with the new theme. This page is reloading/si',
            $response->getContent()
        );
    }
}
