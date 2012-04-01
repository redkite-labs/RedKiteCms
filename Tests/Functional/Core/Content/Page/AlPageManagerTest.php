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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Content\Page;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageAttributeQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageAttributes\AlPageAttributesManager;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;

class AlPageManagerTest extends TestCase
{   
    private static $testAlPageManager;
    
    public function testSetAndGet()
    {
        $testAlPageManager = new AlPageManager(
            $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface')
        );
        
        try 
        {
            $testAlPageManager->set($this->getMock('\BaseObject'));
            $this->fail('->set() method should raise an exception when the passed parameter is not an instance of AlPage object' );
        }
        catch(\InvalidArgumentException $e)
        {
        }
        
        $testAlPageManager->set($this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Model\AlPage'));
        $this->assertNotNull($testAlPageManager->get(), 'The AlPage has not been set');
        
        $testAlPageManager->set(null);
        $this->assertNull($testAlPageManager->get(), 'The AlPage has not been set as null');
    }
    
    public function testAddingAnEmptyArrayAsInputThrowsAnException()
    {
        AlphaLemonDataPopulator::depopulate();
        
        $container = $this->setupPageTree()->getContainer(); 
        self::$testAlPageManager = new AlPageManager(
            $container
        );
        
        try 
        {
            self::$testAlPageManager->save(array());
            $this->fail('->save() method should raise an exception when the passed parameter is an empty array' );
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('The page cannot be added because any parameter has been given', $e->getMessage());
        }
    }
    
    public function testAdd()
    {    
        try 
        {
            self::$testAlPageManager->save(array('Fake' => 'content'));
            $this->fail('->save() method should raise an exception when the required parameters have not been given');
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('save() method requires the following parameters: pageName,template. You must give pageName,template which is/are missing', $e->getMessage());
        }
        
        try 
        {
            self::$testAlPageManager->save(array('pageName' => '', 'template' => 'home'));
            $this->fail('->save() method should raise an exception when the passed parameter doesn\'t contain the pageName parameter');
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('The name to assign to the page cannot be null', $e->getMessage());
        }
        
        try 
        {
            self::$testAlPageManager->save(array('pageName' => 'fake page', 'template' => ''));
            $this->fail('->save() method should raise an exception when the passed parameter doesn\'t contain the template parameter');
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('The page requires at least a template', $e->getMessage());
        }
        
        try 
        {
            self::$testAlPageManager->save(array('pageName' => 'fake page', 'template' => 'home'));
            $this->fail('->save() method should raise an exception when the website has any language');
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('The web site has any language inserted. Please add a new language before adding a page', $e->getMessage());
        }
        
        $alLanguage = new AlLanguage();
        $alLanguage->setLanguage('en');
        $alLanguage->setMainLanguage(1);
        $alLanguage->save(); 
        
        $container = $this->setupPageTree($alLanguage->getId())->getContainer(); 
        self::$testAlPageManager = new AlPageManager(
            $container
        );
        
        $params = array('pageName'      => 'fake page', 
                        'template'      => 'home',
                        'permalink'     => 'this is a website fake page',
                        'title'         => 'page title',
                        'description'   => 'page description',
                        'keywords'      => '');
        $this->assertTrue(self::$testAlPageManager->save($params));//exit;
        $this->assertNotNull(self::$testAlPageManager->get());
        $this->assertEquals('fake-page', self::$testAlPageManager->get()->getPageName());
        $this->assertEquals(1, self::$testAlPageManager->get()->getIsHome(), 'The first page added has not prometed as the website\'s home page');
        
        $repeated = $this->retrieveRepeatedContentsByTemplate($container, 'Home' );
        $this->checkAddedContents(self::$testAlPageManager, $repeated);
        
        $testAlPageManager1 = new AlPageManager(
            $container
        );
        
        try 
        {
            $testAlPageManager1->save($params);
            $this->fail('->save() method should raise an exception when the pagename already exists');
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('The name to assign to the page already exists in the website. Page name must be unique.', $e->getMessage());
        }
        
        $params['pageName'] = 'fake page 1'; 
        $this->assertTrue($testAlPageManager1->save($params));
        $this->assertEquals(0, $testAlPageManager1->get()->getIsHome(), 'When at least a page exists, is home must be false');        
        $this->checkAddedContents($testAlPageManager1, $repeated);
        
        $alPageAttribute = AlPageAttributeQuery::create()->fromPageId($testAlPageManager1->get()->getId());
        $this->assertEquals(1, $alPageAttribute->count(), '->save() has not copied the page attributes to the new language as expected');
        
        $repeated = $this->retrieveRepeatedContentsByTemplate($container, 'Internal' );
        $testAlPageManager2 = new AlPageManager(
            $container
        );
        $params['pageName'] = 'fake page 2';
        $params['template'] = 'internal';
        $this->assertTrue($testAlPageManager2->save($params));
        $this->checkAddedContents($testAlPageManager2, $repeated);
        
        $testAlPageManager3 = new AlPageManager(
            $container
        );
        $params['pageName'] = 'fake page 3';
        $params['isHome'] = 1;
        $this->assertTrue($testAlPageManager3->save($params));        
        $this->assertEquals(1, $testAlPageManager3->get()->getIsHome(), 'When the isHome param is true, is home must be true');
        $this->assertEquals(0, self::$testAlPageManager->get()->getIsHome(), 'When a page is promoted to home page, the previous home page isHome parameter must be false');        
    }
        
    /**
     * @depends testAdd
     */
    public function testEdit()
    {
        try 
        {
            self::$testAlPageManager->save(array());
            $this->fail('->save() method should raise an exception when the passed parameter is an empty array' );
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('save() method requires at least one valid parameter, any one has been given', $e->getMessage());
        }
        
        $pageAttributes = self::$testAlPageManager->get()->getAlPageAttributes(); 
        $languageId =  $pageAttributes[0]->getLanguageId();
        
        $pageName = self::$testAlPageManager->get()->getPageName();
        $templateName = self::$testAlPageManager->get()->getTemplateName();
        $isHome = self::$testAlPageManager->get()->getIsHome();
        
        $this->assertTrue(self::$testAlPageManager->save(array('languageId' => 0, 'pageName' => '', 'template' => '', 'isHome' => '')));
        
        $this->assertTrue(self::$testAlPageManager->save(array('languageId' => $languageId, 'pageName' => '', 'template' => '', 'isHome' => '')));
        $this->assertEquals($pageName, self::$testAlPageManager->get()->getPageName(), 'The page name has been set to empty string when it was expected to remain untouched');
        $this->assertEquals($templateName, self::$testAlPageManager->get()->getTemplateName(), 'The template name has been set to empty string when it was expected to remain untouched');
        $this->assertEquals($isHome, self::$testAlPageManager->get()->getIsHome(), 'The isHome page flag has been set to empty string when it was expected to remain untouched');
        
        $this->assertTrue(self::$testAlPageManager->save(array('languageId' => $languageId, 'pageName' => 'New fake name', 'template' => 'internal', 'isHome' => 1)));
        $this->assertEquals('new-fake-name', self::$testAlPageManager->get()->getPageName(), 'The page name has not been changed');
        $this->assertEquals('internal', self::$testAlPageManager->get()->getTemplateName(), 'The template name has not been changed');
        $this->assertEquals(1, self::$testAlPageManager->get()->getIsHome(), 'The isHome page flag has not been changed ');
        
        $this->assertTrue(self::$testAlPageManager->save(array('languageId' => $languageId, 'template' => 'home')));
        $this->assertEquals('home', self::$testAlPageManager->get()->getTemplateName(), 'The template name has not been changed');
        
        $this->assertTrue(self::$testAlPageManager->save(array('languageId' => $languageId, 'isHome' => 1)));
        $this->assertEquals(1, self::$testAlPageManager->get()->getIsHome(), 'The isHome page flag has been changed when it was expected to remain untouched');
        
        $home = AlPageQuery::create()->homePage()->count();
        $this->assertEquals(1, $home, 'The isHome page flag has been assigned to more than one page');  
        
        $this->assertTrue(self::$testAlPageManager->save(array('languageId' => $languageId, 'isHome' => 1)));
    }
    
    public function testEditPermalink()
    {
        $pageAttributes = self::$testAlPageManager->get()->getAlPageAttributes();
        $pageAttribute = $pageAttributes[0];
        $this->assertEquals('this-is-a-website-fake-page', $pageAttribute->getPermalink());
        $this->assertTrue(self::$testAlPageManager->save(array('languageId' => $pageAttribute->getLanguageId(), 'permalink' => 'a new permalink')));
        $this->assertEquals('a-new-permalink', $pageAttribute->getPermalink());
    }
    
    public function testEditMetatags()
    {
        $pageAttributes = self::$testAlPageManager->get()->getAlPageAttributes();
        $pageAttribute = $pageAttributes[0];
        $this->assertEquals('page title', $pageAttribute->getMetaTitle());
        $this->assertTrue(self::$testAlPageManager->save(array('languageId' => $pageAttribute->getLanguageId(), 'title' => 'a new title')));
        $this->assertEquals('a new title', $pageAttribute->getMetaTitle());
        
        $this->assertEquals('page description', $pageAttribute->getMetaDescription());
        $this->assertTrue(self::$testAlPageManager->save(array('languageId' => $pageAttribute->getLanguageId(), 'description' => 'a new description')));
        $this->assertEquals('a new description', $pageAttribute->getMetaDescription());
        
        $this->assertEquals('', $pageAttribute->getMetaKeywords());
        $this->assertTrue(self::$testAlPageManager->save(array('languageId' => $pageAttribute->getLanguageId(), 'keywords' => 'some,fake,keywords')));
        $this->assertEquals('some,fake,keywords', $pageAttribute->getMetaKeywords());
    }
    
    /**
     * @depends testEdit
     */
    public function testDelete()
    {
        try 
        {
            self::$testAlPageManager->delete();
            $this->fail('->delete() method should not delete the home page' );
        }
        catch(\RuntimeException $e)
        {
            $this->assertEquals('It is not allowed to remove the website\'s home page. Promote another page as the home of your website, then remove this one', $e->getMessage());
        }
        
        self::$testAlPageManager->get()->setIsHome(0);
        
        $this->assertTrue(self::$testAlPageManager->delete()); 
        $this->assertEquals(1, self::$testAlPageManager->get()->getToDelete(), '->delete() method has not set to true the to_delete field as expected');
        $this->assertEquals(0, AlBlockQuery::create()->fromPageId(self::$testAlPageManager->get()->getId())->count());
        $this->assertEquals(0, AlPageAttributeQuery::create()->fromPageId(self::$testAlPageManager->get()->getId())->count());
    }
    
    /**
     * @depends testEdit
     */
    public function testAddPageWhenSiteHasMoreLanguages()
    {
        AlPageQuery::create()->deleteAll();
        
        $alLanguage = new AlLanguage();
        $alLanguage->setLanguage('it');
        $alLanguage->save(); 
        
        $container = $this->setupPageTree($alLanguage->getId())->getContainer(); 
        self::$testAlPageManager = new AlPageManager(
            $container
        );
        
        $this->assertEquals(0, AlPageQuery::create()->filterByToDelete(0)->count());
        $this->assertEquals(0, AlPageAttributeQuery::create()->filterByToDelete(0)->count());
        
        $params = array('pageName'      => 'fake add page', 
                        'template'      => 'home',
                        'permalink'     => 'this is a website fake page',
                        'title'         => 'page title',
                        'description'   => 'page description',
                        'keywords'      => '');
        $this->assertTrue(self::$testAlPageManager->save($params));
        $this->assertEquals(1, AlPageQuery::create()->filterByToDelete(0)->count());
        $this->assertEquals(2, AlPageAttributeQuery::create()->filterByToDelete(0)->count());
        $alPageAttribute = AlPageAttributeQuery::create()->fromPageAndLanguage(self::$testAlPageManager->get()->getId(),$alLanguage->getId());
        $this->assertEquals(1, $alPageAttribute->count(), '->save() has not copied the page attributes to the new language as expected');
        $this->assertEquals('it-this-is-a-website-fake-page', $alPageAttribute->findOne()->getPermalink());
    }
    
    private function checkAddedContents($page, $repeated)
    {
        $pageContents = AlBlockQuery::create('a')->where('a.LanguageId <> ?', 1)->filterByPageId($page->get()->getId())->filterByToDelete(0)->count();
        $languageContents = AlBlockQuery::create('a')->where('a.LanguageId <> ?', 1)->filterByPageId(1)->filterByToDelete(0)->count();
        $siteContents = AlBlockQuery::create()->filterByLanguageId(1)->filterByPageId(1)->filterByToDelete(0)->count();
        
        $this->assertEquals($pageContents, count($repeated["page"]));
        $this->assertEquals($languageContents, count($repeated["language"]));
        //$this->assertEquals($siteContents, count($repeated["site"]));        
    }
    
    private function retrieveRepeatedContentsByTemplate($container, $templateName)
    {
        $pageTree = $container->get('al_page_tree');
        $className = \sprintf('\AlphaLemon\Theme\%s\Core\Slots\%s%sSlots', $pageTree->getThemeName(), $pageTree->getThemeName(), $templateName);
        $templateSlots = new $className();
        
        return $templateSlots->toArray();
    }
}