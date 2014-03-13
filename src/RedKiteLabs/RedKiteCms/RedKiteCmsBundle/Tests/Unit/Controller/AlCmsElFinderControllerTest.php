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
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Controller\AlCmsElFinderController;

/**
 * ElFinderControllerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlCmsElFinderControllerTest extends TestCase
{
    public function testConnectMediaAction()
    {
        $container = $this->initContainer(
            'el_finder_media_connector',
            'RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ElFinder\ElFinderMediaConnector'
        );

        $controller = new AlCmsElFinderController();
        $controller->setContainer($container);
        $controller->connectMediaAction();
    }
    
    public function testConnectMediaAction1()
    {
        $templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');        
        $templating->expects($this->once())
            ->method('renderResponse')
            ->with('RedKiteCmsBundle:Elfinder:file_manager.html.twig')
        ;
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->at(0))
             ->method('get')
             ->with('templating')
             ->will($this->returnValue($templating));

        $controller = new AlCmsElFinderController();
        $controller->setContainer($container);
        $controller->showFilesManagerAction();
    }

    protected function initContainer($connectorName, $connectorClass)
    {
        $connector = $this->getMockBuilder($connectorClass)
                          ->disableOriginalConstructor()
                          ->getMock();
        $connector->expects($this->once())
             ->method('connect');

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->once())
             ->method('get')
             ->with($connectorName)
             ->will($this->returnValue($connector));

        return $container;
    }
}