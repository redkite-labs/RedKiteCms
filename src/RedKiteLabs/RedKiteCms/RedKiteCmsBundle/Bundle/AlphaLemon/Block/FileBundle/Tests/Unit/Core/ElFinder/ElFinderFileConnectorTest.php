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

namespace AlphaLemon\Block\FileBundle\Tests\Unit\Core\ElFinder;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use AlphaLemon\Block\FileBundle\Core\ElFinder\ElFinderFileConnector;

class ElFinderFileConnectorTester extends ElFinderFileConnector
{
    public function getOptions()
    {
        return $this->options;
    }
}

/**
 * ElFinderFileConnectorTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ElFinderFileConnectorTest extends TestCase
{
    public function testOptions()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
/*
        $request->expects($this->once())
            ->method('getScheme')
            ->will($this->returnValue('http'));

        $request->expects($this->once())
            ->method('getHttpHost')
            ->will($this->returnValue('example.com'));
*/
        $bundle = $this->getMock('Symfony\Component\HttpKernel\Bundle\BundleInterface');
        $bundle->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('AlphaLemonCmsBundle'));

        $kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValue($request));

        $container->expects($this->at(0))
            ->method('getParameter')
            ->with('file.base_folder')
            ->will($this->returnValue('file')); 
        
        $container->expects($this->at(1))
            ->method('getParameter')
            ->with('alpha_lemon_cms.upload_assets_full_path')
            ->will($this->returnValue('/full/base/path/to/web/uploads/assets')); 
        
        $container->expects($this->at(4))
            ->method('getParameter')
            ->with('alpha_lemon_cms.upload_assets_dir')
            ->will($this->returnValue('uploads/assets'));

        $espected = array
        (
            "locale" => "",
            "roots" => array
                (
                    array
                        (
                            "driver" => "LocalFileSystem",
                            "path" => "/full/base/path/to/web/uploads/assets/file",
                            "URL" => "/uploads/assets/file",
                            "accessControl" => "access",
                            "rootAlias" => "Files"
                        )
                )
        );

        $connector = new ElFinderFileConnectorTester($container);
        $this->assertEquals($espected, $connector->getOptions());
    }
}
