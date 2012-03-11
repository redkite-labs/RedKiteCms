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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Content\Block;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;

use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;

class AlBlockManagerFunctionalTest extends AlBlockManager
{
    public function getDefaultValue()
    {
        return array("HtmlContent" => "Test value");
    }
}

// This class has default value not valid because an array is required
class AlBlockManagerFake extends AlBlockManager
{
    public function getDefaultValue()
    {
        return "Test value";
    }
}

// This class has a valid default value but any of the available options is defined
class AlBlockManagerFake1 extends AlBlockManager
{
    public function getDefaultValue()
    {
        return array("Fake" => "Test value");
    }
}

/*
class Service
{
    public function onBeforeContentAdding(\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\BeforeBlockAddingEvent $event)
    {
    }
}*/

/*
*/
class AlBlockManagerTest extends TestCase 
{    
    public function testSet()
    {
        $testAlBlockManager = new AlBlockManagerFunctionalTest(
            $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface')
        );
        
        try 
        {
            $testAlBlockManager->set($this->getMock('\BaseObject'));
            $this->fail('->save() method should raise an exception when the passed parameter is not an instance of AlBlock object' );
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('AlBlockManager accepts only AlBlock propel objects', $e->getMessage());
        }
        
        $testAlBlockManager->set($this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock'));
        $this->assertNotNull($testAlBlockManager->get(), 'The AlBlock has not been set');
        
        $testAlBlockManager->set(null);
        $this->assertNull($testAlBlockManager->get(), 'The AlBlock has not been set as null');
    }
    
    
    public function testAdd()
    {
        AlphaLemonDataPopulator::depopulate();
        
        $testAlBlockManager = new AlBlockManagerFunctionalTest(        
            $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface')
        );
        
        try 
        {
            $testAlBlockManager->save(array());
            $this->fail('->save() method should raise an exception when the passed parameter is an empty array' );
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('save() method requires at least one valid parameter, any one has been given', $e->getMessage());
        }
        
        try 
        {
            $testAlBlockManager->save(array('Fake' => 'content'));
            $this->fail('->save() method should raise an exception when the passed parameter doesn\'t contain any expected paremeter by the save() method');
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('save() method requires the following parameters: %required%. You must give %diff% which is/are missing', $e->getMessage());
        }
        
        try 
        {
            $params = array("PageId" => 2,
                            "LanguageId" => 2,
                            "HtmlContent" => 'Fake content', 
                            "ClassName" => "Text");
            $testAlBlockManager->save($params);
            $this->fail('->save() method should raise an exception when at least one of the required params are missing');
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('save() method requires the following parameters: %required%. You must give %diff% which is/are missing', $e->getMessage());
        }
        
        // End test section -----------------------------------------
        /*
        $service = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Content\Block\Service');
        $container->set('service.listener', $service);

        $dispatcher = new \Symfony\Bundle\FrameworkBundle\ContainerAwareEventDispatcher\ContainerAwareEventDispatcher($container);
        $dispatcher->addListenerService('onEvent', array('service.listener', 'onEvent'), 5);
        */
        $params = array("PageId" => 2,
                        "LanguageId" => 2,
                        "SlotName" => 'test',
                        "ClassName" => "Text");
        $result = $testAlBlockManager->save($params);
        $default = $testAlBlockManager->getDefaultValue();
        $this->assertEquals($default["HtmlContent"], $testAlBlockManager->get()->getHtmlContent(), '->save() method has not set the default content to html_content field');
        /*
        $service = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Core\Listener\AlBlockListener');
        $service->expects($this->once())
        ->method('onBeforeContentAdding');
        */
        $testAlBlockManager1 = new AlBlockManagerFake(
            $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface')
        );
        
        try 
        {
            $params = array("PageId" => 2,
                            "LanguageId" => 2,
                            "SlotName" => 'test',
                            "ClassName" => "Text");
            $result = $testAlBlockManager1->save($params);
            $this->fail('->save() method should raise an exception when the default value is not an array');
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('The abstract method getDefaultValue() defined for the object %className% must return an array', $e->getMessage());
        }
        
        $testAlBlockManager2 = new AlBlockManagerFake1(
            $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface')
        );
        try 
        {
            $params = array("PageId" => 2,
                            "LanguageId" => 2,
                            "SlotName" => 'test',
                            "ClassName" => "Text");
            $result = $testAlBlockManager2->save($params);
            $this->fail('->save() method should raise an exception when the default value has any of the available options');
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('%className% requires at least one of the following options: "%options%". Your input parameters are: "%parameters%"', $e->getMessage());
        }
        
        return $testAlBlockManager;
    }
    
    /**
     * @depends testAdd
     */
    public function testEdit($testAlBlockManager)
    {
        try 
        {
            $testAlBlockManager->save(array());
            $this->fail('->save() method should raise an exception when the passed parameter is an empty array' );
        }
        catch(\InvalidArgumentException $e)
        {
            $this->assertEquals('save() method requires at least one valid parameter, any one has been given', $e->getMessage());
        }
        
        // End test section -----------------------------------------   
        
        $parameters = array('HtmlContent' => 'Fake html content saved');
        
        $testAlBlockManager->save($parameters);
        $this->assertEquals('Fake html content saved', $testAlBlockManager->get()->getHtmlContent(), '->save() method has not set the default html_content field as expected');
        
        $parameters = array('InternalJavascript' => 'Fake internal javascript saved');        
        $testAlBlockManager->save($parameters);
        $this->assertEquals('Fake internal javascript saved', $testAlBlockManager->get()->getInternalJavascript(), '->save() method has not set the default internal_javascript field as expected');
        
        $parameters = array('ExternalJavascript' => 'Fake external javascript saved');        
        $testAlBlockManager->save($parameters);
        $this->assertEquals('Fake external javascript saved', $testAlBlockManager->get()->getExternalJavascript(), '->save() method has not set the default external_javascript field as expected');
        
        $parameters = array('InternalStylesheet' => 'Fake internal stylesheet saved');        
        $testAlBlockManager->save($parameters);
        $this->assertEquals('Fake internal stylesheet saved', $testAlBlockManager->get()->getInternalStylesheet(), '->save() method has not set the default internal_stylesheet field as expected');
        
        $parameters = array('ExternalStylesheet' => 'Fake external stylesheet saved');        
        $testAlBlockManager->save($parameters);
        $this->assertEquals('Fake external stylesheet saved', $testAlBlockManager->get()->getExternalStylesheet(), '->save() method has not set the default external_stylesheet field as expected');
        
        // End test section -----------------------------------------        
               
        return $testAlBlockManager;
    }
    
    /**
     * @depends testAdd
     */
    public function testDelete($testAlBlockManager)
    {
        $testAlBlockManager->delete();
        $this->assertEquals(1, $testAlBlockManager->get()->getToDelete(), '->delete() method has not set to true the to_delete field as expected');        
    }
    
    /**
     * @depends testAdd
     */
    public function testAlBlockToArray($testAlBlockManager)
    { 
        $array = $testAlBlockManager->toArray();
        
        $this->assertTrue(array_key_exists('Id', $array), '->toArray() method has not set the expected key Id');
        $this->assertTrue(array_key_exists('HideInEditMode', $array), '->toArray() method has not set the expected key HideInEditMode');
        $this->assertTrue(array_key_exists('HtmlContent', $array), '->toArray() method has not set the expected key HtmlContent');
        $this->assertTrue(array_key_exists('ExternalJavascript', $array), '->toArray() method has not set the expected key ExternalJavascript');
        $this->assertTrue(array_key_exists('InternalJavascript', $array), '->toArray() method has not set the expected key InternalJavascript');
        $this->assertTrue(array_key_exists('ExternalStylesheet', $array), '->toArray() method has not set the expected key ExternalStylesheet');
        $this->assertTrue(array_key_exists('InternalStylesheet', $array), '->toArray() method has not set the expected key InternalStylesheet');
        $this->assertTrue(array_key_exists('Position', $array), '->toArray() method has not set the expected key Position');
        $this->assertTrue(array_key_exists('Type', $array), '->toArray() method has not set the expected key Type');
         
    }
}