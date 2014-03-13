<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Controller;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Controller\DeployController;


/**
 * DeployControllerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class DeployControllerTest extends TestCase
{
    protected $controller;
    private $response;

    protected function setUp()
    {
        $this->response = $this->getMock('Symfony\Component\HttpFoundation\Response');
        $this->comandsProcessor = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\CommandsProcessor\AlCommandsProcessorInterface');
        $this->deployer = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\AlDeployerInterface');
        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');


        $this->controller = new DeployController();
        $this->controller->setContainer($this->container);
    }
    
    public function actionsProvider()
    {
        return array(
            array(
                'productionAction',
                'red_kite_cms.production_deployer',
                'red_kite_labs_theme_engine.deploy.templates_folder',
            ),
            array(
                'stageAction',
                'red_kite_cms.stage_deployer',
                'red_kite_labs_theme_engine.deploy.stage_templates_folder'
            )
        );
    }

    /**
     * @dataProvider actionsProvider
     */
    public function testAWarningMessageIsDisplayedWhenDeployThrowsAnException($action, $deployerServiceName, $templatesFolder)
    {
        $this->initContainer($deployerServiceName, $templatesFolder);
        
        $this->comandsProcessor->expects($this->never())
            ->method('executeCommands');
        
        $this->deployer->expects($this->any())
            ->method('deploy')
            ->will($this->throwException(new \Exception));
        
        $this->templating->expects($this->once())
            ->method('renderResponse')
            ->with('RedKiteCmsBundle:Dialog:dialog.html.twig')                
            ->will($this->returnValue($this->response));

        $this->assertEquals($this->response, $this->controller->$action());
    }

    /**
     * @dataProvider actionsProvider
     */
    public function testAWarningMessageIsDisplayedWhenRenderingTheResponseThrowsAnException($action, $deployerServiceName, $templatesFolder)
    {
        $at = $this->initContainer($deployerServiceName, $templatesFolder);
        
        $this->container->expects($this->at($at))
            ->method('get')
            ->with('templating')
            ->will($this->returnValue($this->templating));

        $this->comandsProcessor->expects($this->never())
            ->method('executeCommands');

        $this->templating->expects($this->at(0))
            ->method('renderResponse')
            ->with('RedKiteCmsBundle:Dialog:dialog.html.twig')
            ->will($this->throwException(new \Exception));

        $this->templating->expects($this->at(1))
            ->method('renderResponse')
            ->with('RedKiteCmsBundle:Dialog:dialog.html.twig')         
            ->will($this->returnValue($this->response));

        $this->deployer->expects($this->once())
            ->method('deploy')
            ->will($this->returnValue(true));

        $this->assertEquals($this->response, $this->controller->$action());
    }

    /**
     * @dataProvider actionsProvider
     */
    public function testSiteHasBeenDeployed($action, $deployerServiceName, $templatesFolder)
    {
        $at = $this->initContainer($deployerServiceName, $templatesFolder);

        $this->container->expects($this->at($at))
            ->method('getParameter')
            ->with('red_kite_cms.web_folder_full_path')
            ->will($this->returnValue('/path/to/app'));
        
        $at++;
        $this->container->expects($this->at($at))
            ->method('get')
            ->with('red_kite_cms.commands_processor')
            ->will($this->returnValue($this->comandsProcessor));

        $this->comandsProcessor->expects($this->once())
            ->method('executeCommands');

        $this->deployer->expects($this->once())
            ->method('deploy')
            ->will($this->returnValue(true));

        $this->templating->expects($this->once())
            ->method('renderResponse')
            ->will($this->returnValue($this->response));

        $this->assertEquals($this->response, $this->controller->$action());
    }

    private function initContainer($deployerServiceName, $templatesFolder)
    {
        $theme = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Theme\AlTheme')
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $activeTheme = $this->getMock("RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ActiveTheme\AlActiveThemeInterface");
        $activeTheme->expects($this->once())
            ->method('getActiveTheme')
            ->will($this->returnValue($theme));
        
        $at = 0;
        $this->container->expects($this->at($at))
            ->method('get')
            ->with('red_kite_cms.active_theme')
            ->will($this->returnValue($activeTheme));
        
        $at++;
        $this->container->expects($this->at($at))
            ->method('get')
            ->with($deployerServiceName)
            ->will($this->returnValue($this->deployer));
        
        $at++;
        $this->container->expects($this->at($at))
            ->method('getParameter')
            ->with($templatesFolder)
            ->will($this->returnValue('RedKite'));
        
        $pageTreeCollection = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\PageTreeCollection\AlPageTreeCollection')
                           ->disableOriginalConstructor()
                            ->getMock();
        $at++;
        $this->container->expects($this->at($at))
            ->method('get')
            ->with('red_kite_cms.page_tree_collection')
            ->will($this->returnValue($pageTreeCollection))
        ;
        
        $at++;
        $kernel = $this->getMock("Symfony\Component\HttpKernel\KernelInterface");
        $this->container->expects($this->at($at))
            ->method('get')
            ->with('kernel')
            ->will($this->returnValue($kernel))
        ;
        
        $at++;
        $this->container->expects($this->at($at))
            ->method('getParameter')
            ->with('red_kite_labs_theme_engine.deploy_bundle')
        ;
        
        $at++;
        $this->container->expects($this->at($at))
            ->method('getParameter')
            ->with('red_kite_labs_theme_engine.web_path')
            ->will($this->returnValue(""))
        ;
        
        $at++;
        $this->container->expects($this->at($at))
            ->method('getParameter')
            ->with('red_kite_cms.deploy_bundle.config_dir')
        ;
        
        $at++;
        $this->container->expects($this->at($at))
            ->method('getParameter')
            ->with('red_kite_cms.deploy_bundle.assets_base_dir')
        ;
        
        $at++;
        $this->container->expects($this->at($at))
            ->method('getParameter')
            ->with('red_kite_cms.upload_assets_full_path')
        ;
        
        $request = $this->getMock("Symfony\Component\HttpFoundation\Request");
        $at++;
        $this->container->expects($this->at($at))
            ->method('get')
            ->with('request')
            ->will($this->returnValue($request))
        ;
        
        $at++;
        $this->container->expects($this->at($at))
            ->method('getParameter')
            ->with('red_kite_cms.upload_assets_dir')
        ;
        
        $at++;
        $this->container->expects($this->at($at))
            ->method('getParameter')
            ->with('red_kite_cms.deploy_bundle.controller')
        ;
        
        $at++;
        $this->container->expects($this->at($at))
            ->method('getParameter')
            ->with('red_kite_cms.web_folder_full_path')
        ;
        
        $at++;
        $this->container->expects($this->at($at))
            ->method('getParameter')
            ->with('red_kite_cms.website_url')
        ;

        $at++;
        $this->container->expects($this->at($at))
            ->method('getParameter')
            ->with('red_kite_cms.love')
        ;
        
        $at++;
        $this->container->expects($this->at($at))
            ->method('get')
            ->with('templating')
            ->will($this->returnValue($this->templating));
        
        $at++;
        
        return $at;
    }
}