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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Deploy;

use AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlTwigDeployer;
use org\bovigo\vfs\vfsStream;

/**
 * AlTwigDeployerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlTwigDeployerTest extends AlPageTreeCollectionBootstrapper
{
    private $container;

    protected function setUp()
    {
        parent::setUp();

        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $this->kernel->expects($this->any())
            ->method('locateResource')
            ->will($this->onConsecutiveCalls(vfsStream::url('AcmeWebSiteBundle'), vfsStream::url('AlphaLemonCmsBundle')));

        $this->kernel->expects($this->any())
            ->method('getRootDir')
            ->will($this->returnValue(vfsStream::url('app')));

        $this->blockManagerFactory = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $this->urlManager = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Core\UrlManager\AlUrlManagerInterface');
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
/*
        $this->container->expects($this->any())
            ->method('getParameter')
            ->will($this->onConsecutiveCalls('AcmeWebSiteBundle',
                    'Resources/config',
                    'Resources/public/',
                    vfsStream::url('root/web/uploads/assets'),
                    '/web/uploads/assets',
                    'Resources/views'));*/

        $folders = array('app' => array(),
                         'web' => array('uploads'
                                    => array('assets'
                                        => array('media' => array('image1.png' => '', 'image2.png' => ''),
                                                'js' => array('code.js' => ''),
                                                'css' => array('style.css' => ''),
                                                )
                                            )
                                        ),
                         'AcmeWebSiteBundle' => array('Resources' => array()),
                         'AlphaLemonCmsBundle' => array(),
                        );
        $this->root = vfsStream::setup('root', null, $folders);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAnExceptionIsThrownWhenATargetDirectoryDoesNotExist()
    {
        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->seoRepository));

        $this->initContainer();

        $folders = array('AcmeWebSiteBundle' => array(), 'AlphaLemonCmsBundle' => array('Resources'));
        $this->root = vfsStream::setup('root', null, $folders);

        vfsStream::newDirectory('Resources', 0444)->at($this->root->getChild('AcmeWebSiteBundle'));

        $this->deployer = new AlTwigDeployer($this->container);
        $this->deployer->deploy();
    }

    public function testDeploy()
    {
        $this->initSomeLangugesAndPages();

        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->onConsecutiveCalls($this->seoRepository, $this->languageRepository, $this->pageRepository, $this->seoRepository,
                    $this->languageRepository, $this->pageRepository, $this->seoRepository,
                    $this->languageRepository, $this->pageRepository, $this->seoRepository,
                    $this->languageRepository, $this->pageRepository, $this->seoRepository,
                    $this->languageRepository, $this->pageRepository, $this->seoRepository));

        $seo1 = $this->setUpSeo('homepage', $this->page1, $this->language1);
        $seo2 = $this->setUpSeo('my-awesome-page', $this->page2, $this->language1);
        $seo3 = $this->setUpSeo('es-homepage', $this->page1, $this->language2);
        $seo4 = $this->setUpSeo('es-my-awesome-page', $this->page2, $this->language2);
        $this->seoRepository->expects($this->once())
            ->method('fetchSeoAttributesWithPagesAndLanguages')
            ->will($this->returnValue(array($seo1, $seo2, $seo3, $seo4)));

        $this->template->expects($this->exactly(4))
            ->method('getSlots')
            ->will($this->returnValue(array('logo' => array('repeated' => 'site'))));

        $this->template->expects($this->exactly(4))
            ->method('getThemeName')
            ->will($this->returnValue('BusinessWebsiteThemeBundle'));

        $this->template->expects($this->exactly(4))
            ->method('getTemplateName')
            ->will($this->onConsecutiveCalls('home', 'fullpage', 'home', 'fullpage'));

        $blockManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $blockManager->expects($this->exactly(4))
            ->method('getHtml')
            ->will($this->returnValue('Formatted content for deploying'));

        $this->blockManagerFactory->expects($this->exactly(4))
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager));

        $activeTheme = $this->getMock('\AlphaLemon\ThemeEngineBundle\Core\Theme\AlActiveThemeInterface');
        $activeTheme->expects($this->any())
            ->method('getActiveTheme')
            ->will($this->returnValue('BusinessWebsiteTheme'));

        $this->initContainer();

        $this->container->expects($this->at(10))
            ->method('get')
            ->with('alpha_lemon_cms.themes_collection_wrapper')
            ->will($this->returnValue($this->themesCollectionWrapper));

        for($i = 11; $i < 15; $i++) {
            $this->container->expects($this->at($i))
                ->method('get')
                ->with('alphalemon_theme_engine.active_theme')
                ->will($this->returnValue($activeTheme));
        }

        $this->templateSlots->expects($this->exactly(4))
            ->method('getSlots')
            ->will($this->returnValue(array()));

        $this->deployer = new AlTwigDeployer($this->container);
        $this->assertTrue($this->deployer->deploy());

        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->hasChild('config'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('config')->hasChild('site_routing.yml'));

        $siteRouting = "# Route <<  >> generated for language << en >> and page << index >>\n";
        $siteRouting .= "_en_index:\n";
        $siteRouting .= "  pattern: /\n";
        $siteRouting .= "  defaults: { _controller: AcmeWebSiteBundle:WebSite:show, _locale: en, page: index }\n";
        $siteRouting .= "\n";
        $siteRouting .= "# Route << my-awesome-page >> generated for language << en >> and page << page-1 >>\n";
        $siteRouting .= "_en_page_1:\n";
        $siteRouting .= "  pattern: /my-awesome-page\n";
        $siteRouting .= "  defaults: { _controller: AcmeWebSiteBundle:WebSite:show, _locale: en, page: page-1 }\n";
        $siteRouting .= "\n";
        $siteRouting .= "# Route << es-homepage >> generated for language << es >> and page << index >>\n";
        $siteRouting .= "_es_index:\n";
        $siteRouting .= "  pattern: /es-homepage\n";
        $siteRouting .= "  defaults: { _controller: AcmeWebSiteBundle:WebSite:show, _locale: es, page: index }\n";
        $siteRouting .= "\n";
        $siteRouting .= "# Route << es-my-awesome-page >> generated for language << es >> and page << page-1 >>\n";
        $siteRouting .= "_es_page_1:\n";
        $siteRouting .= "  pattern: /es-my-awesome-page\n";
        $siteRouting .= "  defaults: { _controller: AcmeWebSiteBundle:WebSite:show, _locale: es, page: page-1 }\n";
        $siteRouting .= "\n";
        $siteRouting .= "# Route <<  >> generated for language << en >> and page << index >>\n";
        $siteRouting .= "_home:\n";
        $siteRouting .= "  pattern: /\n";
        $siteRouting .= "  defaults: { _controller: AcmeWebSiteBundle:WebSite:show, _locale: en, page: index }";
        $this->assertEquals($siteRouting, file_get_contents(vfsStream::url('root\AcmeWebSiteBundle\Resources\config\site_routing.yml')));

        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->hasChild('views'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('views')->hasChild('en'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('views')->getChild('en')->hasChild('index.html.twig'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('views')->getChild('en')->hasChild('page-1.html.twig'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('views')->hasChild('es'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('views')->getChild('es')->hasChild('index.html.twig'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('views')->getChild('es')->hasChild('page-1.html.twig'));

        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->hasChild('public'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->hasChild('media'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->getChild('media')->hasChild('image1.png'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->getChild('media')->hasChild('image2.png'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->hasChild('js'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->getChild('js')->hasChild('code.js'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->hasChild('css'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->getChild('css')->hasChild('style.css'));

        $this->checkTemplateSection('en', 'index', 'home');
        $this->checkTemplateSection('en', 'page-1', 'fullpage');
        $this->checkTemplateSection('es', 'index', 'home');
        $this->checkTemplateSection('es', 'page-1', 'fullpage');
    }

    private function checkTemplateSection($language, $page, $template)
    {
        $contents = file_get_contents(vfsStream::url(sprintf('root/AcmeWebSiteBundle/Resources/views/%s/%s.html.twig', $language, $page)));

        $this->assertRegExp("/\'BusinessWebsiteThemeBundle\:Theme\:$template\.html\.twig\'/s", $contents);
    }

    private function initContainer()
    {
        $this->container->expects($this->at(0))
            ->method('get')
            ->with('kernel')
            ->will($this->returnValue($this->kernel));

        $this->container->expects($this->at(1))
            ->method('get')
            ->with('alpha_lemon_cms.factory_repository')
            ->will($this->returnValue($this->factoryRepository));

        $this->container->expects($this->at(2))
            ->method('getParameter')
            ->with('alpha_lemon_theme_engine.deploy_bundle')
            ->will($this->returnValue('AcmeWebSiteBundle'));

        $this->container->expects($this->at(3))
            ->method('getParameter')
            ->with('alpha_lemon_cms.deploy_bundle.config_dir')
            ->will($this->returnValue('Resources/config'));

        $this->container->expects($this->at(4))
            ->method('getParameter')
            ->with('alpha_lemon_cms.deploy_bundle.assets_base_dir')
            ->will($this->returnValue('Resources/public/'));

        $this->container->expects($this->at(5))
            ->method('getParameter')
            ->with('alpha_lemon_cms.upload_assets_full_path')
            ->will($this->returnValue(vfsStream::url('root/web/uploads/assets')));

        $this->container->expects($this->at(6))
            ->method('getParameter')
            ->with('alpha_lemon_cms.upload_assets_absolute_path')
            ->will($this->returnValue('/web/uploads/assets'));

        $this->container->expects($this->at(7))
            ->method('get')
            ->with('alpha_lemon_cms.url_manager')
            ->will($this->returnValue($this->urlManager));

        $this->container->expects($this->at(8))
            ->method('get')
            ->with('alpha_lemon_cms.block_manager_factory')
            ->will($this->returnValue($this->blockManagerFactory));

        $this->container->expects($this->at(9))
            ->method('getParameter')
            ->with('alpha_lemon_cms.deploy_bundle.views_dir')
            ->will($this->returnValue('Resources/views'));
    }
}
