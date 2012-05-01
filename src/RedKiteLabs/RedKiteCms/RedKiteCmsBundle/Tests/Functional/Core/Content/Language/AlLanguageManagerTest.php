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
    private $dispatcher;
    private $translator;
    private $pageAttributesManager;
    private $blockManager;
    
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        
        AlphaLemonDataPopulator::depopulate();
    }
    
    protected function setUp() 
    {
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $this->pageAttributesManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageAttributes\AlPageAttributesManager')
                                            ->setConstructorArgs(array($this->dispatcher, $this->translator))
                                            ->getMock()
;
        $this->blockManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager')
                                            ->setConstructorArgs(array($this->dispatcher, $this->translator))
                                            ->getMock();
        
        $this->testAlLanguageManager = new AlLanguageManager($this->dispatcher, $this->translator, $this->pageAttributesManager, $this->blockManager);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetFailsWhenANotValidPropelObjectIsGiven()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        
        $this->testAlLanguageManager->set($block);
    }
    
    public function testSetANullAlLanguage()
    {
        $this->testAlLanguageManager->set(null);
        $this->assertNull($this->testAlLanguageManager->get());
    }
    
    public function testSetAlLanguage()
    {
        $language = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        
        $this->testAlLanguageManager->set($language);
        $this->assertEquals($language, $this->testAlLanguageManager->get());
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddFailsWhenAnyParamIsGiven()
    {
        $language = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $this->translator->expects($this->once())
            ->method('trans');
        
        $language->expects($this->any())
            ->method('save')
            ->will($this->throwException(new \InvalidArgumentException));
        
        $params = array();
        $this->testAlLanguageManager->set($language);
        $this->testAlLanguageManager->save($params); 
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddFailsWhenAnyExpectedParamIsGiven()
    {
        $language = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        
        $this->dispatcher->expects($this->once())
            ->method('dispatch');
        
        $this->translator->expects($this->once())
            ->method('trans');
        
        $language->expects($this->any())
            ->method('save')
            ->will($this->throwException(new \InvalidArgumentException));
        
        $params = array('Fake' => 'language');
        $this->testAlLanguageManager->set($language);
        $this->testAlLanguageManager->save($params); 
    }
    
    public function testAdd()
    {
        $params = array('language' => 'en');
        
        /*
        $testAlPageManager = new AlPageManager(
            $container
        );
        $params = array('pageName'      => 'fake page', 
                        'template'      => 'home',
                        'permalink'     => 'this is a website fake page',
                        'title'         => 'page title',
                        'description'   => 'page description',
                        'keywords'      => '');
        $testAlPageManager->save($params);*/
        
        $language = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        
        $this->dispatcher->expects($this->exactly(3))
            ->method('dispatch');
        
        $language->expects($this->once())
                ->method('save')
                ->will($this->returnValue(true));
        
        $this->testAlLanguageManager->set($language);
        $result = $this->testAlLanguageManager->save($params); 
        $this->assertEquals(true, $result);
        
        //$params = array('language' => 'en');
        //$this->assertTrue($testAlLanguageManager->save($params));
    }
    
    /*
    public function testAdd()
    {
        
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
     *
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
     *
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
    }*/
}