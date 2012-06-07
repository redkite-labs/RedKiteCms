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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Listener\Cms;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Cms\CmsBootstrapListener;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;

/**
 * CmsBootstrapListenerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class CmsBootstrapListenerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $this->pageTree = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree')
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->aligner = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Aligner\AlRepeatedSlotsAligner')
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->container->expects($this->any())
            ->method('get')
            ->will($this->onConsecutiveCalls($this->kernel, $this->pageTree, $this->aligner));

        $this->event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')
                            ->disableOriginalConstructor()
                            ->getMock();
        $this->testListener = new CmsBootstrapListener($this->container);
    }

    public function testConfigurationIsSkippedWhenTheEnvironmentIsNotAlCms()
    {
        $this->setUpEnvironment('dev');
        $this->assertNull($this->testListener->onKernelRequest($this->event));
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterException
     * @expectedExceptionMessage The parameter xliff_skeleton is not well configured. Check your configuration file to solve the problem
     */
    public function testAnExceptionIsThrownWhenXliffSkeletonFileDoesNotExist()
    {
        $this->setUpEnvironment('alcms');

        $this->kernel->expects($this->any())
            ->method('locateResource')
            ->will($this->returnValue(false));

        $this->testListener->onKernelRequest($this->event);
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterException
     * @expectedExceptionMessage The parameter xml_skeleton is not well configured. Check your configuration file to solve the problem
     */
    public function testAnExceptionIsThrownWhenXmlSkeletonFileDoesNotExist()
    {
        $this->setUpEnvironment('alcms');

        $this->kernel->expects($this->any())
            ->method('locateResource')
            ->will($this->onConsecutiveCalls(true, false));

        $this->testListener->onKernelRequest($this->event);
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterException
     * @expectedExceptionMessage The parameter skeletons_folder is not well configured. Check your configuration  file to solve the problem
     */
    public function testAnExceptionIsThrownWhenAssetsSkeletonsFolderFileDoesNotExist()
    {
        $this->setUpEnvironment('alcms');

        $this->kernel->expects($this->any())
            ->method('locateResource')
            ->will($this->onConsecutiveCalls(true, true, false));

        $this->testListener->onKernelRequest($this->event);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage An error has occoured during the creation of required folders
     */
    public function testAnExceptionIsThrownWhenSomethingWasWrongDuringFoldersCreation()
    {
        $this->setUpEnvironment('alcms');
        $this->setupFolders(0444);

        $this->kernel->expects($this->any())
            ->method('locateResource')
            ->will($this->onConsecutiveCalls(true, true, true, vfsStream::url('root/frontend-assets'), vfsStream::url('root/cms-assets/')));

        $this->container->expects($this->any())
            ->method('getParameter')
            ->will($this->onConsecutiveCalls(null, null, null, null, null, 'media', 'js', 'css', 'fake', 'media', 'js', 'css'));

        $this->testListener->onKernelRequest($this->event);
    }

    public function testCmsHasBeenBootstrapped()
    {
        $this->setUpEnvironment('alcms');
        $this->setupFolders();

        $this->kernel->expects($this->any())
            ->method('locateResource')
            ->will($this->onConsecutiveCalls(true, true, true, vfsStream::url('root/frontend-assets'), vfsStream::url('root/cms-assets/')));

        $this->container->expects($this->any())
            ->method('getParameter')
            ->will($this->onConsecutiveCalls(null, null, null, null, null, 'media', 'js', 'css', 'fake', 'media', 'js', 'css'));

        $this->pageTree->expects($this->once())
            ->method('setup');

        $this->pageTree->expects($this->once())
            ->method('getThemeName')
            ->will($this->returnValue('Theme'));

        $this->pageTree->expects($this->once())
            ->method('getTemplateName')
            ->will($this->returnValue('Template'));

        $this->pageTree->expects($this->once())
            ->method('getSlots')
            ->will($this->returnValue(array('fake' => 'slots')));

        $this->aligner->expects($this->once())
            ->method('align');

        $expectedResult = array('root' =>
                                    array('frontend-assets' =>
                                        array('media' => array(),
                                                'js' => array(),
                                                'css' => array()
                                        ),

                                        'cms-assets' =>
                                            array('Resources' =>
                                                array('public' =>
                                                    array('fake' =>
                                                        array('media' => array(),
                                                            'js' => array(),
                                                            'css' => array()
                                                        ),
                                                    ),
                                                ),
                                            ),
                                        ),
                                    );

        $this->testListener->onKernelRequest($this->event);
        $this->assertEquals($expectedResult, vfsStream::inspect(new vfsStreamStructureVisitor())->getStructure());
    }

    private function setupFolders($permissions = 0777)
    {
        $this->root = vfsStream::setup('root');
        vfsStream::newDirectory('frontend-assets', $permissions)->at($this->root);
        vfsStream::newDirectory('cms-assets')->at($this->root);
    }

    private function setUpEnvironment($environment)
    {
        $this->kernel->expects($this->once())
            ->method('getEnvironment')
            ->will($this->returnValue($environment));
    }
}