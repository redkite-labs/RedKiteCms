<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 * 
 * @license    GPL LICENSE Version 2.0
 * 
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\PageTree;

use Symfony\Component\HttpFoundation\SessionStorage\ArraySessionStorage;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\Request;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;
use AlphaLemon\ThemeEngineBundle\Model\AlTheme;
use AlphaLemon\ThemeEngineBundle\Core\Model\AlThemeQuery;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;

class AlPageTreeTest extends TestCase
{
    private $container;
    private static $alLanguage;
    private static $alPage;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->container = $this->getContainer();
        
        $request = new Request();
        $request->initialize(array('page' => 'index'));
        $this->container->set('request', $request); 
        
        $storage = new ArraySessionStorage();
        $session = new Session($storage);        
        $this->container->set('session', $session);
    }
    
    public function testSettingUpPageTreeWhenAnyThemeExistsReturnsANullValue()
    {
        AlphaLemonDataPopulator::depopulate();
        
        $alPageTree = new AlPageTree($this->container);
        $this->assertNull($alPageTree->setup());        
    }
    
    /**
     * @depends testSettingUpPageTreeWhenAnyThemeExistsReturnsANullValue
     */
    public function testSettingUpPageTreeWhenAnyLanguageExistsReturnsANullValue()
    {
        $theme = new AlTheme();
        $theme->setThemeName('AlphaLemonThemeBundle');
        $theme->setActive(1);
        $theme->save();
        
        $alPageTree = new AlPageTree($this->container);
        $this->assertNull($alPageTree->setup());
    }
    
    /**
     * @depends testSettingUpPageTreeWhenAnyLanguageExistsReturnsANullValue
     */
    public function testSettingUpPageTreeWhenAnyPageExistsReturnsANullValue()
    {
        self::$alLanguage = new AlLanguage();
        self::$alLanguage->setLanguage('en');
        self::$alLanguage->save();
        
        $alPageTree = new AlPageTree($this->container);
        $this->assertNull($alPageTree->setup());
    }
    
    /**
     * @depends testSettingUpPageTreeWhenAnyPageExistsReturnsANullValue
     */
    public function testPageTreeInitialized()
    {
        self::$alPage = new AlPage();
        self::$alPage->setPageName('index');
        self::$alPage->setTemplateName('home');
        self::$alPage->save();
        
        $alPageTree = new AlPageTree($this->container);
        $this->container->set('al_page_tree', $alPageTree);
        $alPageTree->setup();
        $this->assertEquals('AlphaLemonThemeBundle', $alPageTree->getThemeName());
        $this->assertEquals('home', $alPageTree->getTemplateName());
        $this->assertEquals('index', $alPageTree->getAlPage()->getPageName());
        $this->assertEquals('en', $alPageTree->getAlLanguage()->getLanguage());
        $this->assertNotEquals(0, $alPageTree->getSlots());
    }
    
    /**
     * @depends testPageTreeInitialized
     */
    public function testPageTreeInjectingPageAndLanguage()
    {
        $this->container->set('request', null);  
        $this->container->set('session', null);
        
        $alPageTree = new AlPageTree($this->container);
        $alPageTree->setup(self::$alLanguage, self::$alPage);
        $this->assertEquals('AlphaLemonThemeBundle', $alPageTree->getThemeName());
        $this->assertEquals('home', $alPageTree->getTemplateName());
        $this->assertEquals('index', $alPageTree->getAlPage()->getPageName());
        $this->assertEquals('en', $alPageTree->getAlLanguage()->getLanguage());
        $this->assertNotEquals(0, $alPageTree->getSlots());
    }
}