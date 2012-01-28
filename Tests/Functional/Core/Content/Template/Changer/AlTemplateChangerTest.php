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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Content\Template\Changer;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlContentQuery;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\Changer\AlTemplateChanger;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlots;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;

class Al2011ThemeBundleInternal1Slot extends AlTemplateSlots
{
}

class Al2011ThemeBundleInternal2Slot extends AlTemplateSlots
{
    public function configure()
    {
        return array('fake_slot_1' => new AlSlot('fake_slot_1', array('repeated' => 'page')),
                     'fake_slot_2' => new AlSlot('fake_slot_2', array('repeated' => 'language')),
                     'fake_slot_3' => new AlSlot('fake_slot_3', array('repeated' => 'site')),
                     'logo' => new AlSlot('logo', array('repeated' => 'page')),                     
                     'nav_menu' => new AlSlot('nav_menu', array('repeated' => 'page')),
                     'content' => new AlSlot('content', array('repeated' => 'language')),
                     'middle_sidebar' => new AlSlot('middle_sidebar', array('repeated' => 'language')),
                     'ads_box' => new AlSlot('ads_box', array('repeated' => 'site')),
                    );
    }
}

class AlTemplateChangerTest extends TestCase 
{    
    public function testGetOperations()
    {
        AlphaLemonDataPopulator::depopulate();
              
        $alLanguage = new AlLanguage();
        $alLanguage->setLanguage('en');
        $alLanguage->setMainLanguage(1);
        $alLanguage->save(); 
        $alLanguage->setId(2);
        $alLanguage->save();
        
        $alPage = new AlPage();
        $alPage->setPageName('test');
        $alPage->setIsHome(1);
        $alPage->save(); 
        $alPage->setId(2);
        $alPage->save();
        
        $container = $this->setupPageTree(2, 2)->getContainer();                 
        $templateManager = new AlTemplateManager($container, null, null, null, null, '\AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Content\Template\Changer\Al2011ThemeBundleInternal1Slot');       
        $templateManager->populate();
        
        $templateManager1 = new AlTemplateManager($container, null, null, null, null, '\AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Content\Template\Changer\Al2011ThemeBundleInternal2Slot');
        
        $templateChanger = new AlTemplateChanger($container, $templateManager, $templateManager1);
        $operations = $templateChanger->getOperations(); // print_r($operations);exit;

        $this->assertEquals(array('add', 'change', 'remove'), array_keys($operations)); 
        $add = array_slice($operations['add'], 0);
        $this->assertTrue(in_array('fake_slot_1', $add['page']));
        $this->assertTrue(in_array('fake_slot_2', $add['language']));
        $this->assertTrue(in_array('fake_slot_3', $add['site']));
        
        $change = array_slice($operations['change'][0], 0);
        $this->assertTrue(in_array('logo', $change['site']['page']));
        $this->assertTrue(in_array('nav_menu', $change['language']['page']));
        
        $change = array_slice($operations['change'][1], 0);
        $this->assertTrue(in_array('content', $change['page']['language']));
        $this->assertTrue(in_array('middle_sidebar', $change['page']['language']));
        
        $change = array_slice($operations['change'][2], 0);
        $this->assertTrue(in_array('ads_box', $change['page']['site']));
        
        $this->assertTrue(empty($operations['remove']));
        
        return $templateChanger;
    }
    
    /**
     * @depends testGetOperations
     */
    public function testChange(AlTemplateChanger $templateChanger)
    {
        $templateChanger->change();
        $addedContents = AlContentQuery::create()->retrieveContents(array(1, 2), array(1, 2), array('fake_slot_1', 'fake_slot_2', 'fake_slot_3'))->find(); 
        $this->assertNotNull($addedContents);
        $this->assertEquals(3, $addedContents->count());
        
        $logo = AlContentQuery::create()->retrieveContents(2, 2, 'logo')->findOne();
        $this->assertNotNull($logo);
        
        $navMenu = AlContentQuery::create()->retrieveContents(2, 2, 'nav_menu')->findOne();
        $this->assertNotNull($navMenu);
        
        $content = AlContentQuery::create()->retrieveContents(2, 1, 'content')->findOne();
        $this->assertNotNull($content);
        
        $middleSidebar = AlContentQuery::create()->retrieveContents(2, 1, 'middle_sidebar')->findOne();
        $this->assertNotNull($middleSidebar);
        
        $adsBox = AlContentQuery::create()->retrieveContents(1, 1, 'ads_box')->findOne();
        $this->assertNotNull($adsBox);
    }
    
    /**
     * @depends testChange
     */
    public function testGetOperations_1()
    {
        $container = $this->setupPageTree(2, 2)->getContainer();  
        $templateManager = new AlTemplateManager($container, null, null, null, null, '\AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Content\Template\Changer\Al2011ThemeBundleInternal1Slot');       
        $templateManager1 = new AlTemplateManager($container, null, null, null, null, '\AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Content\Template\Changer\Al2011ThemeBundleInternal2Slot');
        
        $templateChanger = new AlTemplateChanger($container, $templateManager1, $templateManager);
        $operations = $templateChanger->getOperations();  

        $this->assertEquals(array('add', 'change', 'remove'), array_keys($operations)); 
        
        $this->assertTrue(empty($operations['add']));
        
        $change = array_slice($operations['change'][0], 0);
        $this->assertTrue(in_array('content', $change['language']['page']));
        $this->assertTrue(in_array('middle_sidebar', $change['language']['page']));
        
        $change = array_slice($operations['change'][0], 0);
        $this->assertTrue(in_array('ads_box', $change['site']['page']));
        
        $change = array_slice($operations['change'][1], 0);
        $this->assertTrue(in_array('logo', $change['page']['site']));
        
        $change = array_slice($operations['change'][2], 0);
        $this->assertTrue(in_array('nav_menu', $change['page']['language']));
        
        $add = array_slice($operations['remove'], 0);
        $this->assertTrue(in_array('fake_slot_1', $add['page']));
        $this->assertTrue(in_array('fake_slot_2', $add['language']));
        $this->assertTrue(in_array('fake_slot_3', $add['site']));
        
        return $templateChanger;
    }
    /**
     * @depends testGetOperations_1
     */
    public function testChange_1(AlTemplateChanger $templateChanger)
    {
        $templateChanger->change();
        $removedContents = AlContentQuery::create()->retrieveContents(array(1, 2), array(1, 2), array('fake_slot_1', 'fake_slot_2', 'fake_slot_3'))->find(); 
        $this->assertEquals(0, $removedContents->count());
        
        $logo = AlContentQuery::create()->retrieveContents(1, 1, 'logo')->findOne();
        $this->assertNotNull($logo);
        
        $navMenu = AlContentQuery::create()->retrieveContents(2, 1, 'nav_menu')->findOne();
        $this->assertNotNull($navMenu);
        
        $content = AlContentQuery::create()->retrieveContents(2, 2, 'content')->findOne();
        $this->assertNotNull($content);
        
        $middleSidebar = AlContentQuery::create()->retrieveContents(2, 2, 'middle_sidebar')->findOne();
        $this->assertNotNull($middleSidebar);
        
        $adsBox = AlContentQuery::create()->retrieveContents(2, 2, 'ads_box')->findOne();
        $this->assertNotNull($adsBox);
    }
    
}