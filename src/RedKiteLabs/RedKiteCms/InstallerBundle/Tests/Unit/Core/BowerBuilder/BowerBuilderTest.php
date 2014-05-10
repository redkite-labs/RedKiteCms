<?php
/*
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT LICENSE. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT LICENSE
 *
 */

namespace RedKiteLabs\RedKiteCms\InstallerBundle\Tests\Unit\Core\BowerBuilder;

use RedKiteLabs\RedKiteCms\InstallerBundle\Core\BowerBuilder\BowerBuilder;
use RedKiteLabs\RedKiteCms\InstallerBundle\Tests\TestCase;
use org\bovigo\vfs\vfsStream;

/**
 * BowerBuilderTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BowerBuilderTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        
        $this->bower = new BowerBuilder($this->kernel);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage File vfs://root/RedKiteCmsBundle/component.json has an error: please check the syntax consistency
     */
    public function testBuildFailsBecauseAFileHasASyntaxError()
    {        
        $file1 = '{
            "dependencies": {
                "jquery": "1.9.0",
                "jquery-ui": "1.9.2"
            }
        }';
        
        // This file has a syntax error
        $file2 = '{
            "dependencies": {
                "bootstrap": "2.2.2",
            }
        }';
        $this->buildStructure($file1, $file2);
        $this->kernel->expects($this->once())
            ->method('getBundles')
            ->will($this->returnValue($this->getBundles()));

        $file = vfsStream::url('root/bower.json');
        $this->bower->build($file);
    }
    
    /**
     * @dataProvider filesProvider
     */
    public function testBuild($file1, $file2, $result)
    {        
        $this->buildStructure($file1, $file2);
        
        $this->kernel->expects($this->once())
            ->method('getBundles')
            ->will($this->returnValue($this->getBundles()))
        ;

        $this->kernel->expects($this->once())
            ->method('getRootDir')
            ->will($this->returnValue(vfsStream::url('root/app')))
        ;

        $webPath = vfsStream::url('root/web');
        $bower = vfsStream::url('root/bower.json');
        $bowerrc = vfsStream::url('root/.bowerrc');
        $this->assertFileNotExists($bower);
        $this->bower->build($webPath);
        $this->assertFileExists($bower);
        $this->assertEquals($result, file_get_contents($bower));
        $this->assertEquals('{"directory":"vfs:\/\/root\/web\/components"}', file_get_contents($bowerrc));
    }
    
    public function filesProvider()
    {
        return array(
            array('
                    {
                        "dependencies": {
                            "jquery": "1.9.0",
                            "jquery-ui": "1.9.2"
                        }
                    }',
                   '{
                        "dependencies": {
                            "bootstrap": "2.2.2"
                        }
                    }',
                    '{"name":"RedKite CMS","dependencies":{"jquery":"1.9.0","jquery-ui":"1.9.2","bootstrap":"2.2.2"}}',
            ),
            array('
                    {
                        "dependencies": {
                            "jquery": "1.9.0",
                            "jquery-ui": "1.9.2"
                        }
                    }',
                   '{
                        "dependencies": {
                            "jquery": "1.9.0",
                            "bootstrap": "2.2.2"
                        }
                    }',
                    '{"name":"RedKite CMS","dependencies":{"jquery":"1.9.0","jquery-ui":"1.9.2","bootstrap":"2.2.2"}}',
            ),
            array('
                    {
                        "dependencies": {
                            "jquery": "1.9.0",
                            "jquery-ui": "1.9.2"
                        }
                    }',
                   '{
                        "dependencies": {
                            "jquery": "1.9.0",
                            "bootstrap": "2.2.2"
                        }
                    }',
                    '{"name":"RedKite CMS","dependencies":{"jquery":"1.9.0","jquery-ui":"1.9.2","bootstrap":"2.2.2"}}',
            ),
            array('
                    {
                        "dependencies": {
                            "jquery": "1.9.0",
                            "jquery-ui": "1.9.2"
                        }
                    }',
                   '{
                        "dependencies": {
                            "jquery": "1.9.0",
                            "bootstrap": "2.2.2"
                        }
                    }',
                    '{"name":"RedKite CMS","dependencies":{"jquery":"1.9.0","jquery-ui":"1.9.2","bootstrap":"2.2.2"}}',
            ),
        );
    }


    private function buildStructure($component1, $component2)
    {
        $structure =
            array(
                'app' => array(),
                'web' => array(
                    'components' => array(),
                ),
                'FooBundle' => array('component.json' => $component1),
                'RedKiteCmsBundle' => array('component.json' => $component2),
                'BarBundle' => array(),                
            )
        ;        
        return vfsStream::setup('root', null, $structure);
    }
    
    private function getBundles()
    {
        $bundles[] = $this->initBundle('root/FooBundle');
        $bundles[] = $this->initBundle('root/RedKiteCmsBundle');
        
        return $bundles;
    }
    
    private function initBundle($returnPath)
    {
        $bundle = $this->getMock('Symfony\Component\HttpKernel\Bundle\BundleInterface');
        $bundle->expects($this->once())
            ->method('getPath')
            ->will($this->returnValue(vfsStream::url($returnPath)));
        
        return $bundle;
    }
}