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


        $this->event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')
                            ->disableOriginalConstructor()
                            ->getMock();
    }

    public function testConfigurationIsSkippedWhenTheEnvironmentIsNotAlCms()
    {
        $this->initContainer();

        $this->setUpEnvironment('dev');
        $testListener = new CmsBootstrapListener($this->container);
        $this->assertNull($testListener->onKernelRequest($this->event));
    }

    public function testCmsHasBeenBootstrapped()
    {
        $this->initContainer();
        
        $this->container->expects($this->at(2))
            ->method('getParameter')
            ->with('alpha_lemon_theme_engine.deploy_bundle')
            ->will($this->returnValue('@AcmeWebSiteBundle'));

        $this->container->expects($this->at(3))
            ->method('getParameter')
            ->with('alpha_lemon_cms.deploy_bundle.assets_base_dir')
            ->will($this->returnValue('asset-base-dir'));

        $this->container->expects($this->at(4))
            ->method('getParameter')
            ->with('alpha_lemon_cms.deploy_bundle.media_dir')
            ->will($this->returnValue('media'));

        $this->container->expects($this->at(5))
            ->method('getParameter')
            ->with('alpha_lemon_cms.deploy_bundle.js_dir')
            ->will($this->returnValue('js'));

        $this->container->expects($this->at(6))
            ->method('getParameter')
            ->with('alpha_lemon_cms.deploy_bundle.css_dir')
            ->will($this->returnValue('css'));

        $this->container->expects($this->at(7))
            ->method('getParameter')
            ->with('alpha_lemon_cms.upload_assets_full_path')
            ->will($this->returnValue(vfsStream::url('root/cms-assets/uploades-base-dir')));

        $this->container->expects($this->at(8))
            ->method('getParameter')
            ->with('alpha_lemon_cms.deploy_bundle.media_dir')
            ->will($this->returnValue('media'));

        $this->container->expects($this->at(9))
            ->method('getParameter')
            ->with('alpha_lemon_cms.deploy_bundle.js_dir')
            ->will($this->returnValue('js'));

        $this->container->expects($this->at(10))
            ->method('getParameter')
            ->with('alpha_lemon_cms.deploy_bundle.css_dir')
            ->will($this->returnValue('css'));

        $this->container->expects($this->at(11))
            ->method('get')
            ->with('alpha_lemon_cms.repeated_slots_aligner')
            ->will($this->returnValue($this->aligner));

        $this->setUpEnvironment('alcms');
        $this->setupFolders();

        $this->kernel->expects($this->once())
            ->method('locateResource')
            ->will($this->returnValue(vfsStream::url('root/frontend-assets')));

        /*
        $this->container->expects($this->any())
            ->method('getParameter')
            ->will($this->onConsecutiveCalls('@AcmeWebSiteBundle', 'asset-base-dir', 'media', 'js', 'css', vfsStream::url('root/cms-assets/uploades-base-dir'), 'media', 'js', 'css'));
*/
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

        $testListener = new CmsBootstrapListener($this->container);
        $testListener->onKernelRequest($this->event);
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

    private function initContainer()
    {
        $this->container->expects($this->at(0))
            ->method('get')
            ->with('kernel')
            ->will($this->returnValue($this->kernel));

         $this->container->expects($this->at(1))
            ->method('get')
            ->with('alpha_lemon_cms.page_tree')
            ->will($this->returnValue($this->pageTree));
    }
}
