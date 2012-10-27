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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\ElFinder;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\ElFinder\ElFinderMediaConnector;

class ElFinderMediaConnectorExt extends ElFinderMediaConnector
{
    public function getOptions()
    {
        return $this->options;
    }
}

/**
 * ElFinderMediaConnectorTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class ElFinderMediaConnectorTest extends TestCase
{
    public function testOptions()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $request->expects($this->once())
            ->method('getScheme')
            ->will($this->returnValue('http'));

        $request->expects($this->once())
            ->method('getHttpHost')
            ->will($this->returnValue('example.com'));

        $bundle = $this->getMock('Symfony\Component\HttpKernel\Bundle\BundleInterface');
        $bundle->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('AlphaLemonCmsBundle'));

        $kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->once())
            ->method('get')
            ->will($this->returnValue($request));

        $container->expects($this->exactly(3))
            ->method('getParameter')
            ->will($this->onConsecutiveCalls('media', 'uploads/assets', '/full/base/path/to/web/uploads/assets'));

        $espected = array
        (
            "locale" => "",
            "roots" => array
                (
                    array
                        (
                            "driver" => "LocalFileSystem",
                            "path" => "/full/base/path/to/web/uploads/assets/media",
                            "URL" => "http://example.com/uploads/assets/media/",
                            "accessControl" => "access",
                            "rootAlias" => "Media"
                        )

                )

        );

        $connector = new ElFinderMediaConnectorExt($container);
        $this->assertEquals($espected, $connector->getOptions());
    }
}
