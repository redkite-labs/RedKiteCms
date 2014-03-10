<?php
/*
 * This file is part of the RedKiteLabsBootstrapBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://redkite-labs.com
 *
 * @license    MIT License
 */

namespace RedKiteLabs\RedKiteCms\BootstrapBundle\Tests\Unit\Json;

use org\bovigo\vfs\vfsStream;
use RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Loader\RoutingLoader;

/**
 * RoutingLoaderTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class RoutingLoaderTest extends \RedKiteLabs\RedKiteCms\BootstrapBundle\Tests\Unit\Base\BaseFilesystem
{
    private $locator;
    private $root;
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->root = vfsStream::setup('root', null, array('vendor/composer' => array(), 'routing' => array()));
        
        $this->locator = $this->getMock('Symfony\Component\Config\FileLocatorInterface');
        $this->jsonCollection = $this
            ->getMockBuilder('RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Json\JsonAutoloaderCollection')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
    
    public function testSupports()
    {
        $loader = new RoutingLoader($this->locator, $this->jsonCollection, vfsStream::url('routing'));
        $this->assertFalse($loader->supports(null, 'foo'));
        $this->assertTrue($loader->supports(null, 'bootstrap'));
    }
    
    /**
     * @dataProvider routesProvider
     */
    public function testLoadRoutes($bundles, $expectedRoutes)
    {
        
        
        $c = 0;
        foreach($bundles as $bundle) {
            if (array_key_exists('routing', $bundle)) {
                $routingFile = vfsStream::url($bundle['routing']['file']);
                file_put_contents($routingFile, $bundle['routing']['content']);
                
                $this->locator->expects($this->at($c))
                     ->method('locate')
                     ->will($this->returnValue($routingFile));
                $c++;
            }
        }
        
        $this->jsonCollection->expects($this->at(0))
             ->method('rewind');
        
        $c = 1;
        foreach($bundles as $bundle) {
            $autoloader = $this
                ->getMockBuilder('RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Json\JsonAutoloader')
                ->disableOriginalConstructor()
                ->getMock()
            ;
            
            $autoloader
                ->expects($this->once())
                ->method('getBundleName')
                ->will($this->returnValue($bundle['name']))
            ;
            
            if (array_key_exists('priority', $bundle)) {
                $autoloader
                    ->expects($this->once())
                    ->method('getRouting')
                    ->will($this->returnValue(array('priority' => $bundle['priority'])))
                ;
            }
            
            $this->jsonCollection->expects($this->at($c))
                 ->method('valid')
                 ->will($this->returnValue(true))
            ;
            
            $c++;
            $this->jsonCollection->expects($this->at($c))
                 ->method('current')
                 ->will($this->returnValue($autoloader));
            
            $c++;
            $this->jsonCollection->expects($this->at($c))
                 ->method('next');
            
            $c++; 
        }
        
        $loader = new RoutingLoader($this->locator, $this->jsonCollection, vfsStream::url('routing'));
        $routing = $loader->load(null);
        $routes =  $routing->all();
        $this->assertEquals($expectedRoutes, array_keys($routes));
    }
    
    public function routesProvider()
    {
        return array(
            array(
                array(
                    array(
                        'name' => 'FooBundle',
                        'routing' => array(
                            'file' => 'root\routing\foobundle.yml',
                            'content' => "foo:\n  path: /blog",
                        ),
                    ),
                ),
                array(
                    'foo',
                ),
            ),
            array(
                array(
                    array(
                        'name' => 'FooBundle',
                        'routing' => array(
                            'file' => 'root\routing\foobundle.yml',
                            'content' => "foo:\n  path: /blog",
                        ),
                    ),
                    array(
                        'name' => 'BarBundle',
                        'routing' => array(
                            'file' => 'root\routing\barbundle.yml',
                            'content' => "bar:\n  path: /blog",
                        ),
                    ),
                ),
                array(
                    'foo',
                    'bar',
                ),
            ),
            array(
                array(
                    array(
                        'name' => 'FooBundle',
                        'routing' => array(
                            'file' => 'root\routing\foobundle.yml',
                            'content' => "foo:\n  path: /blog",
                        ),
                    ), // This bundle does not implement routing
                    array(
                        'name' => 'BarBundle',
                    ),
                ),
                array(
                    'foo',
                ),
            ),
            array(
                array(
                    array(
                        'name' => 'BarBundle',
                        'priority' => -128,
                        'routing' => array(
                            'file' => 'root\routing\barbundle.yml',
                            'content' => "bar:\n  path: /blog",
                        ),
                    ),
                    array(
                        'name' => 'FooBundle',
                        'routing' => array(
                            'file' => 'root\routing\foobundle.yml',
                            'content' => "foo:\n  path: /blog",
                        ),
                    ),
                ),
                array(
                    'bar',
                    'foo',
                ),
            ),
        );
    }
}