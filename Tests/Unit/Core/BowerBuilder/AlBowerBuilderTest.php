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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\PageTree;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use org\bovigo\vfs\vfsStream;

/**
 * AlBowerBuilderTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlBowerBuilderTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        
        $this->bower = new \AlphaLemon\AlphaLemonCmsBundle\Core\BowerBuilder\AlBowerBuilder($this->kernel);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage File vfs://root/AlphaLemonCmsBundle/component.json has an error: please check the syntax consistency
     */
    public function testBuildFailsBecauseAFileHasASyntaxError()
    {        
        $file1 = '{
            "dependencies": {
                "jquery": "1.8.3",
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
        
        $file = vfsStream::url('root/component.json');
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
            ->will($this->returnValue($this->getBundles()));
        
        $file = vfsStream::url('root/component.json');
        $this->assertFileNotExists($file);
        $this->bower->build($file);
        $this->assertFileExists($file);
        $this->assertEquals($result, file_get_contents($file));
    }
    
    public function filesProvider()
    {
        return array(
            array('
                    {
                        "dependencies": {
                            "jquery": "1.8.3",
                            "jquery-ui": "1.9.2"
                        }
                    }',
                   '{
                        "dependencies": {
                            "bootstrap": "2.2.2"
                        }
                    }',
                    '{"name":"AlphaLemon CMS","dependencies":{"jquery":"1.8.3","jquery-ui":"1.9.2","bootstrap":"2.2.2"}}',
            ),
            array('
                    {
                        "dependencies": {
                            "jquery": "1.8.3",
                            "jquery-ui": "1.9.2"
                        }
                    }',
                   '{
                        "dependencies": {
                            "jquery": "1.8.3",
                            "bootstrap": "2.2.2"
                        }
                    }',
                    '{"name":"AlphaLemon CMS","dependencies":{"jquery":"1.8.3","jquery-ui":"1.9.2","bootstrap":"2.2.2"}}',
            ),
            array('
                    {
                        "dependencies": {
                            "jquery": "1.8.3",
                            "jquery-ui": "1.9.2"
                        }
                    }',
                   '{
                        "dependencies": {
                            "jquery": "1.9.0",
                            "bootstrap": "2.2.2"
                        }
                    }',
                    '{"name":"AlphaLemon CMS","dependencies":{"jquery":"1.9.0","jquery-ui":"1.9.2","bootstrap":"2.2.2"}}',
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
                            "jquery": "1.8.3",
                            "bootstrap": "2.2.2"
                        }
                    }',
                    '{"name":"AlphaLemon CMS","dependencies":{"jquery":"1.8.3","jquery-ui":"1.9.2","bootstrap":"2.2.2"}}',
            ),
        );
    }


    private function buildStructure($component1, $component2)
    {
        $structure =
            array(
                'FooBundle' => array('component.json' => $component1),
                'AlphaLemonCmsBundle' => array('component.json' => $component2),
                'BarBundle' => array(),                
            )
        ;        
        return vfsStream::setup('root', null, $structure);
    }
    
    private function getBundles()
    {
        $bundles[] = $this->initBundle('root/FooBundle');
        $bundles[] = $this->initBundle('root/AlphaLemonCmsBundle');
        
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