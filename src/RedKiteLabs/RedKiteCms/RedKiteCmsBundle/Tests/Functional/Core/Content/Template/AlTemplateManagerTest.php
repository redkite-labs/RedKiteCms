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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Content\Template;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;

class AlTemplateManagerTest extends TestCase 
{    
    private $dispatcher;
    private $translator;
    private $kernel;
    private $alLanguage;
    private $alPage;
    
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        
        AlphaLemonDataPopulator::depopulate();
    }
    
    protected function setUp() 
    {
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        
        $this->alLanguage = new AlLanguage();
        $this->alLanguage->setId(2);        
        $this->alPage = new AlPage();
        $this->alPage->setId(2);
        $this->alPage->setTemplateName('Home');
        
        //$this->templateManager = new AlTemplateManager($this->dispatcher, $this->translator, $this->kernel, $this->alPage, $this->alLanguage, 'BusinessWebsiteThemeBundle');
    }
    
    /**
     * @expectedException RuntimeException
     */
    public function testFailsWithInvalidTheme()
    {
        $templateManager = new AlTemplateManager($this->dispatcher, $this->translator, $this->kernel, $this->alPage, $this->alLanguage, 'fake');
    }
    
    public function testFailsWithInvalidTheme1()
    {
        $this->kernel->expects($this->any())
                ->method('locateResource')
                ->will($this->returnValue('aaa'));
        $alLanguage = new AlLanguage();
        $templateManager = new AlTemplateManager($this->dispatcher, $this->translator, $this->kernel, $this->alPage, $alLanguage, 'BusinessWebsiteThemeBundle');
    }


    public function testSlotManagers()
    {
        return;
        AlphaLemonDataPopulator::depopulate();
        
        $container = $this->setupPageTree()->getContainer(); 
        $templateManager = new AlTemplateManager($container);        
        $this->assertNotEquals(0, count($templateManager->getSlotManagers()), '_ctor() has not fill up the slot managers using the default template name for this test');
     
        $alPage = new AlPage();
        try
        {
            $alPage->setTemplateName(null);
            $templateManager = new AlTemplateManager($container, $alPage);
            $this->fail('An exception should be thrown when the template name is null');
        }
        catch(\RuntimeException $ex)
        {
            $this->assertEquals('The class \AlphaLemon\Theme\AlphaLemonThemeBundle\Core\Slots\AlphaLemonThemeBundleSlots does not exist. You must create a [ThemeName][TemplateName]Slots class for each template of your theme', $ex->getMessage());
        }
        
        try
        {
            $alPage->setTemplateName('fake');
            $templateManager = new AlTemplateManager($container, $alPage);
            $this->fail('An exception should be thrown when the template\'s class does not exists');
        }
        catch(\RuntimeException $ex)
        {
            $this->assertEquals('The class \AlphaLemon\Theme\AlphaLemonThemeBundle\Core\Slots\AlphaLemonThemeBundleFakeSlots does not exist. You must create a [ThemeName][TemplateName]Slots class for each template of your theme', $ex->getMessage());
        }
        
        $alPage->setTemplateName('home');
        $templateManager = new AlTemplateManager($container, $alPage);
        $this->assertNotEquals(0, count($templateManager->getSlotManagers()), '_ctor() has not fill up the slot managers using a template name given in lower case');
        
        return $templateManager;
    }
    
    public function testSlotManager()
    {
        return;
        $slotManager = $templateManager->getSlotManager(null);
        $this->assertNull($slotManager);
        
        $slotManager = $templateManager->getSlotManager('fake');
        $this->assertNull($slotManager);
        
        $slotManager = $templateManager->getSlotManager('logo');
        $this->assertNotNull($slotManager);
    }
    
    public function testSlotToArray()
    {
        return;
        try
        {
            $slotManager = $templateManager->slotToArray(null);
            $this->fail('An exception should be thrown when a null argument is passed to ->slotToArray() method');
        }
        catch(\InvalidArgumentException $ex)
        {
            $this->assertEquals('slotToArray accepts only strings', $ex->getMessage());
        }
        
        try
        {
            $slotManager = $templateManager->slotToArray(1);
            $this->fail('An exception should be thrown when a not string argument is passed to ->slotToArray() method');
        }
        catch(\InvalidArgumentException $ex)
        {
            $this->assertEquals('slotToArray accepts only strings', $ex->getMessage());
        }
        
        $slotManager = $templateManager->slotToArray('logo');
        $this->assertNotNull($slotManager);
    }
    
    public function testSlotsToArray()
    {
        return;
        $slotManagersArray = $templateManager->slotsToArray();        
        $slotManagers = $templateManager->getSlotManagers();
        $this->assertEquals(count($slotManagers),count($slotManagersArray));
        
        $this->assertEquals($slotManagers['logo']->toArray(), $slotManagersArray['logo']);
    }
    
    public function testPopulate()
    {
        return;
        $container = $this->setupPageTree()->getContainer(); 
        $idLanguage = array(1, $container->get('al_page_tree')->getAlLanguage()->getId());
        $idPage = array(1, $container->get('al_page_tree')->getAlPage()->getId());
        
        $contents = AlBlockQuery::create()->retrieveContents($idLanguage, $idPage)->count();
        $this->assertEquals(0, $contents, 'Some contents are saved, none was expected');
        $templateManager->populate();
        
        $contents = AlBlockQuery::create()->retrieveContents($idLanguage, $idPage)->count();
        $this->assertEquals(count($templateManager->getSlotManagers()), $contents, 'Not all the expected contents have been created');
    }
}