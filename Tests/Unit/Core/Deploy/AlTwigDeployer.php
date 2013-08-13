<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Deploy;

use org\bovigo\vfs\vfsStream;

/**
 * AlTwigDeployerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
abstract class AlTwigDeployer extends AlPageTreeCollectionBootstrapper
{
    protected $container;
    protected $dispatcher;
    protected $templateSlots;
    protected $containerAtSequenceAfterObjectCreation;
    protected $templates;
    
    abstract protected function checkSiteMap($seo);
    
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
        $this->blockManagerFactory = $this->getMock('\RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        $this->urlManager = $this->getMock('\RedKiteLabs\RedKiteCmsBundle\Core\UrlManager\AlUrlManagerInterface');
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->dispatcher = $this->getMock('\Symfony\Component\EventsDispatcher\EventDispatcherInterface', array('dispatch'));
        $this->viewRenderer = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\ViewRenderer\AlViewRendererInterface');
                
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
        
        $this->templates = array(
            'home',
            'empty',
            'contacts',
            'two-columns',
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

        $blockManager = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManager')
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
        $this->saveBaseTemplates();
        
        $this->dispatcher->expects($this->exactly(2))
            ->method('dispatch');
        
        $dispatcher = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $i = $this->containerAtSequenceAfterObjectCreation;
        $cycles = $this->cycles * 3;
        $numberOfCalls = $cycles + $i;
        while ($i < $numberOfCalls) {
            $this->container->expects($this->at($i))
                ->method('get')
                ->with('event_dispatcher')
                ->will($this->returnValue($dispatcher));
            $i = $i + 3;
        }
        
        $i = $this->containerAtSequenceAfterObjectCreation + 1;
        while ($i < $numberOfCalls + 1) {
            $this->container->expects($this->at($i))
                ->method('get')
                ->with('alpha_lemon_cms.block_manager_factory')
                ->will($this->returnValue($activeTheme));
            $i = $i + 3;
        }
        
        $i = $this->containerAtSequenceAfterObjectCreation + 2;
        while ($i < $numberOfCalls + 2) {
            $this->container->expects($this->at($i))
                ->method('get')
                ->with('alphalemon_theme_engine.active_theme')
                ->will($this->returnValue($activeTheme));
            $i = $i + 3;
        }
        
        $this->template->expects($this->any())
            ->method('getSlots')
            ->will($this->returnValue($this->initTemplateSlots()));
        
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
        
        $returnValueArray = array();
        for ($i = 0; $i < $this->cycles; $i++) {
            $returnValueArray = array_merge($returnValueArray, array($slot, $slot1, $slot2,));
        }       
        $this->template->expects($this->any())
            ->method('getSlot')
            ->will(new \PHPUnit_Framework_MockObject_Stub_ConsecutiveCalls($returnValueArray));
        
        $this->template->expects($this->any())
            ->method('getTemplateSlots')
            ->will($this->returnValue($this->templateSlots));
        
        $this->viewRenderer->expects($this->any())
            ->method('render')   
            ->will($this->returnValue('Just a fake content'));
               
        $this
            ->pageBlocks
            ->expects($this->any())
            ->method('getBlocks')
            ->will($this->returnValue($this->setupBlocks()))
        ;
        //print_r(vfsStream::inspect(new \org\bovigo\vfs\visitor\vfsStreamStructureVisitor())->getStructure());exit;
        //$this->deployer = ;  //new AlTwigDeployerProduction($this->container);
        $this->assertTrue($this->getDeployer()->deploy());
        //print_r(vfsStream::inspect(new \org\bovigo\vfs\visitor\vfsStreamStructureVisitor())->getStructure());exit;

        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->hasChild('config'));
        $this->assertTrue($this->root->getChild('AcmeWebSiteBundle')->getChild('Resources')->getChild('config')->hasChild($this->siteRoutingFile));
        
        $this->assertEquals($this->buildExpectedRoutes($seo), file_get_contents(vfsStream::url('root\AcmeWebSiteBundle\Resources\config\\' . $this->siteRoutingFile)));
        $this->checkSiteMap($seo);
        
        $this->assertFileExists(vfsStream::url($this->assetsFolder));
        $this->checkDirectoryStructure($this->root, $this->buildExpectedStructure($languages, $pages));
        $this->checkTemplateExtension($languages, $pages);
    }
    
    protected function saveBaseTemplates()
    {
        $languagesRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface');
        $languagesRepository
            ->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue($this->languages))
        ;
        
        $counter = $this->counterRepositoriesCreation;
        $this->factoryRepository
            ->expects($this->at($counter))
            ->method('createRepository')
            ->with('Language')
            ->will($this->returnValue($languagesRepository))
        ;
        
        $blockRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface');
        $blockRepository
            ->expects($this->any())
            ->method('retrieveContents')
            ->will($this->returnValue(array()))
        ;
        
        $counter++;
        $this->factoryRepository
            ->expects($this->at($counter))
            ->method('createRepository')
            ->with('Block')
            ->will($this->returnValue($blockRepository))
        ;
        
        $theme = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\Theme\AlThemeInterface');
        
        $c = 1;
        $templates = array();
        $templates1 = array();
        for($i = 0; $i < count($this->languages); $i++) {
            foreach($this->templates as $templateName) {
                $template = $this->initTemplate($templateName);
                $templates[] = $template;                
                for($d = 0; $d < count($this->languages); $d++) {
                    $templates1[] = $template;
                    $templates1[] = $template;
                    
                    $theme->expects($this->at($c))
                        ->method('getTemplate')
                        ->will($this->returnValue($template))
                    ;
                    
                    $c++;
                }   
            }
        }
        
        $templateManager = $this->initLocalTemplateManager();    
        $templateManager
            ->expects($this->any()) 
            ->method('getTemplate')
            ->will(new \PHPUnit_Framework_MockObject_Stub_ConsecutiveCalls($templates1))
        ;       
        
        $theme->expects($this->once())
            ->method('getTemplates')
            ->will($this->returnValue($templates))
        ;

        $themes = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $themes  
            ->expects($this->any())
            ->method('getTheme')
            ->will($this->returnValue($theme))
        ;  

        $themesCollectionWrapper = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\ThemesCollectionWrapper\AlThemesCollectionWrapper')
            ->disableOriginalConstructor()
            ->getMock();    

        $themesCollectionWrapper->expects($this->any())
            ->method('getTheme')
            ->will($this->returnValue($theme));

        $themesCollectionWrapper
            ->expects($this->any())
            ->method('getThemesCollection')
            ->will($this->returnValue($themes))
        ;

        $themesCollectionWrapper
            ->expects($this->any())
            ->method('getTemplateManager')
            ->will($this->returnValue($templateManager))
        ;
        
        $this->container->expects($this->at(15))
                ->method('get')
                ->with('alpha_lemon_cms.themes_collection_wrapper')
                ->will($this->returnValue($themesCollectionWrapper));
    }
    
    protected function checkTemplateExtension($languages, $pages)
    {
        foreach ($languages as $language) {
            $languageName = $language['language'];
            
            foreach ($pages as $page) {       
                if ( ! $page['published']) {
                    continue;
                }
                
                $template = $page['template'];                
                $contents = file_get_contents(vfsStream::url(sprintf('root/AcmeWebSiteBundle/Resources/views/%s/%s/%s.html.twig', $this->templatesFolder, $languageName, $page['page'])));
                $this->assertRegExp("/\'AcmeWebSiteBundle:$this->templatesFolder\:$languageName\/base\/$template\.html\.twig\'/s", $contents);                
            }
        }
    }
    
    protected function buildExpectedSitemap($seo)
    {
        $sitemapItems = array();
        foreach($seo as $seoAttributes) {
            $sitemapItem = '<url>' . PHP_EOL;
            $sitemapItem .= sprintf('	<loc>http://alphalemon.com/%s</loc>' . PHP_EOL, $seoAttributes["permalink"]);
            $sitemapItem .= sprintf('	<changefreq>%s</changefreq>' . PHP_EOL, array_key_exists('changefreq', $seoAttributes) ? $seoAttributes["changefreq"] : '');
            $sitemapItem .= sprintf('	<priority>%s</priority>' . PHP_EOL, array_key_exists('priority', $seoAttributes) ? $seoAttributes["priority"] : '');
            $sitemapItem .= '</url>';

            $sitemapItems[] = $sitemapItem;
        };
        
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
        $sitemap .= implode("\n", $sitemapItems) . PHP_EOL;
        $sitemap .= '</urlset>';
        
        return $sitemap;
    }
    
    protected function buildExpectedRoutes($seo)
    {
        $routes = array();
        foreach($seo as $seoAttributes) {
            $language = $seoAttributes["language"];
            $page = $seoAttributes["page"];
            $permalink = ( ! $seoAttributes["homepage"]) ? $seoAttributes["permalink"] : "" ;
            $siteRouting = "# Route << %s >> generated for language << %s >> and page << %s >>\n";
            $siteRouting .= "_%s_%s:\n";
            $siteRouting .= "  pattern: /%s\n";
            $siteRouting .= "  defaults: { _controller: AcmeWebSiteBundle:WebSite:show, _locale: %s, page: %s }\n";

            $routes[] = sprintf($siteRouting, $permalink, $language, $page, $language, str_replace('-', '_', $page), $permalink, $language, $page);
        }
        
        $siteRouting = "# Route <<  >> generated for language << en >> and page << index >>\n";
        $siteRouting .= "_home:\n";
        $siteRouting .= "  pattern: /\n";
        $siteRouting .= "  defaults: { _controller: AcmeWebSiteBundle:WebSite:show, _locale: en, page: index }";
        
        $routes[] = $siteRouting;
        
        return (implode("\n", $routes));
    }
    
    protected function buildExpectedStructure($languages, $pages)
    {
        $structure = array();
        foreach ($languages as $language) {
            $languageName = $language['language'];
            $structure[$languageName] = array();
                        
            $templates = array();
            foreach ($pages as $page) {       
                if ( ! $page['published']) {
                    continue;
                }
                
                $fileName = $page['page'] . '.html.twig';     
                $structure[$languageName][$fileName] = '';
            }
            
            foreach ($this->templates as $templateName) {
                $fileName = $templateName . '.html.twig';  
                $structure[$languageName]['base'][$fileName] = '';
                $templates[] = $fileName;
            }
        }
        
        return array(
            'AcmeWebSiteBundle' => array(
                'Resources' => array(
                    'views' => array(
                        $this->templatesFolder => $structure,
                    ),
                ),
            ),
        );
    }
    
    protected function checkDirectoryStructure($node, $values)
    {
        foreach ($values as $directory => $value) {
            $this->assertTrue($node->hasChild($directory), $directory . " does not exist");
            if (is_array($value)) {
                $child = $node->getChild($directory);
                $this->checkDirectoryStructure($child, $value);
            }            
        }
    }

    protected function initContainer()
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
            ->with('alpha_lemon_cms.deploy_bundle.controller')
            ->will($this->returnValue('WebSite'));
        
        $this->container->expects($this->at(10))
                ->method('get')
                ->with('alpha_lemon_cms.view_renderer')
                ->will($this->returnValue($this->viewRenderer));
                
        $this->container->expects($this->at(11)) 
            ->method('getParameter')
            ->with('alpha_lemon_cms.web_folder_full_path')
            ->will($this->returnValue(vfsStream::url('root')));
        
        $this->container->expects($this->at(12))
            ->method('get')
            ->with('event_dispatcher')
            ->will($this->returnValue($this->dispatcher));
        
        $this->container->expects($this->at(13))
            ->method('getParameter')
            ->with('alpha_lemon_cms.love')
            ->will($this->returnValue('yes'));
        
        $activeTheme = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\Theme\AlActiveThemeInterface');
        $activeTheme->expects($this->once())
            ->method('getActiveTheme')
            ->will($this->returnValue('BootbusinessThemeBundle'));
            
        $this->container->expects($this->at(14))
            ->method('get')
            ->with('alpha_lemon_theme_engine.active_theme')
            ->will($this->returnValue($activeTheme));
    }
    
    private function initLocalTemplateManager()
    {
        $templateManager = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $templateManager
            ->expects($this->any())
            ->method('setTemplate')
            ->will($this->returnSelf())
        ;

        $templateManager
            ->expects($this->any()) 
            ->method('setPageBlocks')
            ->will($this->returnSelf())
        ;

        $templateManager
            ->expects($this->any()) 
            ->method('refresh')
        ;

        return $templateManager;
    }
    
    private function initTemplate($templateName)
    {
        $template = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate')
            ->disableOriginalConstructor()
            ->getMock();

        $template->expects($this->any())
            ->method('getSlots')
            ->will($this->returnValue($this->initTemplateSlots()));

        $template->expects($this->any())
            ->method('getTemplateSlots')
            ->will($this->returnValue($this->templateSlots));

        $template->expects($this->any()) 
            ->method('getThemeName')
            ->will($this->returnValue('BootbusinessThemeBundle'));

        $template->expects($this->any()) 
            ->method('getTemplateName')
            ->will($this->returnValue($templateName));
        
        return $template;
    }


    private function initTemplateSlots($slots = null)
    {
        if (null === $slots) {
            $slots = array(
                'logo' => array('repeated' => 'site'),                        
                'menu' => array('repeated' => 'language'),
                'content' => array('repeated' => 'page'),
            );
        }
        
        return $slots;
    }
    
    private function setupBlocks()
    {
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
            
        $blocks = array(
            "logo" => array(
                $block 
            ),
            "menu" => array(
                $block1 
            ),
            "content" => array(
                $block2 
            ),
        );
        
        return $blocks;
    }
    
    public function pages() {
        return array( 
            array(
                array(
                    array(
                        'language' => 'en', 
                        'isMain' => true,
                    ),
                ),
                array(
                    array(
                        'page' => 'index', 
                        'template' => 'home',
                        'isHome' => true,
                        'published' => true,
                    ),
                ),
                array(
                    array(
                        'language' => 'en',
                        'page' => 'index', 
                        'permalink' => 'homepage',
                        'homepage' => true,
                    ),
                )
            ),
            array(
                array(
                    array(
                        'language' => 'en', 
                        'isMain' => true,
                    ),
                ),
                array(
                    array(
                        'page' => 'index', 
                        'template' => 'home',
                        'isHome' => true,
                        'published' => true,
                    ),
                    array(
                        'page' => 'page-1',
                        'template' => 'fullpage',
                        'isHome' => false,
                        'published' => true,
                    ),
                ),
                array(
                    array(
                        'language' => 'en',
                        'page' => 'index', 
                        'permalink' => 'homepage',
                        'homepage' => true,
                    ),
                    array(
                        'language' => 'en',
                        'page' => 'page-1', 
                        'permalink' => 'my-awesome-page',
                        'homepage' => false,
                    ),
                ),
            ),
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
                        'template' => 'home',
                        'isHome' => true,
                        'published' => true,
                    ),
                ),
                array(
                    array(
                        'language' => 'en',
                        'page' => 'index', 
                        'permalink' => 'homepage',
                        'homepage' => true,
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'index', 
                        'permalink' => 'es-homepage',
                        'homepage' => false,
                    ),
                )
            ),
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
                        'template' => 'home',
                        'isHome' => true,
                        'published' => true,
                    ),
                    array(
                        'page' => 'page-1',
                        'template' => 'fullpage',
                        'isHome' => false,
                        'published' => true,
                    ),
                ),
                array(
                    array(
                        'language' => 'en',
                        'page' => 'index', 
                        'permalink' => 'homepage',
                        'homepage' => true,
                    ),
                    array(
                        'language' => 'en',
                        'page' => 'page-1', 
                        'permalink' => 'my-awesome-page',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'index', 
                        'permalink' => 'es-homepage',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'page-1', 
                        'permalink' => 'es-my-awesome-page',
                        'homepage' => false,
                    ),
                )
            ),
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
                        'template' => 'home',
                        'isHome' => true,
                        'published' => true,
                    ),
                    array(
                        'page' => 'page-1',
                        'template' => 'fullpage',
                        'isHome' => false,
                        'published' => true,
                    ),
                    array(
                        'page' => 'page-2',
                        'template' => 'empty',
                        'isHome' => false,
                        'published' => true,
                    ),
                ),
                array(
                    array(
                        'language' => 'en',
                        'page' => 'index', 
                        'permalink' => 'homepage',
                        'homepage' => true,
                    ),
                    array(
                        'language' => 'en',
                        'page' => 'page-1', 
                        'permalink' => 'my-awesome-page',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'en',
                        'page' => 'page-2', 
                        'permalink' => 'my-great-page',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'index', 
                        'permalink' => 'es-homepage',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'page-1', 
                        'permalink' => 'es-my-awesome-page',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'page-2', 
                        'permalink' => 'es-my-great-page',
                        'homepage' => false,
                    ),
                )
            ),  
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
                        'template' => 'home',
                        'isHome' => true,
                        'published' => true,
                    ),
                    array(
                        'page' => 'page-1',
                        'template' => 'fullpage',
                        'isHome' => false,
                        'published' => false,
                    ),
                    array(
                        'page' => 'page-2',
                        'template' => 'fullpage',
                        'isHome' => false,
                        'published' => true,
                    ),
                    array(
                        'page' => 'page-3',
                        'template' => 'empty',
                        'isHome' => false,
                        'published' => true,
                    ),
                ),
                array(
                    array(
                        'language' => 'en',
                        'page' => 'index', 
                        'permalink' => 'homepage',
                        'homepage' => true,
                    ),
                    array(
                        'language' => 'en',
                        'page' => 'page-2', 
                        'permalink' => 'my-great-page',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'en',
                        'page' => 'page-3', 
                        'permalink' => 'another-great-page',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'index', 
                        'permalink' => 'es-homepage',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'page-2', 
                        'permalink' => 'es-my-great-page',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'page-3', 
                        'permalink' => 'another-my-great-page',
                        'homepage' => false,
                    ),
                )
            ),    
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
                        'template' => 'home',
                        'isHome' => true,
                        'published' => true,
                    ),
                    array(
                        'page' => 'page-1',
                        'template' => 'fullpage',
                        'isHome' => false,
                        'published' => true,
                    ),
                    array(
                        'page' => 'page-2',
                        'template' => 'fullpage',
                        'isHome' => false,
                        'published' => true,
                    ),
                    array(
                        'page' => 'page-3',
                        'template' => 'empty',
                        'isHome' => false,
                        'published' => true,
                    ),
                ),
                array(
                    array(
                        'language' => 'en',
                        'page' => 'index', 
                        'permalink' => 'homepage',
                        'homepage' => true,
                    ),
                    array(
                        'language' => 'en',
                        'page' => 'page-1', 
                        'permalink' => 'my-awesome-page',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'en',
                        'page' => 'page-2', 
                        'permalink' => 'my-great-page',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'en',
                        'page' => 'page-3', 
                        'permalink' => 'another-great-page',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'index', 
                        'permalink' => 'es-homepage',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'page-1', 
                        'permalink' => 'es-my-awesome-page',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'page-2', 
                        'permalink' => 'es-my-great-page',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'page-3', 
                        'permalink' => 'another-my-great-page',
                        'homepage' => false,
                    ),
                )
            ),  
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
                        'template' => 'home',
                        'isHome' => true,
                        'published' => true,
                    ),
                    array(
                        'page' => 'page-1',
                        'template' => 'fullpage',
                        'isHome' => false,
                        'published' => true,
                    ),
                    array(
                        'page' => 'page-2',
                        'template' => 'empty',
                        'isHome' => false,
                        'published' => true,
                    ),
                    array(
                        'page' => 'page-3',
                        'template' => 'empty',
                        'isHome' => false,
                        'published' => true,
                    ),
                ),
                array(
                    array(
                        'language' => 'en',
                        'page' => 'index', 
                        'permalink' => 'homepage',
                        'homepage' => true,
                    ),
                    array(
                        'language' => 'en',
                        'page' => 'page-1', 
                        'permalink' => 'my-awesome-page',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'en',
                        'page' => 'page-2', 
                        'permalink' => 'my-great-page',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'en',
                        'page' => 'page-3', 
                        'permalink' => 'another-great-page',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'index', 
                        'permalink' => 'es-homepage',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'page-1', 
                        'permalink' => 'es-my-awesome-page',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'page-2', 
                        'permalink' => 'es-my-great-page',
                        'homepage' => false,
                    ),
                    array(
                        'language' => 'es',
                        'page' => 'page-3', 
                        'permalink' => 'another-my-great-page',
                        'homepage' => false,
                    ),
                )
            ),
        );
    }
}
