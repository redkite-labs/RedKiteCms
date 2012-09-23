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

    public function testCmsHasBeenBootstrapped()
    {
        $this->setUpEnvironment('alcms');
        $this->setupFolders();

        /*
        $this->kernel->expects($this->exactly(2))
            ->method('locateResource')
            ->will($this->onConsecutiveCalls(vfsStream::url('root/frontend-assets'), vfsStream::url('root/cms-assets/')));
        */
        $this->kernel->expects($this->once())
            ->method('locateResource')
            ->will($this->returnValue(vfsStream::url('root/frontend-assets')));

        $this->container->expects($this->any())
            ->method('getParameter')
            ->will($this->onConsecutiveCalls('@AcmeWebSiteBundle', 'asset-base-dir', 'media', 'js', 'css', vfsStream::url('root/cms-assets/uploades-base-dir'), 'media', 'js', 'css'));

        $this->pageTree->expects($this->once())
            ->method('setup');

        $template = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate')
                            ->disableOriginalConstructor()
                            ->getMock();
        $template->expects($this->once())
            ->method('getThemeName')
            ->will($this->returnValue('Theme'));

        $template->expects($this->once())
            ->method('getTemplateName')
            ->will($this->returnValue('Template'));

        $template->expects($this->once())
            ->method('getSlots')
            ->will($this->returnValue(array('fake' => 'slots')));

        $this->pageTree->expects($this->once())
            ->method('getTemplate')
            ->will($this->returnValue($template));

        $this->aligner->expects($this->once())
            ->method('align');

        $expectedResult = array('root' =>
                                    array('frontend-assets' =>
                                        array('asset-base-dir' =>
                                            array(
                                                'media' => array(),
                                                'js' => array(),
                                                'css' => array()
                                            ),
                                        ),

                                        'cms-assets' =>
                                                    array('uploades-base-dir' =>
                                                        array(
                                                            'media' => array(),
                                                            'js' => array(),
                                                            'css' => array()
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
