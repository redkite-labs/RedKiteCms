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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Controller;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Controller\ThemePreviewController;


/**
 * ThemePreviewControllerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class ThemePreviewControllerTest extends TestCase
{
    private $request;
    private $pageManager;
    private $factoryRepository;
    private $activeTheme;
    private $blockRepository;
    private $pageRepository;
    private $blocksFactory;
    private $templating;

    protected function setUp()
    {
        $this->request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $this->response = $this->getMock('Symfony\Component\HttpFoundation\Response');
        
        $this->pageManager = 
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->pageRepository =
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->blockRepository =
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->factoryRepository = 
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepository')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->blocksFactory = 
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactory')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->activeTheme = 
            $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Theme\AlActiveTheme')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;

        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->templating
             ->expects($this->once())
             ->method('renderResponse')
             ->will($this->returnValue($this->response));
        
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        
        $this->controller = new ThemePreviewController();
        $this->controller->setContainer($this->container);
    }

    public function testThemeIsNotSavedWhenAnyTemplateHasBeenIncudedInTheMapping()
    {
        $this->initContainer(false, false);
        $this->configureDbAndActiveTheme(0, 0, 0, 0);        
        $this->configureFactoryRepository(false);
        
        $data = 'theme=BusinessWebsiteThemeBundle';
        $this->initRequest($data);
                
        $response = $this->controller->saveActiveThemeAction();
        $this->assertEquals($this->response, $response);
    }
    
    public function testThemeIsActivateButAnythigIsSavedWhenTheTemplateHasAnySlot()
    {
        $this->initContainer(false);
        $this->configureDbAndActiveTheme(1, 1, 0, 1);     
        $this->configureFactoryRepository(false);
        
        $data = 'theme=BusinessWebsiteThemeBundle&templates[0][new_template]=home&templates[0][old_template]=home';
        $this->initRequest($data);
        
        $this->pageRepository
             ->expects($this->never())
             ->method('fromTemplateName')
        ;
                
        $response = $this->controller->saveActiveThemeAction();
        $this->assertEquals($this->response, $response);
    }
    
    public function testThemeIsActivateButAnythigIsSavedWhenAnyPageHasBeenFetched()
    {
        $this->initContainer(false);
        $this->configureDbAndActiveTheme(1, 1, 0, 1);     
        $this->configureFactoryRepository(false);
        
        $data = 'theme=BusinessWebsiteThemeBundle&templates[0][new_template]=home&templates[0][old_template]=home&templates[0][slots][0][slot_placeholder]=al_slot_home_left_sidebar';
        $this->initRequest($data);
        
        $this->pageRepository
             ->expects($this->once())
             ->method('fromTemplateName')
             ->will($this->returnValue(array()))
        ;
        
        $response = $this->controller->saveActiveThemeAction();
        $this->assertEquals($this->response, $response);
    }
    
    public function testAnyChangeIsMadeOnTheDbWhenSlotsHaveTheSameName()
    {
        $this->initContainer();
        $this->configureDbAndActiveTheme(1, 1, 0, 1);    
        $this->configureFactoryRepository();
        
        $data = 'theme=BusinessWebsiteThemeBundle&templates[0][new_template]=home&templates[0][old_template]=home&templates[0][slots][0][slot_placeholder]=al_slot_home_logo&templates[0][slots][0][slot]=al_map_logo';
        $this->initRequest($data);
        
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $this->pageRepository
             ->expects($this->once())
             ->method('fromTemplateName')
             ->will($this->returnValue(array($page)))
        ;
        
        $this->blockRepository
             ->expects($this->never())
             ->method('retrieveContents')
        ;
        
        $this->pageManager
             ->expects($this->never())
             ->method('set')
        ;
        
        $response = $this->controller->saveActiveThemeAction();
        $this->assertEquals($this->response, $response);
    }
    
    public function testTheSlotHasNotBeenChangedForABlock()
    {
        $this->initContainer(true, false);
        $this->configureDbAndActiveTheme(1, 0, 1, 0);    
        $this->configureFactoryRepository();
        
        $data = 'theme=BusinessWebsiteThemeBundle&templates[0][new_template]=home&templates[0][old_template]=home&templates[0][slots][0][slot_placeholder]=al_slot_home_left_sidebar&templates[0][slots][0][slot]=al_map_top_section_1';
        $this->initRequest($data);
        
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $this->pageRepository
             ->expects($this->once())
             ->method('fromTemplateName')
             ->will($this->returnValue(array($page)))
        ;
        
        $block = $this->initBlock();
        $this->blockRepository
             ->expects($this->once())
             ->method('retrieveContents')
             ->will($this->returnValue(array($block)))
        ;
        
        $this->initBlocksFactory($block, false);
        
        $this->pageManager
             ->expects($this->never())
             ->method('set')
        ;
        
        $response = $this->controller->saveActiveThemeAction();
        $this->assertEquals($this->response, $response);
    }
    
    public function testTheSlotHasBeenChangedForABlock()
    {
        $this->initContainer();
        $this->configureDbAndActiveTheme(1, 1, 0, 1);    
        $this->configureFactoryRepository();
        
        $data = 'theme=BusinessWebsiteThemeBundle&templates[0][new_template]=home&templates[0][old_template]=home&templates[0][slots][0][slot_placeholder]=al_slot_home_left_sidebar&templates[0][slots][0][slot]=al_map_top_section_1';
        $this->initRequest($data);
        
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $this->pageRepository
             ->expects($this->once())
             ->method('fromTemplateName')
             ->will($this->returnValue(array($page)))
        ;
        
        $block = $this->initBlock();
        $this->blockRepository
             ->expects($this->once())
             ->method('retrieveContents')
             ->will($this->returnValue(array($block)))
        ;
        
        $this->initBlocksFactory($block, true);
        
        $this->pageManager
             ->expects($this->never())
             ->method('set')
        ;
        
        $response = $this->controller->saveActiveThemeAction();
        $this->assertEquals($this->response, $response);
    }
    
    public function testTheSlotHasNotBeenChangedForARepeatedSlot()
    {
        $this->initContainer(true, false);
        $this->configureDbAndActiveTheme(1, 0, 1, 0);    
        $this->configureFactoryRepository();
        
        $data = 'theme=BusinessWebsiteThemeBundle&templates[0][new_template]=home&templates[0][old_template]=repeated_slots&templates[0][slots][0][slot_placeholder]=al_slot_repeated_slots_logo&templates[0][slots][0][slot]=al_map_top_section_1';
        $this->initRequest($data);
        
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $this->pageRepository
             ->expects($this->never())
             ->method('fromTemplateName')
        ;
        
        $block = $this->initBlock();
        $this->blockRepository
             ->expects($this->once())
             ->method('retrieveContents')
             ->will($this->returnValue(array($block)))
        ;
        
        $this->initBlocksFactory($block, false);
        
        $this->pageManager
             ->expects($this->never())
             ->method('set')
        ;
        
        $response = $this->controller->saveActiveThemeAction();
        $this->assertEquals($this->response, $response);
    }
    
    public function testTheSlotHasBeenChangedForARepeatedSlot()
    {
        $this->initContainer();
        $this->configureDbAndActiveTheme(1, 1, 0, 1);    
        $this->configureFactoryRepository();
        
        $data = 'theme=BusinessWebsiteThemeBundle&templates[0][new_template]=home&templates[0][old_template]=repeated_slots&templates[0][slots][0][slot_placeholder]=al_slot_repeated_slots_logo&templates[0][slots][0][slot]=al_map_top_section_1';
        $this->initRequest($data);
        
        $this->pageRepository
             ->expects($this->never())
             ->method('fromTemplateName')
        ;
        
        $block = $this->initBlock();
        $this->blockRepository
             ->expects($this->once())
             ->method('retrieveContents')
             ->will($this->returnValue(array($block)))
        ;
        
        $this->initBlocksFactory($block, true);
        
        $this->pageManager
             ->expects($this->never())
             ->method('set')
        ;
        
        $response = $this->controller->saveActiveThemeAction();
        $this->assertEquals($this->response, $response);
    }
    
    
    public function testPageHasNotBeenSaved()
    {
        $this->initContainer(false, false);
        $this->configureDbAndActiveTheme(1, 0, 1, 0);    
        $this->configureFactoryRepository(false);
        
        $data = 'theme=BusinessWebsiteThemeBundle&templates[0][new_template]=home&templates[0][old_template]=homepage&templates[0][slots][0][slot_placeholder]=al_slot_home_left_sidebar&templates[0][slots][0][slot]=al_map_top_section_1';
        $this->initRequest($data);
        
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $this->pageRepository
             ->expects($this->once())
             ->method('fromTemplateName')
             ->will($this->returnValue(array($page)))
        ;
        
        $this->pageManager
             ->expects($this->once())
             ->method('set')
             ->with($page)
        ;
        
        $this->pageManager
             ->expects($this->once())
             ->method('save')
             ->will($this->returnValue(false))
        ;
        
        $this->blockRepository
             ->expects($this->never())
             ->method('retrieveContents')
        ;
        
        $response = $this->controller->saveActiveThemeAction();
        $this->assertEquals($this->response, $response);
    }
    
    public function testPageHasBeenSaved()
    {
        $this->initContainer();
        $this->configureDbAndActiveTheme(1, 1, 0, 1);    
        $this->configureFactoryRepository();
        
        $data = 'theme=BusinessWebsiteThemeBundle&templates[0][new_template]=home&templates[0][old_template]=homepage&templates[0][slots][0][slot_placeholder]=al_slot_home_left_sidebar&templates[0][slots][0][slot]=al_map_top_section_1';
        $this->initRequest($data);
        
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $this->pageRepository
             ->expects($this->once())
             ->method('fromTemplateName')
             ->will($this->returnValue(array($page)))
        ;
        
        $this->pageManager
             ->expects($this->once())
             ->method('set')
             ->with($page)
        ;
        
        $this->pageManager
             ->expects($this->once())
             ->method('save')
             ->will($this->returnValue(true))
        ;
        
        $block = $this->initBlock();
        $this->blockRepository
             ->expects($this->once())
             ->method('retrieveContents')
             ->will($this->returnValue(array($block)))
        ;
        
        $this->initBlocksFactory($block, true);
        
        $response = $this->controller->saveActiveThemeAction();
        $this->assertEquals($this->response, $response);
    }
    
    private function initBlock()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        
        return $block;
    }
    
    private function initBlocksFactory($block, $saveResult)
    {
        $this->blockManager = 
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->blockManager
             ->expects($this->once())
             ->method('save')
             ->will($this->returnValue($saveResult))
        ;
        
        $this->blocksFactory
             ->expects($this->once())
             ->method('createBlockManager')
             ->with($block)
             ->will($this->returnValue($this->blockManager))
        ;
    }
    
    private function initContainer($hasBlockManager = true, $hasActiveTheme = true)
    {
        $this->container
             ->expects($this->at(0))
             ->method('get')
             ->with('request')
             ->will($this->returnValue($this->request));

        $this->container
             ->expects($this->at(1))
             ->method('get')
             ->with('alpha_lemon_cms.page_manager')
             ->will($this->returnValue($this->pageManager));

        $this->container
             ->expects($this->at(2))
             ->method('get')
             ->with('alpha_lemon_cms.factory_repository')
             ->will($this->returnValue($this->factoryRepository));
        
        $at = 3;
        if ($hasBlockManager) {
            $this->container
                 ->expects($this->at($at))
                 ->method('get')
                 ->with('alpha_lemon_cms.block_manager_factory')
                 ->will($this->returnValue($this->blocksFactory));
            $at++;
        }
        
        if ($hasActiveTheme) {
            $this->container
                 ->expects($this->at($at))
                 ->method('get')
                 ->with('alpha_lemon_theme_engine.active_theme')
                 ->will($this->returnValue($this->activeTheme));
            $at++;
        }
        
        $this->container
             ->expects($this->at($at))
             ->method('get')
             ->with('templating')
             ->will($this->returnValue($this->templating));
    }
    
    private function initRequest($data)
    {
        $this->request
             ->expects($this->once())
             ->method('get')
             ->with('data')
             ->will($this->returnValue($data));
    }
    
    private function configureDbAndActiveTheme($startTransaction = 1, $commit = 1, $rollback = 1, $activeTheme = 1)
    {
        $this->pageRepository
             ->expects($this->exactly($startTransaction))
             ->method('startTransaction')
        ;
        
        $this->pageRepository
             ->expects($this->exactly($commit))
             ->method('commit')
        ;
        
        $this->pageRepository
             ->expects($this->exactly($rollback))
             ->method('rollback')
        ;
        
        $this->activeTheme
             ->expects($this->exactly($activeTheme))
             ->method('writeActiveTheme')
        ;
    }
    
    private function configureFactoryRepository($createsBlocksRepository = true)
    {
        $this->factoryRepository
             ->expects($this->at(0))
             ->method('createRepository')
             ->with('Page')
             ->will($this->returnValue($this->pageRepository))
        ;
        
        if ($createsBlocksRepository) {
            $this->factoryRepository
                 ->expects($this->at(1))
                 ->method('createRepository')
                 ->with('Block')
                 ->will($this->returnValue($this->blockRepository))
            ;
        }
    }
}