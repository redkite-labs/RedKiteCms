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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Deploy;

use AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlTwigDeployerProduction;
use org\bovigo\vfs\vfsStream;

/**
 * AlTwigDeployerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlTwigDeployerProductionTest extends AlPageTreeCollectionBootstrapper
{
    private $container;
    private $dispatcher;
    private $templateSlots;

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

        $this->templateSlots = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface');
        $this->blockManagerFactory = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $this->urlManager = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Core\UrlManager\AlUrlManagerInterface');
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->dispatcher = $this->getMock('\Symfony\Component\EventsDispatcher\EventDispatcherInterface', array('dispatch'));
        $this->viewRenderer = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\ViewRenderer\AlViewRendererInterface');
        
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
     * @expectedExceptio \RuntimeException
     *
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

        $this->deployer = new AlTwigDeployerProduction($this->container);
        $this->deployer->deploy();
    }*/
    
    public function pages() {
        return array( 
            /*array(
                array(
                    array(
                        'language' => 'en', 
                        'isMain' => true,
                    ),
                    array(
                        'language' => 'es', 
                        'isMain' => false,
                    ),
                ),
                array(
                    array(
                        'page' => 'index', 
                        'isHome' => true,
                        'published' => true,
                    ),
                    array(
                        'page' => 'page-1',
                        'isHome' => false,
                        'published' => true,
                    ),
                ),
                array(
                    array(
                        'language' => 'en',
                        'page' => 'index', 
                        'permalink' => 'homepage',
                    ),
                    array(
                        'language' => 'en',
                        'page' => 'page-1', 
                        'permalink' => 'my-awesome-page',
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'index', 
                        'permalink' => 'es-homepage',
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'page-1', 
                        'permalink' => 'es-my-awesome-page',
                    ),
                )
            ),*/
            array(
                array(
                    array(
                        'language' => 'en', 
                        'isMain' => true,
                    ),
                    array(
                        'language' => 'es', 
                        'isMain' => false,
                    ),
                ),
                array(
                    array(
                        'page' => 'index', 
                        'isHome' => true,
                        'published' => true,
                    ),
                    array(
                        'page' => 'page-1',
                        'isHome' => false,
                        'published' => true,
                    ),
                    array(
                        'page' => 'page-2',
                        'isHome' => false,
                        'published' => true,
                    ),
                ),
                array(
                    array(
                        'language' => 'en',
                        'page' => 'index', 
                        'permalink' => 'homepage',
                    ),
                    array(
                        'language' => 'en',
                        'page' => 'page-1', 
                        'permalink' => 'my-awesome-page',
                    ),
                    array(
                        'language' => 'en',
                        'page' => 'page-2', 
                        'permalink' => 'my-great-page',
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'index', 
                        'permalink' => 'es-homepage',
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'page-1', 
                        'permalink' => 'es-my-awesome-page',
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'page-2', 
                        'permalink' => 'es-my-great-page',
                    ),
                )
            ),   
        );
    }

    /**
     * @dataProvider pages
     */
    public function testDeploy($languages, $pages, $seo)
    {
        $this->setUpLanguagesAndPages($languages, $pages, $seo);
        
        $this->template->expects($this->any())
            ->method('getThemeName')
            ->will($this->returnValue('BootbusinessThemeBundle'));

        $blockManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $blockManager->expects($this->any())
            ->method('setEditorDisabled')
            ->with(true);
        
        $this->blockManagerFactory->expects($this->any())
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager));

        $activeTheme = $this->getMock('\AlphaLemon\ThemeEngineBundle\Core\Theme\AlActiveThemeInterface');
        $activeTheme->expects($this->any())
            ->method('getActiveTheme')
            ->will($this->returnValue('BootbusinessTheme'));

        $this->initContainer();

        $this->dispatcher->expects($this->exactly(2))
            ->method('dispatch');
        
        $this->container->expects($this->at(16))
            ->method('get')
            ->with('alpha_lemon_cms.themes_collection_wrapper')
            ->will($this->returnValue($this->themesCollectionWrapper));

        $dispatcher = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $i = 17;
        $numberOfCalls = ($this->cycles * 2) + $i;
        while ($i < $numberOfCalls) {
            $this->container->expects($this->at($i))
                ->method('get')
                ->with('event_dispatcher')
                ->will($this->returnValue($dispatcher));
            $i = $i + 2;
        }
        
        $i = 18;
        while ($i < $numberOfCalls + 1) {
            $this->container->expects($this->at($i))
                ->method('get')
                ->with('alphalemon_theme_engine.active_theme')
                ->will($this->returnValue($activeTheme));
            $i = $i + 2;
        }
        
        $this->container->expects($this->at($i + 11)) // 7
            ->method('getParameter')
            ->with('alpha_lemon_cms.web_folder_full_path')
            ->will($this->returnValue(vfsStream::url('root')));
        
        $this->template->expects($this->any())
            ->method('getSlots')
            ->will(
                $this->returnValue(
                    array(
                        'logo' => array('repeated' => 'site'),                        
                        'menu' => array('repeated' => 'language'),
                        'content' => array('repeated' => 'page'),
                    )
                )
            );
        
        $slot = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $slot->expects($this->any())
            ->method('getRepeated')
            ->will($this->returnValue('site'));
            
        $slot1 = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $slot1->expects($this->any())
            ->method('getRepeated')
            ->will($this->returnValue('language'));
            
        $slot2 = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $slot2->expects($this->any())
            ->method('getRepeated')
            ->will($this->returnValue('page'));
        
        $returnValueArray = array(
            $slot, $slot1, $slot2,$slot, $slot1, $slot2,$slot, $slot1, $slot2,$slot, $slot1, $slot2,$slot, $slot1, $slot2,$slot, $slot1, $slot2,
            $slot, $slot1, $slot2,$slot, $slot1, $slot2,$slot, $slot1, $slot2,$slot, $slot1, $slot2,$slot, $slot1, $slot2,$slot, $slot1, $slot2
        );
        $this->template->expects($this->any())
            ->method('getSlot')
            ->will(new \PHPUnit_Framework_MockObject_Stub_ConsecutiveCalls($returnValueArray));
        
        $returnValueArray = array('home', 'home', 'fullpage', 'fullpage', 'home', 'home', 'fullpage', 'fullpage', 'home', 'home', 'fullpage', 'fullpage','home', 'home', 'fullpage', 'fullpage', 'home', 'home', 'fullpage', 'fullpage', 'home', 'home', 'fullpage', 'fullpage');
        $this->template->expects($this->any())
            ->method('getTemplateName')
            ->will(new \PHPUnit_Framework_MockObject_Stub_ConsecutiveCalls($returnValueArray));

        $this->template->expects($this->any())
            ->method('getTemplateSlots')
            ->will($this->returnValue($this->templateSlots));
        
        $returnValueArray = array(
            "1", "2", "3", "4", "5", "6", "7", "8","1", "2", "3", "4", "5", "6", "7", "8"
        );
        $this->viewRenderer->expects($this->any())
            ->method('render')                
            ->will(new \PHPUnit_Framework_MockObject_Stub_ConsecutiveCalls($returnValueArray));
                
        $block = $this->setUpBlock('my content repeated at site level');
        $block->expects($this->any())
            ->method('getSlotName')
            ->will($this->returnValue('logo'));
            
        $block1 = $this->setUpBlock('my content repeated at language level');
        $block1->expects($this->any())
            ->method('getSlotName')
            ->will($this->returnValue('menu'));
            
        $block2 = $this->setUpBlock('my content repeated at page level');
        $block2->expects($this->any())
            ->method('getSlotName')
            ->will($this->returnValue('content'));
        $this
            ->pageBlocks
            ->expects($this->any())
            ->method('getBlocks')
            ->will($this->returnValue(
                    array(
                        "logo" => array(
                            $block 
                        ),
                        "menu" => array(
                            $block1 
                        ),
                        "content" => array(
                            $block2 
                        ),
                    )
                )
            )
        ;
        //print_r(vfsStream::inspect(new \org\bovigo\vfs\visitor\vfsStreamStructureVisitor())->getStructure());exit;
        $this->deployer = new AlTwigDeployerProduction($this->container);
        $this->assertTrue($this->deployer->deploy()); // print_r(vfsStream::inspect(new \org\bovigo\vfs\visitor\vfsStreamStructureVisitor())->getStructure());exit;

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
        //$this->assertEquals($siteRouting, file_get_contents(vfsStream::url('root\AcmeWebSiteBundle\Resources\config\site_routing.yml')));
        //echo file_get_contents(vfsStream::url('root\AcmeWebSiteBundle\Resources\config\site_routing.yml')) . "\n\n";

        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
        $sitemap .= '<url>' . PHP_EOL;
        $sitemap .= '	<loc>http://alphalemon.com/homepage</loc>' . PHP_EOL;
        $sitemap .= '	<changefreq></changefreq>' . PHP_EOL;
        $sitemap .= '	<priority></priority>' . PHP_EOL;
        $sitemap .= '</url>' . PHP_EOL;
        $sitemap .= '<url>' . PHP_EOL;
        $sitemap .= '	<loc>http://alphalemon.com/</loc>' . PHP_EOL;
        $sitemap .= '	<changefreq></changefreq>' . PHP_EOL;
        $sitemap .= '	<priority></priority>' . PHP_EOL;
        $sitemap .= '</url>' . PHP_EOL;
        $sitemap .= '<url>' . PHP_EOL;
        $sitemap .= '	<loc>http://alphalemon.com/</loc>' . PHP_EOL;
        $sitemap .= '	<changefreq></changefreq>' . PHP_EOL;
        $sitemap .= '	<priority></priority>' . PHP_EOL;
        $sitemap .= '</url>' . PHP_EOL;
        $sitemap .= '<url>' . PHP_EOL;
        $sitemap .= '	<loc>http://alphalemon.com/</loc>' . PHP_EOL;
        $sitemap .= '	<changefreq></changefreq>' . PHP_EOL;
        $sitemap .= '	<priority></priority>' . PHP_EOL;
        $sitemap .= '</url>' . PHP_EOL;
        $sitemap .= '</urlset>';

        $sitemapFile = vfsStream::url('root\sitemap.xml');
        //$this->assertFileExists($sitemapFile);
        //$this->assertEquals($sitemap, file_get_contents($sitemapFile));
        
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->hasChild('views'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('views')->hasChild('AlphaLemon'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('views')->getChild('AlphaLemon')->hasChild('en'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('views')->getChild('AlphaLemon')->getChild('en')->hasChild('base'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('views')->getChild('AlphaLemon')->getChild('en')->getChild('base')->hasChild('home.html.twig'));        
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('views')->getChild('AlphaLemon')->getChild('en')->getChild('base')->hasChild('fullpage.html.twig'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('views')->getChild('AlphaLemon')->getChild('en')->hasChild('index.html.twig'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('views')->getChild('AlphaLemon')->getChild('en')->hasChild('page-1.html.twig'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('views')->getChild('AlphaLemon')->hasChild('es'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('views')->getChild('AlphaLemon')->getChild('es')->hasChild('index.html.twig'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('views')->getChild('AlphaLemon')->getChild('es')->hasChild('page-1.html.twig'));

        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->hasChild('public'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->hasChild('media'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->getChild('media')->hasChild('image1.png'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->getChild('media')->hasChild('image2.png'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->hasChild('js'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->getChild('js')->hasChild('code.js'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->hasChild('css'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('public')->getChild('css')->hasChild('style.css'));

        /*$this->checkTemplateSection('en', 'index', 'home');
        $this->checkTemplateSection('en', 'page-1', 'fullpage');
        $this->checkTemplateSection('es', 'index', 'home');
        $this->checkTemplateSection('es', 'page-1', 'fullpage');*/
    }

    private function checkTemplateSection($language, $page, $template)
    {
        $contents = file_get_contents(vfsStream::url(sprintf('root/AcmeWebSiteBundle/Resources/views/AlphaLemon/%s/%s.html.twig', $language, $page)));

        $this->assertRegExp("/\'BootbusinessThemeBundle\:Theme\:$template\.html\.twig\'/s", $contents);
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

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->container->expects($this->at(6))
            ->method('get')
            ->with('request')
            ->will($this->returnValue($request));
        
        $this->container->expects($this->at(7))
            ->method('getParameter')
            ->with('alpha_lemon_cms.upload_assets_dir')
            ->will($this->returnValue('uploads/assets'));
        
        $this->container->expects($this->at(8))
            ->method('getParameter')
            ->with('alpha_lemon_cms.web_folder')
            ->will($this->returnValue('web'));
        
        $this->container->expects($this->at(9))
            ->method('getParameter')
            ->with('alpha_lemon_cms.deploy_bundle.controller')
            ->will($this->returnValue('WebSite'));
        
        $this->container->expects($this->at(10))
            ->method('getParameter')
            ->with('alpha_lemon_theme_engine.deploy.templates_folder')
            ->will($this->returnValue('AlphaLemon'));
        
        $this->container->expects($this->at(11))
                ->method('get')
                ->with('alpha_lemon_cms.view_renderer')
                ->will($this->returnValue($this->viewRenderer));
        
        $this->container->expects($this->at(12))
            ->method('get')
            ->with('alpha_lemon_cms.url_manager')
            ->will($this->returnValue($this->urlManager));

        $this->container->expects($this->at(13))
            ->method('get')
            ->with('alpha_lemon_cms.block_manager_factory')
            ->will($this->returnValue($this->blockManagerFactory));

        $this->container->expects($this->at(14))
            ->method('getParameter')
            ->with('alpha_lemon_cms.deploy_bundle.views_dir')
            ->will($this->returnValue('Resources/views'));
        
        $this->container->expects($this->at(15))
            ->method('get')
            ->with('event_dispatcher')
            ->will($this->returnValue($this->dispatcher));
    }
}
