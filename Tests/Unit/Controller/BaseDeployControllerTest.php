<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Controller;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Controller\DeployController;


/**
 * DeployControllerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class BaseDeployControllerTest extends TestCase
{
    protected $controller;
    private $response;
    
    abstract protected function executeAction();

    protected function setUp()
    {
        $this->response = $this->getMock('Symfony\Component\HttpFoundation\Response');
        $this->comandsProcessor = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\CommandsProcessor\AlCommandsProcessorInterface');
        $this->deployer = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Deploy\AlDeployerInterface');
        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');


        $this->controller = new DeployController();
        $this->controller->setContainer($this->container);
    }

    public function testAWarningMessageIsDisplayedWhenDeployThrowsAnException()
    {
        $this->initContainer();

        $this->container->expects($this->never())
            ->method('getParameter');

        $this->comandsProcessor->expects($this->never())
            ->method('executeCommands');

        $this->deployer->expects($this->any())
            ->method('deploy')
            ->will($this->throwException(new \Exception));
        
        $this->templating->expects($this->once())
            ->method('renderResponse')
            ->will($this->returnValue($this->response));

        $this->assertEquals($this->response, $this->executeAction());
    }

    public function testAWarningMessageIsDisplayedWhenRenderingTheResponseThrowsAnException()
    {
        $this->initContainer();
        
        $this->container->expects($this->at(2))
            ->method('get')
            ->with('templating')
            ->will($this->returnValue($this->templating));

        $this->container->expects($this->never())
            ->method('getParameter');

        $this->comandsProcessor->expects($this->never())
            ->method('executeCommands');

        $this->templating->expects($this->at(0))
            ->method('renderResponse')
            ->will($this->throwException(new \Exception));

        $this->templating->expects($this->at(1))
            ->method('renderResponse')
            ->will($this->returnValue($this->response));

        $this->deployer->expects($this->once())
            ->method('deploy')
            ->will($this->returnValue(true));

        $this->assertEquals($this->response, $this->executeAction());
    }

    public function testSiteHasBeenDeployed()
    {
        $this->initContainer();

        $this->container->expects($this->at(2))
            ->method('getParameter')
            ->will($this->returnValue('/path/to/app'));
        
        $this->container->expects($this->at(3))
            ->method('get')
            ->with('alpha_lemon_cms.commands_processor')
            ->will($this->returnValue($this->comandsProcessor));

        $this->comandsProcessor->expects($this->once())
            ->method('executeCommands');

        $this->deployer->expects($this->once())
            ->method('deploy')
            ->will($this->returnValue(true));

        $this->templating->expects($this->once())
            ->method('renderResponse')
            ->will($this->returnValue($this->response));

        $this->assertEquals($this->response, $this->executeAction());
    }

    private function initContainer()
    {
        $this->container->expects($this->at(0))
            ->method('get')
            ->with($this->deployerServiceName)
            ->will($this->returnValue($this->deployer));

        $this->container->expects($this->at(1))
            ->method('get')
            ->with('templating')
            ->will($this->returnValue($this->templating));
    }
}
