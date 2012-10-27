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
use AlphaLemon\AlphaLemonCmsBundle\Controller\ThemesController;


/**
 * ThemesControllerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class ThemesControllerTest extends TestCase
{
    private $request;
    private $templateManager;
    private $theme;
    private $themes;
    private $templating;
    private $container;
    private $controller;

    protected function setUp()
    {
        $this->request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $this->request
             ->expects($this->once())
             ->method('get')
             ->will($this->returnValue('BusinessWebsiteThemeBundle'));
        
        $this->siteBootstrap = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\SiteBootstrap\AlSiteBootstrapInterface');
                
        $this->template = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate')
             ->disableOriginalConstructor()
             ->getMock()
        ;
        
        $this->templateManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->templateManager
             ->expects($this->once())
             ->method('setTemplate')
             ->with($this->template)
             ->will($this->returnSelf())
        ;
        
        $this->templateManager
             ->expects($this->once())
             ->method('refresh')
        ;
        
        $this->theme = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Theme\AlTheme')
             ->disableOriginalConstructor()
             ->getMock()
        ;
        
        $this->theme
             ->expects($this->once())
             ->method('getHomeTemplate')
             ->will($this->returnValue($this->template))
        ;
        
        $this->themes = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection');
        $this->themes
             ->expects($this->once())
             ->method('getTheme')
             ->will($this->returnValue($this->theme))
        ;
        
        $this->response = $this->getMock('Symfony\Component\HttpFoundation\Response');      
        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->templating
             ->expects($this->once())
             ->method('renderResponse')
             ->will($this->returnValue($this->response))
        ;
        
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        
        $this->controller = new ThemesController();
        $this->controller->setContainer($this->container);
    }

    /**
     * @dataProvider resultProvider
     */
    public function testStartNewTheme($result, $times)
    {
        $this->initContainer();
        $this->siteBootstrap
             ->expects($this->once())
             ->method('setTemplateManager')
             ->will($this->returnSelf());
        
         $this->siteBootstrap
             ->expects($this->once())
             ->method('bootstrap')
             ->will($this->returnValue($result));
         
         $this->siteBootstrap
             ->expects($this->exactly($times))
             ->method('getErrorMessage');
                        
        $response = $this->controller->startFromThemeAction();
        $this->assertEquals($this->response, $response);
    }
    
    public function resultProvider()
    {
        return array(
            array(false, 1),
            array(true, 0),
        );
    }
            
    
    private function initContainer()
    {
        $this->container
             ->expects($this->at(0))
             ->method('get')
             ->with('request')
             ->will($this->returnValue($this->request));

        $this->container
             ->expects($this->at(1))
             ->method('get')
             ->with('alpha_lemon_theme_engine.themes')
             ->will($this->returnValue($this->themes));

        $this->container
             ->expects($this->at(2))
             ->method('get')
             ->with('alpha_lemon_cms.template_manager')
             ->will($this->returnValue($this->templateManager));
        
        $this->container
             ->expects($this->at(3))
             ->method('get')
             ->with('alpha_lemon_cms.site_bootstrap')
             ->will($this->returnValue($this->siteBootstrap));
        
        $this->container
             ->expects($this->at(4))
             ->method('get')
             ->with('templating')
             ->will($this->returnValue($this->templating));
    }
}