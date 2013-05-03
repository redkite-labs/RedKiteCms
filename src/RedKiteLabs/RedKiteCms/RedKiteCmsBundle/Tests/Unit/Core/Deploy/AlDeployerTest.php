<?php
/**
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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Deploy;

use AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlDeployer;

class AlDeployerTester extends AlDeployer
{
    public function getRoutesPrefix()
    {   
    }
    
    public function getTemplatesFolder()
    {   
    }

    public function save(\AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree $pageTree, $type)
    {   
    }
}

/**
 * AlTwigDeployerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlDeployerTest extends \AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase
{
    protected $container;
    protected $dispatcher;
    protected $templateSlots;
    protected $containerAtSequenceAfterObjectCreation;
    
    protected function setUp()
    {
        parent::setUp();

        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $this->kernel->expects($this->any())
            ->method('locateResource')
            ->will($this->returnValue('deploy/bundle/path'));

        $this->kernel->expects($this->any())
            ->method('getRootDir')
            ->will($this->returnValue('home'));

        $this->templateSlots = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface');
        $this->blockManagerFactory = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $this->urlManager = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Core\UrlManager\AlUrlManagerInterface');
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->dispatcher = $this->getMock('\Symfony\Component\EventsDispatcher\EventDispatcherInterface', array('dispatch'));
        $this->viewRenderer = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\ViewRenderer\AlViewRendererInterface');
        
        $this->initContainer();
    }
    
    public function testTemplateSlotsInjectedBySetters()
    {        
        $pageTreeCollection = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlPageTreeCollection')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $deployer = new AlDeployerTester($this->container);
        
        $this->assertEquals($deployer, $deployer->setPageTreeCollection($pageTreeCollection));
        $this->assertEquals($pageTreeCollection, $deployer->getPageTreeCollection());
    }
    
    public function testTemplateSlotsInjectedBySetters1()
    {
        $deployer = new AlDeployerTester($this->container);
        $this->assertEquals('deploy/bundle/path', $deployer->getDeployBundleRealPath());
    }
    
    protected function initContainer()
    {
        $this->container->expects($this->at(0))
            ->method('get')
            ->with('kernel')
            ->will($this->returnValue($this->kernel));

        $this->container->expects($this->at(2))
            ->method('getParameter')
            ->with('alpha_lemon_theme_engine.deploy_bundle')
            ->will($this->returnValue('AcmeWebSiteBundle'));

        $this->container->expects($this->at(3))
            ->method('getParameter')
            ->with('alpha_lemon_cms.deploy_bundle.config_dir')
            ->will($this->returnValue('Resources/config'));

        $this->container->expects($this->at(4))
            ->method('getParameter')
            ->with('alpha_lemon_cms.deploy_bundle.assets_base_dir')
            ->will($this->returnValue('Resources/public/'));

        $this->container->expects($this->at(5))
            ->method('getParameter')
            ->with('alpha_lemon_cms.upload_assets_full_path')
            ->will($this->returnValue(''));

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->container->expects($this->at(6))
            ->method('get')
            ->with('request')
            ->will($this->returnValue($request));
        
        $this->container->expects($this->at(7))
            ->method('getParameter')
            ->with('alpha_lemon_cms.upload_assets_dir')
            ->will($this->returnValue('uploads/assets'));
        
        $this->container->expects($this->at(8))
            ->method('getParameter')
            ->with('alpha_lemon_cms.web_folder')
            ->will($this->returnValue('web'));
        
        $this->container->expects($this->at(9))
            ->method('getParameter')
            ->with('alpha_lemon_cms.deploy_bundle.controller')
            ->will($this->returnValue('WebSite'));
    }
}