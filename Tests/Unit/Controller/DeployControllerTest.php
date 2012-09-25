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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Controller;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Controller\DeployController;


/**
 * DeployControllerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class DeployControllerTest extends TestCase
{
    private $response;

    protected function setUp()
    {
        $this->response = $this->getMock('Symfony\Component\HttpFoundation\Response');
        $this->comandsProcessor = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\CommandsProcessor\AlCommandsProcessorInterface');
        $this->deployer = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlDeployerInterface');
        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');


        $this->controller = new DeployController();
        $this->controller->setContainer($this->container);
    }

    public function testAWarningMessageIsDisplayedWhenDeployThrowsAnException()
    {
        $this->container->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls($this->templating, $this->deployer));

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

        $this->assertEquals($this->response, $this->controller->localAction());
    }

    public function testAWarningMessageIsDisplayedWhenRenderingTheResponseThrowsAnException()
    {
        $this->container->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls($this->templating, $this->deployer));

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

        $this->assertEquals($this->response, $this->controller->localAction());
    }

    public function testSiteHasBeenDeployed()
    {
        $this->container->expects($this->exactly(3))
            ->method('get')
            ->will($this->onConsecutiveCalls($this->templating, $this->deployer, $this->comandsProcessor));

        $this->container->expects($this->once())
            ->method('getParameter')
            ->will($this->returnValue('/path/to/app'));

        $this->comandsProcessor->expects($this->once())
            ->method('executeCommands');

        $this->deployer->expects($this->once())
            ->method('deploy')
            ->will($this->returnValue(true));

        $this->templating->expects($this->once())
            ->method('renderResponse')
            ->will($this->returnValue($this->response));

        $this->assertEquals($this->response, $this->controller->localAction());
    }
}
