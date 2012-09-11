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

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $this->container->expects($this->any())
            ->method('getParameter')
            ->will($this->onConsecutiveCalls('AcmeWebSiteBundle',
                    'Resources/config',
                    'Resources/public/',
                    'uploads/assets',
                    'web',
                    'Resources/views'));

        $folders = array('app' => array(),
                         'web' => array('bundles'
                                => array('alphalemoncms'
                                    => array('uploads'
                                        => array('assets'
                                            => array('media' => array('image1.png' => '', 'image2.png' => ''),
                                                    'js' => array('code.js' => ''),
                                                    'css' => array('style.css' => ''),
                                            )
                                        )
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

        $this->container->expects($this->any())
            ->method('get')
            ->will($this->onConsecutiveCalls($this->kernel, $this->factoryRepository));

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
        $blockManagerFactory = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $blockManagerFactory->expects($this->exactly(4))
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager));
        $urlManager = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Core\UrlManager\AlUrlManagerInterface');
        
        $activeTheme = $this->getMock('\AlphaLemon\ThemeEngineBundle\Core\Theme\AlActiveThemeInterface');
        $activeTheme->expects($this->any())
            ->method('getActiveTheme')
            ->will($this->returnValue('BusinessWebsiteTheme'));
        $this->container->expects($this->any())
            ->method('get')
            ->will($this->onConsecutiveCalls($this->kernel, $this->factoryRepository, $urlManager, $blockManagerFactory, $this->themesCollectionWrapper, $activeTheme, $activeTheme, $activeTheme, $activeTheme));

        $this->templateSlots->expects($this->exactly(4))
            ->method('getSlots')
            ->will($this->returnValue(array()));

        $this->deployer = new AlTwigDeployer($this->container);
        $this->assertTrue($this->deployer->deploy());

        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->hasChild('config'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('config')->hasChild('site_routing.yml'));

        $siteRouting = "# Route << homepage >> generated for language << en >> and page << index >>\n";
        $siteRouting .= "_en_index:\n";
        $siteRouting .= "  pattern: /homepage\n";
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
}
