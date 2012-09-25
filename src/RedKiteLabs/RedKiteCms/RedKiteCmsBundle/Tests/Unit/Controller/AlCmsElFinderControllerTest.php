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
use AlphaLemon\AlphaLemonCmsBundle\Controller\AlCmsElFinderController;

/**
 * ElFinderControllerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlCmsElFinderControllerTest extends TestCase
{
    public function testConnectMediaAction()
    {
        $container = $this->initContainer(
            'el_finder_media_connector',
            'AlphaLemon\AlphaLemonCmsBundle\Core\ElFinder\ElFinderMediaConnector'
        );

        $controller = new AlCmsElFinderController();
        $controller->setContainer($container);
        $controller->connectMediaAction();
    }

    public function testConnectStylesheetsAction()
    {
        $container = $this->initContainer(
            'el_finder_css_connector',
            'AlphaLemon\AlphaLemonCmsBundle\Core\ElFinder\ElFinderStylesheetsConnector'
        );

        $controller = new AlCmsElFinderController();
        $controller->setContainer($container);
        $controller->connectStylesheetsAction();
    }

    public function testConnectJavascriptsAction()
    {
        $container = $this->initContainer(
            'el_finder_js_connector',
            'AlphaLemon\AlphaLemonCmsBundle\Core\ElFinder\ElFinderJavascriptsConnector'
        );

        $controller = new AlCmsElFinderController();
        $controller->setContainer($container);
        $controller->connectJavascriptsAction();
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