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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Content\Language;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlLanguageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageAttributeQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;


class AlLanguageManagerTest extends TestCase
{   
    
    public function testSetAndGet()
    {
        $testAlLanguageManager = new AlLanguageManager(
            $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface')
        );
        
        try 
        {
            $testAlLanguageManager->set($this->getMock('\BaseObject'));
            $this->fail('->set() method should raise an exception when the passed parameter is not an instance of AlLanguage object' );
        }
        catch(\InvalidArgumentException $e)
        {
        }
        
        $testAlLanguageManager->set($this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage'));
        $this->assertNotNull($testAlLanguageManager->get(), 'The AlLanguage has not been set');
        
        $testAlLanguageManager->set(null);
        $this->assertNull($testAlLanguageManager->get(), 'The AlLanguage has not been set as null');
    }
    
    public function testAdd()
    {
        AlphaLemonDataPopulator::depopulate();
        
        $container = $this->setupPageTree()->getContainer(); 
        $testAlLanguageManager = new AlLanguageManager(
            $container
        );
        
        try 
        {
            $testAlLanguageManager->save(array());
            $this->fail('->save() method should raise an exception when the passed parameter is an empty array' );
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('The language cannot be added because any parameter has been given', $e->getMessage());
        }
        
        try 
        {
            $testAlLanguageManager->save(array('Fake' => 'content'));
            $this->fail('->save() method should raise an exception when the required parameters have not been given');
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('save() method requires the following parameters: language. You must give language which is/are missing', $e->getMessage());
        }
        
        $params = array('language' => 'en');
        $this->assertTrue($testAlLanguageManager->save($params));
        $this->assertNotNull($testAlLanguageManager->get());
        $this->assertEquals('en', $testAlLanguageManager->get()->getLanguage());
        $this->assertEquals(1, $testAlLanguageManager->get()->getMainLanguage(), "The first added language has not been setted to 1 as expected");
        $this->assertEquals(1, AlLanguageQuery::create()->activeLanguages()->count());
        
        // Add a fake page with its attributeds and contents
        $container = $this->setupPageTree($testAlLanguageManager->get()->getId())->getContainer(); 
        $testAlPageManager = new AlPageManager(
            $container
        );
        $params = array('pageName'      => 'fake page', 
                        'template'      => 'home',
                        'permalink'     => 'this is a website fake page',
                        'title'         => 'page title',
                        'description'   => 'page description',
                        'keywords'      => '');
        $testAlPageManager->save($params);
        
        $testAlLanguageManager1 = new AlLanguageManager(
            $container
        );
        
        $params = array('language' => 'it');
        $this->assertTrue($testAlLanguageManager1->save($params));
        $this->assertEquals('it', $testAlLanguageManager1->get()->getLanguage());
        $this->assertEquals(0, $testAlLanguageManager1->get()->getMainLanguage(), "Another language added than the first has not been setted to 0 as expected");
        $this->assertEquals(2, AlLanguageQuery::create()->activeLanguages()->count());
        $this->assertNotEquals(0, AlBlockQuery::create()->fromLanguageId($testAlLanguageManager1->get()->getId())->count(), '->save() has not copied the contents to the new language as expected');
        
        $alPageAttribute = AlPageAttributeQuery::create()->fromLanguageId($testAlLanguageManager1->get()->getId());
        $this->assertNotEquals(0, $alPageAttribute->count(), '->save() has not copied the page attributes to the new language as expected');
        $this->assertEquals('it-this-is-a-website-fake-page', $alPageAttribute->findOne()->getPermalink());
        
        $testAlLanguageManager2 = new AlLanguageManager(
            $container
        );
        
        $params = array('language' => 'ab', 'isMain' => '1');
        $this->assertTrue($testAlLanguageManager2->save($params));
        $this->assertEquals(1, $testAlLanguageManager2->get()->getMainLanguage());
        $this->assertEquals(0, $testAlLanguageManager->get()->getMainLanguage(), "The previous main language has not changed to 0");
        
        return $testAlLanguageManager;  
    }
        
    /**
     * @depends testAdd
     */
    public function testEdit(AlLanguageManager $testAlLanguageManager)
    {
        try 
        {
            $testAlLanguageManager->save(array('fake' => 'fake-language'));
            $this->fail('->save() method should raise an exception when any of the options passed are valid' );
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('save() method requires the following parameters: language,isMain. You must give language,isMain which is/are missing', $e->getMessage());
        }
        
        try 
        {
            $this->assertTrue($testAlLanguageManager->save(array('language' => 1)));
            $this->fail('->save() method should raise an exception when the language name is not a string' );
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('AlLanguageQuery->fromLanguageName() accepts only strings', $e->getMessage());
        }
        
        $this->assertTrue($testAlLanguageManager->save(array('isMain' => 1)));
        $this->assertEquals('1', $testAlLanguageManager->get()->getMainLanguage());
        $this->assertEquals('1', AlLanguageQuery::create()->filterByMainLanguage(1)->count());
        
        $this->assertTrue($testAlLanguageManager->save(array('language' => 'xx')));
        $this->assertEquals('xx', $testAlLanguageManager->get()->getLanguage());
        
        return $testAlLanguageManager;
    }
    
    /**
     * @depends testAdd
     */
    public function testDelete(AlLanguageManager $testAlLanguageManager)
    {
        $alLanguageManager = new AlLanguageManager(
            $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface')
        );
        
        try 
        {
            $alLanguageManager->delete();
            $this->fail('->delete() method should raise an exception when the managed language is null' );
        }
        catch(\RuntimeException $e)
        {
            $this->assertEquals('Any language has been assigned to the LanguageManager. Delete operation aborted', $e->getMessage());
        }
        
        try 
        {
            $testAlLanguageManager->delete();
            $this->fail('->delete() method should raise an exception when tries to delete the main language' );
        }
        catch(\RuntimeException $e)
        {
            $this->assertEquals('The website main language cannot be deleted. To delete this language promote another one as main language, then delete it again', $e->getMessage());
        }
        
        $testAlLanguageManager->get()->setMainLanguage(0);
        
        $this->assertNotEquals(0, AlBlockQuery::create()->fromLanguageId($testAlLanguageManager->get()->getId())->count(), 'Any content exists for the current language');
        $this->assertNotEquals(0, AlPageAttributeQuery::create()->fromLanguageId($testAlLanguageManager->get()->getId())->count(), 'Any page attribute exists for the current language');
        
        $this->assertTrue($testAlLanguageManager->delete());
        $this->assertEquals(1, $testAlLanguageManager->get()->getToDelete(), '->delete() method has not set to true the to_delete field as expected');
        $this->assertEquals(0, AlBlockQuery::create()->fromLanguageId($testAlLanguageManager->get()->getId())->count(), '->delete() method has not set to true the to_delete field as expected');
        $this->assertEquals(0, AlPageAttributeQuery::create()->fromLanguageId($testAlLanguageManager->get()->getId())->count(), '->delete() method has not set to true the to_delete field as expected');
    }
}