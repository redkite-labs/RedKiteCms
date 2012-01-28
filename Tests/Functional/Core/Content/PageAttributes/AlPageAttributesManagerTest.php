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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Content\PageAttributes;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlContentQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageAttributeQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageAttributes\AlPageAttributesManager;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;


class AlPageAttributesManagerTest extends TestCase
{   
    
    public function testSetAndGet()
    {
        $testAlPageAttributesManager = new AlPageAttributesManager(
            $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface')
        );
        
        try 
        {
            $testAlPageAttributesManager->set($this->getMock('\BaseObject'));
            $this->fail('->set() method should raise an exception when the passed parameter is not an instance of AlPageAttributes object' );
        }
        catch(\InvalidArgumentException $e)
        {
        }
        
        $testAlPageAttributesManager->set($this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Model\AlPageAttribute'));
        $this->assertNotNull($testAlPageAttributesManager->get(), 'The AlPageAttributes has not been set');
        
        $testAlPageAttributesManager->set(null);
        $this->assertNull($testAlPageAttributesManager->get(), 'The AlPageAttributes has not been set as null');
    }
    
    public function testAdd()
    {
        AlphaLemonDataPopulator::depopulate();
        
        $container = $this->setupPageTree()->getContainer(); 
        $testAlPageAttributesManager = new AlPageAttributesManager(
            $container
        );
        
        try 
        {
            $testAlPageAttributesManager->save(array());
            $this->fail('->save() method should raise an exception when the passed parameter is an empty array' );
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('The page cannot be added because any parameter has been given', $e->getMessage());
        }
        
        try 
        {
            $testAlPageAttributesManager->save(array('Fake' => 'content'));
            $this->fail('->save() method should raise an exception when the required parameters have not been given');
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('save() method requires the following parameters: idPage,idLanguage,permalink,title,description,keywords. You must give idPage,idLanguage,permalink,title,description,keywords which is/are missing', $e->getMessage());
        }
        
        try 
        {
            $testAlPageAttributesManager->save(array('idPage' => '', 'idLanguage' => '2', 'permalink' => 'fake permalink', 'title' => 'My title', 'description' => 'My description', 'keywords' => 'My keywords'));
            $this->fail('->save() method should raise an exception when the idPage parameter is empty');
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('The idPage parameter is mandatory to save a page attribute object', $e->getMessage());
        }
        
        try 
        {
            $testAlPageAttributesManager->save(array('idPage' => '2', 'idLanguage' => '', 'permalink' => 'fake permalink', 'title' => 'My title', 'description' => 'My description', 'keywords' => 'My keywords'));
            $this->fail('->save() method should raise an exception when the idLanguage parameter is empty');
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('The idLanguage parameter is mandatory to save a page attribute object', $e->getMessage());
        }
        
        try 
        {
            $testAlPageAttributesManager->save(array('idPage' => '2', 'idLanguage' => '2', 'permalink' => '', 'title' => 'My title', 'description' => 'My description', 'keywords' => 'My keywords'));
            $this->fail('->save() method should raise an exception when the permalink parameter is empty');
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('The permalink parameter is mandatory to save a page attribute object', $e->getMessage());
        }
        
        $params = array('idPage' => '2', 'idLanguage' => '2', 'permalink' => 'fake permalink', 'title' => 'My title', 'description' => 'My description', 'keywords' => 'My keywords');
        $this->assertTrue($testAlPageAttributesManager->save($params));
        $this->assertNotNull($testAlPageAttributesManager->get());
        $this->assertEquals(2, $testAlPageAttributesManager->get()->getPageId());
        $this->assertEquals(2, $testAlPageAttributesManager->get()->getLanguageId());
        $this->assertEquals('fake-permalink', $testAlPageAttributesManager->get()->getPermalink());
        $this->assertEquals('My title', $testAlPageAttributesManager->get()->getMetaTitle());
        $this->assertEquals('My description', $testAlPageAttributesManager->get()->getMetaDescription());
        $this->assertEquals('My keywords', $testAlPageAttributesManager->get()->getMetaKeywords());
        
        return $testAlPageAttributesManager;  
    }
        
    /**
     * @depends testAdd
     */
    public function testEdit(AlPageAttributesManager $testAlPageAttributesManager)
    {
        try 
        {
            $testAlPageAttributesManager->save(array());
            $this->fail('->save() method should raise an exception when the passed parameter is an empty array' );
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('Any value has been given: nothing to update', $e->getMessage());
        }
        
        $this->assertNull($testAlPageAttributesManager->save(array('fake' => 'fake permalink changed')), '->save() methid must return null when one of the required params are not provided');
        $this->assertTrue($testAlPageAttributesManager->save(array('permalink' => 'fake permalink changed')));
        
        $this->assertEquals('fake-permalink-changed', $testAlPageAttributesManager->get()->getPermalink());
        $this->assertEquals('My title', $testAlPageAttributesManager->get()->getMetaTitle());
        $this->assertEquals('My description', $testAlPageAttributesManager->get()->getMetaDescription());
        $this->assertEquals('My keywords', $testAlPageAttributesManager->get()->getMetaKeywords());
        
        $this->assertTrue($testAlPageAttributesManager->save(array('permalink' => '&ake permalink changed', 'title' => 'My title changed', 'description' => 'My description changed', 'keywords' => 'My keywords changed')));
        $this->assertEquals('ake-permalink-changed', $testAlPageAttributesManager->get()->getPermalink());
        $this->assertEquals('My title changed', $testAlPageAttributesManager->get()->getMetaTitle());
        $this->assertEquals('My description changed', $testAlPageAttributesManager->get()->getMetaDescription());
        $this->assertEquals('My keywords changed', $testAlPageAttributesManager->get()->getMetaKeywords());
        
        return $testAlPageAttributesManager;
    }
    
    /**
     * @depends testAdd
     */
    public function testDelete(AlPageAttributesManager $testAlPageAttributesManager)
    {
        $this->assertTrue($testAlPageAttributesManager->delete());
        $this->assertEquals(1, $testAlPageAttributesManager->get()->getToDelete(), '->delete() method has not set to true the to_delete field as expected');        
    }
}