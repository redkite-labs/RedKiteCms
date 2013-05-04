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

namespace AlphaLemon\Block\FileBundle\Tests\Unit\Controller;

use AlphaLemon\Block\FileBundle\Controller\ElFinderFileController;
use AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Controller\AlCmsElFinderControllerTest;

/**
 * ElFinderControllerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class ElFinderControllerTest extends AlCmsElFinderControllerTest
{
    public function testConnectFileAction()
    {
        $container = $this->initContainer(
            'el_finder.file_connector',
            'AlphaLemon\Block\FileBundle\Core\ElFinder\ElFinderFileConnector'
        );

        $controller = new ElFinderFileController();
        $controller->setContainer($container);
        $controller->connectFileAction();
    }
    
    public function testShowMediaLibrary()
    {
        $templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $templating->expects($this->once())
             ->method('renderResponse')
             ->with('AlphaLemonCmsBundle:Elfinder:media_library.html.twig')
        ;
        
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->at(0))
             ->method('get')
             ->with('templating')
             ->will($this->returnValue($templating))
        ;
        
        $container->expects($this->at(1))
             ->method('getParameter')
             ->with('alpha_lemon_cms.enable_yui_compressor')
             ->will($this->returnValue(true))
        ;
        
        $controller = new ElFinderFileController();
        $controller->setContainer($container);
        $controller->showMediaLibraryAction();
    }
}