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

use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlLockedResourceRepositoryPropel;

/**
 * LockedResourcesTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class LockedResourcesTest extends BaseSecured
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        
        self::addUser('mario', 'bross', self::$roles['ROLE_ADMIN']);
    }
    
    protected function setUp()
    {        
        $this->lockedResourceRepository = new AlLockedResourceRepositoryPropel();
    }
    
    public function testOpenARouteNotLocked()
    {
        $client = $this->setUpClient();
        $crawler = $client->request('POST', '/backend/en/al_showPages');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        
        $credentials = array(
            'PHP_AUTH_USER' => 'mario',
            'PHP_AUTH_PW' => 'bross',
        );
        $client1 = $this->setUpClient($credentials);
        $crawler1 = $client1->request('POST', '/backend/en/al_showPages');
        $response1 = $client1->getResponse();
        $this->assertEquals(200, $response1->getStatusCode());
        
        $this->assertCount(0, $this->lockedResourceRepository->fetchResources());
    }
    
    /**
     * @dataProvider routesProvider
     */
    public function testOpenARouteLocked($lockedRoute, $route, $params, $method = 'POST')
    {   
        $client = $this->setUpClient();
        $crawler = $client->request($method, $lockedRoute, $params);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        
        $credentials = array(
            'PHP_AUTH_USER' => 'mario',
            'PHP_AUTH_PW' => 'bross',
        );
        $client1 = $this->setUpClient($credentials);
        $crawler1 = $client1->request($method, $route, $params);
        $response1 = $client1->getResponse();
        $this->assertEquals(404, $response1->getStatusCode());
        $this->assertEquals('The resource you requested is locked by another user. Please retry in a couple of minutes', $crawler1->text());
        
        $this->assertCount(1, $this->lockedResourceRepository->fetchResources());
    }
    
    public function routesProvider()
    {
        return array(
            array(
                '/backend/en/index', 
                '/backend/en/index', 
                array("pageId" => 2),
            ),     
            array(
                '/backend/homepage', 
                '/backend/homepage', 
                array("pageId" => 2),
            ),     
            array(
                '/backend', 
                '/backend', 
                array("pageId" => 2),
            ), 
            array(
                '/backend/en/al_loadLanguageAttributes', 
                '/backend/en/al_loadLanguageAttributes', 
                array("languageId" => 2),
            ),          
            array(
                '/backend/en/al_loadLanguageAttributes', 
                '/backend/en/al_saveLanguage', 
                array("languageId" => 2),
            ),          
            array(
                '/backend/en/al_loadLanguageAttributes', 
                '/backend/en/al_deleteLanguage', 
                array("languageId" => 2),
            ),
            array(
                '/backend/en/al_loadSeoAttributes', 
                '/backend/en/al_loadSeoAttributes', 
                array("pageId" => 2),
            ),
            array(
                '/backend/en/al_loadSeoAttributes', 
                '/backend/en/al_savePage', 
                array("pageId" => 2),
            ),
            array(
                '/backend/en/al_loadSeoAttributes', 
                '/backend/en/al_deletePage', 
                array("pageId" => 2),
            ),
            array(
                '/backend/users/en/al_showUser', 
                '/backend/users/en/al_showUser', 
                array("id" => 1),
                'GET',
            ),
            array(
                '/backend/users/en/al_showUser', 
                '/backend/users/en/al_deleteUser', 
                array("id" => 1),
                'GET',
            ),
            array(
                '/backend/users/en/al_showRole', 
                '/backend/users/en/al_showRole', 
                array("id" => 1),
                'GET',
            ),
            array(
                '/backend/users/en/al_showRole', 
                '/backend/users/en/al_deleteRole', 
                array("id" => 1),
                'GET',
            ),
            array(
                '/backend/en/al_showThemesPanel', 
                '/backend/en/al_showThemesPanel', 
                array(
                    'language' => 'en', 
                    'page' => 'index',
                ),
            ),
            array(
                '/backend/en/al_showThemesPanel', 
                '/backend/en/al_showThemeChanger', 
                array(
                    'language' => 'en', 
                    'page' => 'index',
                ),
            ),
            array(
                '/backend/en/al_showThemesPanel', 
                '/backend/en/al_changeTheme', 
                array(
                    'language' => 'en', 
                    'page' => 'index',
                ),
            ),
            array(
                '/backend/en/al_showThemesPanel', 
                '/backend/en/al_changeSlot', 
                array(
                    'language' => 'en', 
                    'page' => 'index',
                ),
            ),
            array(
                '/backend/en/al_showThemesPanel', 
                '/backend/en/startFromTheme', 
                array(
                    'language' => 'en', 
                    'page' => 'index',
                ),
            ),
            array(
                '/backend/en/al_showThemesPanel', 
                '/backend/en/al_showThemesFinalizer', 
                array(
                    'language' => 'en', 
                    'page' => 'index',
                ),
            ),
            array(
                '/backend/en/al_showThemesPanel', 
                '/backend/en/al_finalizeTheme', 
                array(
                    'language' => 'en', 
                    'page' => 'index',
                ),
            ),
        );
    }
}
