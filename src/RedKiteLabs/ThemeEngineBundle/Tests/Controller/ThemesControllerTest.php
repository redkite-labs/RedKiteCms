<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 * 
 */
namespace AlphaLemon\ThemeEngineBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AlphaLemon\ThemeEngineBundle\Tests\tools\AlphaLemonDataPopulator;
use Symfony\Component\Filesystem\Filesystem;

class ThemesControllerTest extends WebTestCase
{
    static $sourceFolder;
    static $targetFolder;
    
    public static function setUpBeforeClass()
    {
        self::$sourceFolder = __DIR__ . '/../Resources';
        self::$targetFolder = __DIR__ . '/../Themes';   
        
        $fs = new Filesystem();
        $fs->remove(self::$targetFolder);
        $fs->mkdir(self::$targetFolder);
        
        copy(self::$sourceFolder . '/AlphaLemonThemeBundle.zip', self::$targetFolder . '/AlphaLemonThemeBundle.zip');
    }
    
    public static function tearDownAfterClass()
    {
        $fs = new Filesystem();
        $fs->remove(self::$targetFolder);
    }
    
    public function testShow()
    {
        
        $client = $this->createClient();
        $crawler = $client->request('GET', '/en/al_showThemes');     
        
        $this->assertTrue($crawler->filter('html:contains("Active Theme")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("Available Themes")')->count() > 0);
        $this->assertTrue($crawler->filter('#al_valum_uploader')->count() > 0);  
        $this->assertFalse($crawler->filter('html:contains("AlphaLemonThemeBundle")')->count() > 0);
    }
    
    public function testExtract()
    {
        $client = $this->createClient();
        $container = $client->getContainer();
        
        $crawler = $client->request('GET', '/en/al_showThemes');      
        $this->assertFalse($crawler->filter('html:contains("AlphaLemonThemeBundle")')->count() > 0);
        
        $crawler = $client->request('GET', '/en/al_extractTheme');  
        
        $crawler = $client->request('GET', '/en/al_showThemes');      
        $this->assertTrue($crawler->filter('html:contains("AlphaLemonThemeBundle")')->count() > 0);
    }
    
    
    public function testActivate()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/en/al_showThemes');         
        $this->assertTrue($crawler->filter('html:contains("Activate")')->count() > 0);
        $link = $crawler->filter('a:contains("Activate")')->eq(0)->link();
        $crawler = $client->click($link);
        
        $crawler = $client->request('GET', '/en/al_showThemes');         
        $this->assertFalse($crawler->filter('html:contains("Activate")')->count() > 0);
    }
    
    /**
     * @depends testExtract
     */
    public function testImport()
    {
        $fs = new Filesystem();
        $fs->mirror(self::$targetFolder . '/AlphaLemonThemeBundle', self::$targetFolder . '/AlphaLemonThemeBundle1');
        
        $client = $this->createClient();
        $crawler = $client->request('GET', '/en/al_showThemes');  
        $this->assertTrue($crawler->filter('html:contains("Import")')->count() > 0);
        $this->assertFalse($crawler->filter('html:contains("Remove")')->count() > 0);
        $link = $crawler->filter('a:contains("Import")')->eq(0)->link();
        $crawler = $client->click($link);
        
        $crawler = $client->request('GET', '/en/al_showThemes');
        $this->assertTrue($crawler->filter('html:contains("AlphaLemonThemeBundle1")')->count() > 0);
        $this->assertFalse($crawler->filter('html:contains("Import")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("Remove")')->count() > 0);
    }
    
    /**
     * @depends testImport
     */
    public function testRemove()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/en/al_showThemes');  
        $link = $crawler->filter('a:contains("Remove")')->eq(0)->link();
        $crawler = $client->click($link);
        $this->assertFalse($crawler->filter('html:contains("AlphaLemonThemeBundle1")')->count() > 0);
    }
}
