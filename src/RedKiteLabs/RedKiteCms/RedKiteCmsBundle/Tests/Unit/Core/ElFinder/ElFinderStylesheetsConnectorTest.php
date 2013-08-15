<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\ElFinder;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\ElFinder\ElFinderStylesheetsConnector;

class ElFinderStylesheetsConnectorExt extends ElFinderStylesheetsConnector
{
    public function getOptions()
    {
        return $this->options;
    }
}

/**
 * ElFinderStylesheetsConnectorTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ElFinderStylesheetsConnectorTest extends TestCase
{
    public function testOptions()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
/* TODO Removable?
        $request->expects($this->once())
            ->method('getScheme')
            ->will($this->returnValue('http'));

        $request->expects($this->once())
            ->method('getHttpHost')
            ->will($this->returnValue('example.com'));*/

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
            ->with('red_kite_cms.deploy_bundle.css_dir')
            ->will($this->returnValue('css')); 
       
        $container->expects($this->at(1))
            ->method('getParameter')
            ->with('red_kite_cms.upload_assets_full_path')
            ->will($this->returnValue('/full/base/path/to/web/uploads/assets')); 
        
        $container->expects($this->at(4))
            ->method('getParameter')
            ->with('red_kite_cms.upload_assets_dir')
            ->will($this->returnValue('uploads/assets'));

        $espected = array
        (
            "locale" => "",
            "roots" => array
                (
                    array
                        (
                            "driver" => "LocalFileSystem",
                            "path" => "/full/base/path/to/web/uploads/assets/css",
                            // TODO "URL" => "http://example.com/uploads/assets/css",
                            "URL" => "/uploads/assets/css",
                            "accessControl" => "access",
                            "rootAlias" => "Stylesheets"
                        )

                )

        );

        $connector = new ElFinderStylesheetsConnectorExt($container);
        $this->assertEquals($espected, $connector->getOptions());
    }
}
