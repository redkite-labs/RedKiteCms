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
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlContentQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageAttributeQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageAttributes\AlPageAttributesManager;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;

class AlPageManagerTest extends TestCase
{   
    
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
    
    public function testAdd()
    {
        AlphaLemonDataPopulator::depopulate();
        
        $container = $this->setupPageTree()->getContainer(); 
        $testAlPageManager = new AlPageManager(
            $container
        );
        
        try 
        {
            $testAlPageManager->save(array());
            $this->fail('->save() method should raise an exception when the passed parameter is an empty array' );
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('The page cannot be added because any parameter has been given', $e->getMessage());
        }
        
        try 
        {
            $testAlPageManager->save(array('Fake' => 'content'));
            $this->fail('->save() method should raise an exception when the required parameters have not been given');
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('save() method requires the following parameters: pageName,template. You must give pageName,template which is/are missing', $e->getMessage());
        }
        
        try 
        {
            $testAlPageManager->save(array('pageName' => '', 'template' => 'home'));
            $this->fail('->save() method should raise an exception when the passed parameter doesn\'t contain the pageName parameter');
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('The name to assign to the page cannot be null', $e->getMessage());
        }
        
        try 
        {
            $testAlPageManager->save(array('pageName' => 'fake page', 'template' => ''));
            $this->fail('->save() method should raise an exception when the passed parameter doesn\'t contain the template parameter');
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('The page requires at least a template', $e->getMessage());
        }
        
        try 
        {
            $testAlPageManager->save(array('pageName' => 'fake page', 'template' => 'home'));
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
        $testAlPageManager = new AlPageManager(
            $container
        );
        
        $params = array('pageName'      => 'fake page', 
                        'template'      => 'home',
                        'permalink'     => 'this is a website fake page',
                        'title'         => 'page title',
                        'description'   => 'page description',
                        'keywords'      => '');
        $this->assertTrue($testAlPageManager->save($params));//exit;
        $this->assertNotNull($testAlPageManager->get());
        $this->assertEquals('fake-page', $testAlPageManager->get()->getPageName());
        $this->assertEquals(1, $testAlPageManager->get()->getIsHome(), 'The first page added has not prometed as the website\'s home page');
        
        $repeated = $this->retrieveRepeatedContentsByTemplate($container, 'Home' );
        $this->checkAddedContents($testAlPageManager, $repeated);
        
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
        $this->assertEquals(0, $testAlPageManager->get()->getIsHome(), 'When a page is promoted to home page, the previous home page isHomem parameter must be false');
        
        /*
        $container = $this->setupPageTree()->getContainer(); 
        $testAlLanguageManager = new \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager (
            $container
        );
        $testAlLanguageManager->save(array('language' => 'it'));*/
        
        return $testAlPageManager;  
    }
        
    /**
     * @depends testAdd
     */
    public function testEdit(AlPageManager $testAlPageManager)
    {
        try 
        {
            $testAlPageManager->save(array());
            $this->fail('->save() method should raise an exception when the passed parameter is an empty array' );
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('save() method requires at least one valid parameter, any one has been given', $e->getMessage());
        }
        
        try 
        {
            $testAlPageManager->save(array('pageName' => 'fake name changed'));
            $this->fail('->save() method should raise an exception when the passed parameter doesn\'t contains the languageId key' );
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('save() method requires the following parameters: languageId. You must give languageId which is/are missing', $e->getMessage());
        }
        
        $pageAttributes = $testAlPageManager->get()->getAlPageAttributes(); 
        $languageId =  $pageAttributes[0]->getLanguageId();
        
        $pageName = $testAlPageManager->get()->getPageName();
        $templateName = $testAlPageManager->get()->getTemplateName();
        $isHome = $testAlPageManager->get()->getIsHome();
        
        //$this->assertTrue($testAlPageManager->save(array('languageId' => 0, 'pageName' => '', 'template' => '', 'isHome' => '')));exit;
        
        $this->assertTrue($testAlPageManager->save(array('languageId' => $languageId, 'pageName' => '', 'template' => '', 'isHome' => '')));
        $this->assertEquals($pageName, $testAlPageManager->get()->getPageName(), 'The page name has been set to empty string when it was expected to remain untouched');
        $this->assertEquals($templateName, $testAlPageManager->get()->getTemplateName(), 'The template name has been set to empty string when it was expected to remain untouched');
        $this->assertEquals($isHome, $testAlPageManager->get()->getIsHome(), 'The isHome page flag has been set to empty string when it was expected to remain untouched');
        
        $this->assertTrue($testAlPageManager->save(array('languageId' => $languageId, 'pageName' => 'New fake name', 'template' => 'internal', 'isHome' => 1)));
        $this->assertEquals('new-fake-name', $testAlPageManager->get()->getPageName(), 'The page name has not been changed');
        $this->assertEquals('internal', $testAlPageManager->get()->getTemplateName(), 'The template name has not been changed');
        $this->assertEquals(1, $testAlPageManager->get()->getIsHome(), 'The isHome page flag has not been changed ');
        
        $this->assertTrue($testAlPageManager->save(array('languageId' => $languageId, 'template' => 'home')));
        $this->assertEquals('home', $testAlPageManager->get()->getTemplateName(), 'The template name has not been changed');
        
        $this->assertTrue($testAlPageManager->save(array('languageId' => $languageId, 'isHome' => 1)));
        $this->assertEquals(1, $testAlPageManager->get()->getIsHome(), 'The isHome page flag has been changed when it was expected to remain untouched');
        
        $home = AlPageQuery::create()->homePage()->count();
        $this->assertEquals(1, $home, 'The isHome page flag has been assigned to more than one page');  
        
        return $testAlPageManager;
    }
    
    /**
     * @depends testEdit
     */
    public function testDelete(AlPageManager $testAlPageManager)
    {
        try 
        {
            $testAlPageManager->delete();
            $this->fail('->delete() method should not delete the home page' );
        }
        catch(\RuntimeException $e)
        {
            $this->assertEquals('It is not allowed to remove the website\'s home page. Promote another page as the home of your website, then remove this one', $e->getMessage());
        }
        
        $testAlPageManager->get()->setIsHome(0);
        
        $this->assertTrue($testAlPageManager->delete()); 
        $this->assertEquals(1, $testAlPageManager->get()->getToDelete(), '->delete() method has not set to true the to_delete field as expected');
        $this->assertEquals(0, AlContentQuery::create()->fromPageId($testAlPageManager->get()->getId())->count());
        $this->assertEquals(0, AlPageAttributeQuery::create()->fromPageId($testAlPageManager->get()->getId())->count());
    }
    
    private function checkAddedContents($page, $repeated)
    {
        $pageContents = AlContentQuery::create('a')->where('a.LanguageId <> ?', 1)->filterByPageId($page->get()->getId())->filterByToDelete(0)->count();
        $languageContents = AlContentQuery::create('a')->where('a.LanguageId <> ?', 1)->filterByPageId(1)->filterByToDelete(0)->count();
        $siteContents = AlContentQuery::create()->filterByLanguageId(1)->filterByPageId(1)->filterByToDelete(0)->count();
        
        $this->assertEquals($pageContents, count($repeated["page"]));
        $this->assertEquals($languageContents, count($repeated["language"]));
        $this->assertEquals($siteContents, count($repeated["site"]));        
    }
    
    private function retrieveRepeatedContentsByTemplate($container, $templateName)
    {
        $pageTree = $container->get('al_page_tree');
        $className = \sprintf('\Themes\%s\Core\Slots\%s%sSlots', $pageTree->getThemeName(), $pageTree->getThemeName(), $templateName);
        $templateSlots = new $className();
        
        return $templateSlots->toArray();
    }
}