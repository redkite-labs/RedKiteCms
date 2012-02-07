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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Content\Slot\Repeated;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\AlRepeatedSlotsManager;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlLanguageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlContentQuery; 
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageQuery;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;

class AlRepeatedSlotManagerTest extends TestCase 
{    
    private $activeThemeSlots;
    
    protected function setUp()
    {
        parent::setUp();
        
        AlphaLemonDataPopulator::depopulate();
        
        $alLanguageManager = new AlLanguageManager(
            $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface')
        );
        $params = array('language' => 'en');
        $alLanguageManager->save($params);
        
        $container = $this->setupPageTree(AlLanguageQuery::create()->mainLanguage()->findOne()->getId())->getContainer();
        $alPageManager = new AlPageManager(
            $container
        );
        
        $params = array('pageName'      => 'fake page 1', 
                        'template'      => 'home',
                        'permalink'     => 'this is a website fake page',
                        'title'         => 'page title',
                        'description'   => 'page description',
                        'keywords'      => '');
        $alPageManager->save($params);
        $alPageManager->set(null);
        
        $params['pageName'] = 'fake page 2';
        $alPageManager->save($params);
        $alPageManager->set(null);
        
        $params['pageName'] = 'fake page 3';
        $alPageManager->save($params);
        $alPageManager->set(null);
        
        $params['pageName'] = 'fake page 4';
        $alPageManager->save($params);
        
        $alLanguageManager = new AlLanguageManager($container);        
        $params = array('language' => 'it');
        $alLanguageManager->set(null);
        $alLanguageManager->save($params);
        
        $this->activeThemeSlots = __DIR__ . '/active_theme_slots.xml';
    }
    
    protected function tearDown()
    {
        unlink($this->activeThemeSlots);
    }
    
    public function testCompareSlots()
    {
        $container = $this->setupPageTree(AlLanguageQuery::create()->mainLanguage()->findOne()->getId(), AlPageQuery::create()->homePage()->findOne()->getId())->getContainer(); 
        $testAlRepeatedSlotsManager = new AlRepeatedSlotsManager(
            $container, 'AlphaLemonThemeBundle'
        );
        
        $testAlRepeatedSlotsManager->setActiveThemeSlotsFile($this->activeThemeSlots); 
        
        $this->assertFalse(file_exists($this->activeThemeSlots), 'The activeThemeSlots file already exists');
        $slots = $container->get('al_page_tree')->getSlots();
        $this->assertTrue($testAlRepeatedSlotsManager->compareSlots('Home', $slots));
        $this->assertTrue(file_exists($this->activeThemeSlots), 'The activeThemeSlots file has not been created');
        
        $this->assertEquals(1, AlContentQuery::create()->retrieveContentsBySlotName('stats_box')->count());
        $slots['stats_box'] = new AlSlot('stats_box', array('repeated' => 'page'));
        $this->assertTrue($testAlRepeatedSlotsManager->compareSlots('Home', $slots));
        $this->assertEquals(8, AlContentQuery::create()->retrieveContentsBySlotName('stats_box')->count());
        
        $this->assertEquals(2, AlContentQuery::create()->retrieveContentsBySlotName('nav_menu')->count());
        $slots['nav_menu'] = new AlSlot('nav_menu', array('repeated' => 'site'));
        $this->assertTrue($testAlRepeatedSlotsManager->compareSlots('Home', $slots));
        $this->assertEquals(1, AlContentQuery::create()->retrieveContentsBySlotName('nav_menu')->count());
        
        $this->assertEquals(8, AlContentQuery::create()->retrieveContentsBySlotName('header')->count());
        $slots['header'] = new AlSlot('header', array('repeated' => 'language'));
        $this->assertTrue($testAlRepeatedSlotsManager->compareSlots('Home', $slots));
        $this->assertEquals(2, AlContentQuery::create()->retrieveContentsBySlotName('header')->count());
        
        $slots['ads_box'] = new AlSlot('ads_box', array('repeated' => 'site'));
        $slots['content'] = new AlSlot('content', array('repeated' => 'language'));
        $slots['sponsor_box'] = new AlSlot('sponsor_box', array('repeated' => 'page'));
        $this->assertTrue($testAlRepeatedSlotsManager->compareSlots('Home', $slots));
        
        $this->assertEquals(1, AlContentQuery::create()->retrieveContentsBySlotName('ads_box')->count());
        $this->assertEquals(2, AlContentQuery::create()->retrieveContentsBySlotName('content')->count());
        $this->assertEquals(8, AlContentQuery::create()->retrieveContentsBySlotName('sponsor_box')->count());
    }
}