<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\PageTree;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree;
use RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAssetCollection;

/**
 * AlPageTreeTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlPageTreeTest extends TestCase
{
    private $language;
    private $page;
    
    protected function setUp()
    {
        parent::setUp();

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $this->templateManager = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->template = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->pageBlocks = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocks')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->templateManager->expects($this->any())
            ->method('getTemplate')
            ->will($this->returnValue($this->template));

        $this->templateManager->expects($this->any())
            ->method('getPageBlocks')
            ->will($this->returnValue($this->pageBlocks));

        $this->languageRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->pageRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->seoRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlSeoRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->activeTheme = $this->getMock('\RedKiteLabs\ThemeEngineBundle\Core\Theme\AlActiveThemeInterface');

        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->onConsecutiveCalls($this->languageRepository, $this->pageRepository, $this->seoRepository));

        $this->themesCollectionWrapper = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\ThemesCollectionWrapper\AlThemesCollectionWrapper')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->themesCollectionWrapper->expects($this->any())
            ->method('assignTemplate')
            ->will($this->returnValue($this->templateManager));
        
        $this->dispatcher = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
    }

    
    public function testTemplateManagerInjectedBySetters()
    {
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $templateManager = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->assertEquals($pageTree, $pageTree->setTemplateManager($templateManager));
        $this->assertEquals($templateManager, $pageTree->getTemplateManager());
        $this->assertNotSame($this->templateManager, $pageTree->getTemplateManager());
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testAnExceptionIsThrowsWhenCalledMethodDoesNotExist()
    {
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);

        $pageTree->fake();
    }
    
    public function testGetNotInitializedAssets()
    {
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);

        $this->assertEmpty($pageTree->getExternalStylesheets());
    }
    
    public function testMetatags()
    {
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);

        $metas = array(
            'title' => 'An awesome page title',
            'description' => 'An awesome page description',
            'keywords' => 'some,awesome,keywords',
        );
        
        $pageTree->setMetatags($metas);
        $this->assertEquals($metas['title'], $pageTree->getMetaTitle());
        $this->assertEquals($metas['description'], $pageTree->getMetaDescription());
        $this->assertEquals($metas['keywords'], $pageTree->getMetaKeywords());
        
        $title = "another title";
        $pageTree->setMetaTitle($title);
        $this->assertEquals($title, $pageTree->getMetaTitle());
        
        $desription = "another description";
        $pageTree->setMetaDescription($desription);
        $this->assertEquals($desription, $pageTree->getMetaDescription());
        
        $keywords = "another,keyword";
        $pageTree->setMetaKeywords($keywords);
        $this->assertEquals($keywords, $pageTree->getMetaKeywords());
    }
    
    public function testPageBlocks()
    {
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);

        $pageBlock = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocksInterface');
        $this->assertEquals($pageTree, $pageTree->setPageBlocks($pageBlock));
        $this->assertEquals($pageBlock, $pageTree->getPageBlocks());
    }

    public function testGetBlockManagerReturnsAnEmptyArrayWhenTemplateManagerIsNull()
    {
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);

        $this->templateManager->expects($this->never())
            ->method('getSlotmanager');

        $this->assertEquals(array(), $pageTree->getBlockManagers('logo'));
    }

    public function testGetBlockManagerReturnsAnEmptyArrayWhenSlotHasNotBeenFound()
    {
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);

        $this->themesCollectionWrapper->expects($this->once())
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));

        $this->templateManager->expects($this->once())
            ->method('getSlotmanager')
            ->will($this->returnValue(null));

        $this->assertEquals(array(), $pageTree->getBlockManagers('logo'));
    }

    public function testGetBlockManagersReturnsTheBlockManagerSavedOnTheRequiredSlot()
    {
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);

        $this->themesCollectionWrapper->expects($this->once())
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));

        $blockManager = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\BlockManagerInterface');

        $slotManager = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\AlSlotManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $slotManager->expects($this->once())
            ->method('getBlockManagers')
            ->will($this->returnValue(array($blockManager)));

        $this->templateManager->expects($this->once())
            ->method('getSlotmanager')
            ->will($this->returnValue($slotManager));

        $blockManagers = $pageTree->getBlockManagers('logo');
        $this->assertEquals($blockManager, $blockManagers[0]);
    }
    
    public function testLanguageIsFetchedFromLocale()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->at(0))
            ->method('get')
            ->with('page')
            ->will($this->returnValue('index'));
        
        $request->expects($this->at(1))
            ->method('get')
            ->with('languageId')
            ->will($this->returnValue(null));
        
        $request->expects($this->at(2))
            ->method('get')
            ->with('_locale')
            ->will($this->returnValue('en'));

        $this->initContainer($request);

        $alLanguage = $this->setUpLanguage(2);
        $this->languageRepository->expects($this->once())
            ->method('fromLanguageName')
            ->will($this->returnValue($alLanguage));

        $this->languageRepository->expects($this->never())
            ->method('fromPK');

        $this->initEventsDispatcher('page_tree.before_setup');
        
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $this->assertNull($pageTree->setup());
        $this->assertEquals($alLanguage, $pageTree->getAlLanguage());
        $this->assertNull($pageTree->getAlPage());
        $this->assertFalse($pageTree->isValid());
    }

    public function testLanguageIsFetchedFromPrimaryKeyLanguageParam()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $request->expects($this->at(0))
            ->method('get')
            ->with('page')
            ->will($this->returnValue('index'));
        
        $request->expects($this->at(1))
            ->method('get')
            ->with('languageId')
            ->will($this->returnValue(2));

        $this->initContainer($request);

        $alLanguage = $this->setUpLanguage(2);
        $this->languageRepository->expects($this->never())
            ->method('fromLanguageName');

        $this->languageRepository->expects($this->once())
            ->method('fromPK')
            ->will($this->returnValue($alLanguage));

        $this->initEventsDispatcher('page_tree.before_setup');
        
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $this->assertNull($pageTree->setup());
        $this->assertEquals($alLanguage, $pageTree->getAlLanguage());
        $this->assertNull($pageTree->getAlPage());
        $this->assertFalse($pageTree->isValid());
    }

    public function testLanguageIsFetchedFromPermalink()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();        
        $request->expects($this->at(0))
            ->method('get')
            ->with('page')
            ->will($this->returnValue('index'));
        
        $request->expects($this->at(1))
            ->method('get')
            ->with('languageId')
            ->will($this->returnValue(null));
        
        $this->initContainer($request);

        $alLanguage = $this->setUpLanguage(2); 
        $alSeo = $this->setUpSeo(2);
        $alSeo->expects($this->once())
            ->method('getAlLanguage')
            ->will($this->returnValue($alLanguage));

        $this->seoRepository->expects($this->once())
            ->method('fromPermalink')
            ->will($this->returnValue($alSeo));
        
        $this->languageRepository->expects($this->once())
            ->method('fromLanguageName')
            ->will($this->returnValue(null));

        $this->initEventsDispatcher('page_tree.before_setup');
        
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $this->assertNull($pageTree->setup());
        $this->assertEquals($alLanguage, $pageTree->getAlLanguage());
        $this->assertNull($pageTree->getAlPage());
        $this->assertFalse($pageTree->isValid());
    }

    public function testPageIsNotFetchedWhenLanguageIsNull()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->at(0))
            ->method('get')
            ->with('page')
            ->will($this->returnValue('index'));
        
        $request->expects($this->at(1))
            ->method('get')
            ->with('languageId')
            ->will($this->returnValue(null));
        
        $request->expects($this->at(2))
            ->method('get')
            ->with('_locale')
            ->will($this->returnValue(null));

        $this->initContainer($request);

        $this->seoRepository->expects($this->exactly(2))
            ->method('fromPermalink')
            ->will($this->returnValue(null));

        $this->pageRepository->expects($this->never())
            ->method('fromPageName')
            ->will($this->returnValue(null));

        $this->pageRepository->expects($this->never())
            ->method('fromPK')
            ->will($this->returnValue(null));

        $this->initEventsDispatcher('page_tree.before_setup');
        
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $this->assertNull($pageTree->setup());
        $this->assertNull($pageTree->getAlPage());
        $this->assertFalse($pageTree->isValid());
    }

    public function testPageIsNotFetched()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->at(0))
            ->method('get')
            ->with('page')
            ->will($this->returnValue('index'));
        
        $request->expects($this->at(1))
            ->method('get')
            ->with('languageId')
            ->will($this->returnValue(null));
        
        $request->expects($this->at(2))
            ->method('get')
            ->with('_locale')
            ->will($this->returnValue('en'));
        
        $request->expects($this->at(3))
            ->method('get')
            ->with('pageId')
            ->will($this->returnValue(null));

        $this->initContainer($request);
        $this->configureLanguage();

        $this->pageRepository->expects($this->once())
            ->method('fromPageName')
            ->will($this->returnValue(null));

        $this->pageRepository->expects($this->never())
            ->method('fromPK');

        $this->initEventsDispatcher('page_tree.before_setup');
        
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $this->assertNull($pageTree->setup());
        $this->assertNull($pageTree->getAlPage());
        $this->assertFalse($pageTree->isValid());
    }

    public function testPageIsFetchedFromPrimaryKey()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->at(0))
            ->method('get')
            ->with('page')
            ->will($this->returnValue('index'));
        
        $request->expects($this->at(1))
            ->method('get')
            ->with('languageId')
            ->will($this->returnValue(null));
        
        $request->expects($this->at(2))
            ->method('get')
            ->with('_locale')
            ->will($this->returnValue('en'));
        
        $request->expects($this->at(3))
            ->method('get')
            ->with('pageId')
            ->will($this->returnValue(2));

        $this->initContainer($request);
        $this->configureLanguage();

        $this->pageRepository->expects($this->never())
            ->method('fromPageName');

        $alPage = $this->setUpPage(2);
        $this->pageRepository->expects($this->once())
            ->method('fromPK')
            ->will($this->returnValue($alPage));

        $this->initEventsDispatcher('page_tree.before_setup');
        
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $pageTree->setup();
        $this->assertEquals($alPage, $pageTree->getAlPage());
        $this->assertTrue($pageTree->isValid());
    }

    public function testPageIsFetchedFromPageName()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->at(0))
            ->method('get')
            ->with('page')
            ->will($this->returnValue('index'));
        
        $request->expects($this->at(1))
            ->method('get')
            ->with('languageId')
            ->will($this->returnValue(null));
        
        $request->expects($this->at(2))
            ->method('get')
            ->with('_locale')
            ->will($this->returnValue('en'));
        
        $request->expects($this->at(3))
            ->method('get')
            ->with('pageId')
            ->will($this->returnValue(null));

        $this->initContainer($request);
        $this->configureLanguage();

        $alPage = $this->setUpPage(2);
        $this->pageRepository->expects($this->once())
            ->method('fromPageName')
            ->will($this->returnValue($alPage));

        $this->pageRepository->expects($this->never())
            ->method('fromPK');

        $this->initEventsDispatcher('page_tree.before_setup');
        
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $pageTree->setup();
        $this->assertEquals($alPage, $pageTree->getAlPage());
        $this->assertTrue($pageTree->isValid());
    }

    // url: /backend/en/homepage 
    public function testPageIsFetchedFromPermalink()
    {   
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->at(0))
            ->method('get')
            ->with('page')
            ->will($this->returnValue('homepage'));
        
        $request->expects($this->at(1))
            ->method('get')
            ->with('languageId')
            ->will($this->returnValue(null));

        $this->initContainer($request);

        $alPage = $this->setUpPage(2);
        $alLanguage = $this->setUpLanguage(2); 
        $alSeo = $this->setUpSeo(2);
        $alSeo->expects($this->once())
            ->method('getAlLanguage')
            ->will($this->returnValue($alLanguage));
        
        $alSeo->expects($this->once())
            ->method('getAlPage')
            ->will($this->returnValue($alPage));

        $this->seoRepository->expects($this->once())
            ->method('fromPermalink')
            ->with('homepage')
            ->will($this->returnValue($alSeo));

        $this->pageRepository->expects($this->never())
            ->method('fromPageName');

        $this->pageRepository->expects($this->never())
            ->method('fromPK');

        $this->initEventsDispatcher('page_tree.before_setup');
        
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $pageTree->setup();
        $this->assertEquals($alPage, $pageTree->getAlPage());
        $this->assertTrue($pageTree->isValid());
    }
    
    // url: /backend/homepage
    public function testPageIsFetchedFromPermalinkWhenLanguageIsNotProvidedInrequestedUrl()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->at(0))
            ->method('get')
            ->with('page')
            ->will($this->returnValue('index'));
        
        $request->expects($this->at(1))
            ->method('get')
            ->with('languageId')
            ->will($this->returnValue(null));
            
        $request->expects($this->at(2))
            ->method('get')
            ->with('_locale')
            ->will($this->returnValue(null));
            
        $request->expects($this->at(3))
            ->method('get')
            ->with('_locale')
            ->will($this->returnValue('homepage'));

        $this->initContainer($request);

        $alPage = $this->setUpPage(2);
        $alLanguage = $this->setUpLanguage(2); 
        $alSeo = $this->setUpSeo(2);
        $alSeo->expects($this->once())
            ->method('getAlLanguage')
            ->will($this->returnValue($alLanguage));
        
        $alSeo->expects($this->once())
            ->method('getAlPage')
            ->will($this->returnValue($alPage));

        $this->seoRepository->expects($this->at(0))
            ->method('fromPermalink')
            ->with('index')
            ->will($this->returnValue(null));
            
        $this->seoRepository->expects($this->at(1))
            ->method('fromPermalink')
            ->with('homepage')
            ->will($this->returnValue($alSeo));

        $this->pageRepository->expects($this->never())
            ->method('fromPageName');

        $this->pageRepository->expects($this->never())
            ->method('fromPK');

        $this->initEventsDispatcher('page_tree.before_setup');
        
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $pageTree->setup();
        $this->assertEquals($alPage, $pageTree->getAlPage());
        $this->assertTrue($pageTree->isValid());
    }

    public function testPageIsFetchedFromSeo()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->at(0))
            ->method('get')
            ->with('page')
            ->will($this->returnValue('index'));
        
        $request->expects($this->at(1))
            ->method('get')
            ->with('languageId')
            ->will($this->returnValue(null));
        
        $request->expects($this->at(2))
            ->method('get')
            ->with('_locale')
            ->will($this->returnValue('en'));

        $this->initContainer($request);
        $this->configureLanguage();

        $alPage = $this->setUpPage(2);
        $alSeo = $this->setUpSeo(2);

        $alSeo->expects($this->once())
            ->method('getAlPage')
            ->will($this->returnValue($alPage));
        
        $alSeo->expects($this->once())
            ->method('getMetaTitle');

        $alSeo->expects($this->once())
            ->method('getMetaDescription');

        $alSeo->expects($this->once())
            ->method('getMetaKeywords');

        $this->seoRepository->expects($this->once())
            ->method('fromPageAndLanguage')
            ->will($this->returnValue($alSeo));

        $this->pageRepository->expects($this->never())
            ->method('fromPageName');

        $this->pageRepository->expects($this->never())
            ->method('fromPK');

        $this->initEventsDispatcher('page_tree.before_setup');
        
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $pageTree->setup();
        $this->assertEquals($alPage, $pageTree->getAlPage());
        $this->assertTrue($pageTree->isValid());
    }

    public function testPageTreeHasNotBeenSetBecauseAnyThemeHasBeenFetched()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->at(0))
            ->method('get')
            ->with('page')
            ->will($this->returnValue('index'));
        
        $request->expects($this->at(1))
            ->method('get')
            ->with('languageId')
            ->will($this->returnValue(null));
        
        $request->expects($this->at(2))
            ->method('get')
            ->with('_locale')
            ->will($this->returnValue('en'));

        $this->initContainer($request);
        $this->configureLanguage();
        $this->configurePage();

        $this->activeTheme->expects($this->any())
            ->method('getActiveTheme')
            ->will($this->returnValue(null));

        $this->initEventsDispatcher('page_tree.before_setup');
        
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $this->assertNull($pageTree->setup());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMEssage Something goes wrong retrieving a routing parameter
     */
    public function testAnUnespectedExceptionHasBeenThown()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->once())
            ->method('get')
            ->will($this->throwException(new \RuntimeException('Something goes wrong retrieving a routing parameter')));

        $this->initContainer($request);

        $this->initEventsDispatcher('page_tree.before_setup');
        
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $this->assertNull($pageTree->setup());
    }

    public function testPageTreeHasBeenSet()
    {
        $this->initValidPageTree();
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $pageTree->setup();
        $this->assertEquals($this->language, $pageTree->getAlLanguage());
        $this->assertEquals($this->page, $pageTree->getAlPage());
        $this->assertTrue($pageTree->isValid());
        $this->assertTrue($pageTree->isCmsMode());
        $this->assertInstanceOf('\RedKiteLabs\ThemeEngineBundle\Core\Theme\AlTheme', $pageTree->getTheme());
        $this->assertInstanceOf('\RedKiteLabs\RedKiteCmsBundle\Model\AlSeo', $pageTree->getAlSeo());
    }
    
    public function tesASlotNotInTemplateIsIgnore()
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('getType')
            ->will($this->returnValue('Script'));

        $externalStylesheet = 'fake-stylesheet-1.css,fake-stylesheet-2.css';
        $block->expects($this->once())
            ->method('getExternalStylesheet')
            ->will($this->returnValue($externalStylesheet));

        $this->pageBlocks->expects($this->once())
            ->method('getBlocks')
            ->will($this->returnValue(array('logo' => array($block))));
        
        $this->template->expects($this->once())
            ->method('getSlots')
            ->will($this->returnValue(array('nav-menu' => 'slot')));

        $themeAssets = array('theme-stylesheet.css');
        $this->setUpAssetsCollection($themeAssets);

        $this->initValidPageTree();
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $pageTree->setup();
        $this->assertEquals($themeAssets, $pageTree->getExternalStylesheets());
    }

    /**
     * @dataProvider fetchAssets
     */
    public function testPageTreeSetsUpExternalAssetsFromABlock($availableBlocks, $blocksAssets, $result, $externalListeners = array(), $orphanSlots = array())
    {        
        $this->initValidPageTree();
        
        $this->blocksManagerFactory->expects($this->once())
            ->method('getAvailableBlocks')
            ->will($this->returnValue($availableBlocks));
        
        $listeners = array();
        $listenerAssets = array();
        if ( ! empty($externalListeners)) {
            $listeners = $externalListeners['listener'];
            $listenerAssets = $externalListeners['assets'];
            
            $this->language->expects($this->once())
                ->method('getLanguageName')
                ->will($this->returnValue($externalListeners['language']))
            ;
            
            $this->page->expects($this->once())
                ->method('getPageName')
                ->will($this->returnValue($externalListeners['page']))
            ;
        }
        
        $this->container->expects($this->at(5))
            ->method('get')
            ->with('red_kite_labs_theme_engine.registed_listeners')
            ->will($this->returnValue($listeners));
        
        $startIndex = 6;
        $this->checkAssets($listenerAssets, $startIndex, true);        
        $this->checkAssets($blocksAssets, $startIndex);
        
        if ( ! empty($orphanSlots)) {            
            $this->pageBlocks->expects($this->once())
                ->method('getBlocks')
                ->will($this->returnValue($orphanSlots['blocks']));
            
            $this->template->expects($this->once())
                ->method('getSlots')
                ->will($this->returnValue($orphanSlots['slots'])); //
        }

        $themeAssets = array('theme-stylesheet.css');
        $this->setUpAssetsCollection($themeAssets);

        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $pageTree->setup();
        $this->assertEquals($result, $pageTree->getExternalStylesheets());
    }

    public function testPageTreeHasNotBeenRefreshedBecauseThemeIsNull()
    {
        $this->activeTheme->expects($this->once())
            ->method('getActiveTheme')
            ->will($this->returnValue(null));

        $this->language = $this->setUpLanguage(2);
        $this->page = $this->setUpPage(2);

        $this->themesCollectionWrapper->expects($this->never())
            ->method('assignTemplate');

        $this->seoRepository->expects($this->once())
            ->method('fromPageAndLanguage')
            ->will($this->returnValue($this->setUpSeo(2)));

        $this->languageRepository->expects($this->any())
            ->method('fromPK')
            ->will($this->returnValue($this->language));

        $this->pageRepository->expects($this->any())
            ->method('fromPK')
            ->will($this->returnValue($this->page));
        
        $this->initContainer(null);

        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $this->assertSame($pageTree,  $pageTree->refresh(2, 2));
        $this->assertNull($pageTree->getTheme());
    }

    public function testPageTreeHasBeenRefreshed()
    {
        $this->configureTheme();
        $this->language = $this->setUpLanguage(2);
        $this->page = $this->setUpPage(2);
        $alSeo = $this->setUpSeo(2);
        $this->setUpPageBlocks();

        $alSeo->expects($this->once())
            ->method('getMetaTitle');

        $alSeo->expects($this->once())
            ->method('getMetaDescription');

        $alSeo->expects($this->once())
            ->method('getMetaKeywords');

        $this->seoRepository->expects($this->once())
            ->method('fromPageAndLanguage')
            ->will($this->returnValue($alSeo));

        $this->languageRepository->expects($this->any())
            ->method('fromPK')
            ->will($this->returnValue($this->language));

        $this->pageRepository->expects($this->any())
            ->method('fromPK')
            ->will($this->returnValue($this->page));

        $this->initContainer(null);

        $this->initEventsDispatcher('page_tree.before_refresh', 'page_tree.after_refresh');
        
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $pageTree->refresh(2, 2);
        $this->assertEquals($this->language, $pageTree->getAlLanguage());
        $this->assertEquals($this->page, $pageTree->getAlPage());
    }
    
    public function fetchAssets()
    {        
        return array(        
            // Image block has any external asset
            array(
                array('Image'),
                array(
                    'image.external_stylesheets' => array(                        
                        'global' => array(
                            'exists' => false,
                        ),  
                    ),
                ),
                array(
                    'theme-stylesheet.css',
                ),
            ),    
            // Image block has an external asset
            array(
                array('Image'),
                array(
                    'image.external_stylesheets' => array(                        
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet.css',
                            ),
                        ),  
                    ),
                ),
                array(
                    'theme-stylesheet.css',
                    'image-stylesheet.css',
                ),
            ),    
            // An asset is not added twice
            array(
                array('Image'),
                array(
                    'image.external_stylesheets' => array(                        
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet.css',
                                'image-stylesheet.css',
                            ),
                        ),  
                    ),
                ),
                array(
                    'theme-stylesheet.css',
                    'image-stylesheet.css',
                ),
            ),    
            // Image block has any external cms asset
            array(
                array('Image'),
                array(
                    'image.external_stylesheets' => array(                        
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet.css'
                            ),
                        ),                        
                        'cms' => array(
                            'exists' => false,
                        ),
                    ),
                ),
                array(
                    'theme-stylesheet.css',
                    'image-stylesheet.css',
                ),
            ),
            // Image block has an external cms asset
            array(
                array('Image'),
                array(
                    'image.external_stylesheets' => array(                        
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet.css'
                            ),
                        ),                        
                        'cms' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet-cms.css'
                            ),
                        ),
                    ),
                ),
                array(
                    'theme-stylesheet.css',
                    'image-stylesheet.css',
                    'image-stylesheet-cms.css',
                ),
            ),
            // An asset is not added twice
            array(
                array('Image'),
                array(
                    'image.external_stylesheets' => array(                        
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet.css'
                            ),
                        ),                        
                        'cms' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet.css'
                            ),
                        ),
                    ),
                ),
                array(
                    'theme-stylesheet.css',
                    'image-stylesheet.css',
                ),
            ),
            // Added two assets
            array(
                array('Image'),
                array(
                    'image.external_stylesheets' => array(
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet.css', 
                                'image-stylesheet-1.css',
                            ),
                        ),
                    ),
                ),
                array(
                    'theme-stylesheet.css',
                    'image-stylesheet.css', 
                    'image-stylesheet-1.css',
                ),
            ),
            // Added assets from different blocks
            array(
                array('Image', 'Text'),
                array(
                    'image.external_stylesheets' => array(
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet.css', 
                                'image-stylesheet-1.css',
                            ),
                        ),
                    ),
                    'text.external_stylesheets' => array(
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'text-stylesheet.css',
                            ),
                        ),
                    ),
                ),
                array(
                    'theme-stylesheet.css',
                    'image-stylesheet.css', 
                    'image-stylesheet-1.css',
                    'text-stylesheet.css',
                ),
            ),
            // Added assets and cms assets from different blocks 
            array(
                array('Image', 'Text'),
                array(
                    'image.external_stylesheets' => array(
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet.css', 
                                'image-stylesheet-1.css',
                            ),
                        ),
                    ),
                    'text.external_stylesheets' => array(
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'text-stylesheet.css',
                            ),
                        ),
                        'cms' => array(
                            'exists' => true,
                            'assets' => array(
                                'text-stylesheet-cms.css',
                            ),
                        ),
                    ),
                ),
                array(
                    'theme-stylesheet.css',
                    'image-stylesheet.css', 
                    'image-stylesheet-1.css',
                    'text-stylesheet.css',
                    'text-stylesheet-cms.css',
                ),
            ),
            // An asset is not added twice
            array(
                array('Image', 'Text'),
                array(
                    'image.external_stylesheets' => array(
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet.css',  // Same assets
                                'image-stylesheet-1.css',
                            ),
                        ),
                    ),
                    'text.external_stylesheets' => array(
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet.css', // Same assets
                            ),
                        ),
                    ),
                ),
                array(
                    'theme-stylesheet.css',
                    'image-stylesheet.css', 
                    'image-stylesheet-1.css',
                ),
            ),
            // Adding assets from one listener
            array(
                array('Image'),
                array(
                    'image.external_stylesheets' => array(
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet.css', 
                                'image-stylesheet-1.css',
                            ),
                        ),
                    ),
                ),
                array(
                    'theme-stylesheet.css',
                    'site-listener-stylesheet.css',
                    'image-stylesheet.css', 
                    'image-stylesheet-1.css',
                ),
                array(
                    'listener' => array('demo'),
                    'language' => 'en',                    
                    'page' => 'index',
                    'assets' => array(
                        'demo.page.external_stylesheets' => array(
                            'global' => array(
                                'exists' => true,
                                'assets' =>  array('site-listener-stylesheet.css'),
                            ),
                        ),
                        'demo.en.external_stylesheets' => array(                            
                            'global' => array(
                                'exists' => false,
                            ),
                        ),
                        'demo.index.external_stylesheets' => array(                            
                            'global' => array(
                                'exists' => false,
                            ),
                        ),
                    ),
                ),
            ),
            // Adding assets from all listeners
            array(
                array('Image'),
                array(
                    'image.external_stylesheets' => array(
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet.css', 
                                'image-stylesheet-1.css',
                            ),
                        ),
                    ),
                ),
                array(
                    'theme-stylesheet.css',
                    'site-listener-stylesheet.css',
                    'language-listener-stylesheet.css',
                    'page-listener-stylesheet.css',
                    'image-stylesheet.css', 
                    'image-stylesheet-1.css',
                ),
                array(
                    'listener' => array('demo'),
                    'language' => 'en',                    
                    'page' => 'index',
                    'assets' => array(
                        'demo.page.external_stylesheets' => array(
                            'global' => array(
                                'exists' => true,
                                'assets' =>  array('site-listener-stylesheet.css'),
                            ),
                        ),
                        'demo.en.external_stylesheets' => array(                            
                            'global' => array(
                                'exists' => true,
                                'assets' =>  array('language-listener-stylesheet.css'),
                            ),
                        ),
                        'demo.index.external_stylesheets' => array(                            
                            'global' => array(
                                'exists' => true,
                                'assets' =>  array('page-listener-stylesheet.css'),
                            ),
                        ),
                    ),
                ),
            ),
            // Assets are not added when orphan slot does not match
            array(
                array('Image'),
                array(
                    'image.external_stylesheets' => array(
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet.css', 
                                'image-stylesheet-1.css',
                            ),
                        ),
                    ),
                ),
                array(
                    'theme-stylesheet.css',
                    'image-stylesheet.css', 
                    'image-stylesheet-1.css',
                ),
                array(),
                array(
                    'slots' => array(
                        'logo1' => ''
                    ),
                    'blocks' => array(
                        'logo' => array($this->setUpBlock('getExternalStylesheet', 'fake-stylesheet-1.css,fake-stylesheet-2.css')),
                    ),
                ),
            ),
            // Assets are added when orphan slot matches
            array(
                array('Image'),
                array(
                    'image.external_stylesheets' => array(
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet.css', 
                                'image-stylesheet-1.css',
                            ),
                        ),
                    ),
                ),
                array(
                    'theme-stylesheet.css',
                    'image-stylesheet.css', 
                    'image-stylesheet-1.css',
                    'fake-stylesheet-1.css',
                    'fake-stylesheet-2.css',
                ),
                array(),
                array(
                    'slots' => array(
                        'logo' => ''
                    ),
                    'blocks' => array(
                        'logo' => array($this->setUpBlock('getExternalStylesheet', 'fake-stylesheet-1.css,fake-stylesheet-2.css')),
                    ),
                ),
            ),
            //full case
            array(
                array('Image', 'Text'),
                array(
                    'image.external_stylesheets' => array(
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet.css', 
                            ),
                        ),
                        'cms' => array(
                            'exists' => true,
                            'assets' => array(
                                'image-stylesheet-cms.css',
                            ),
                        ),
                    ),
                    'text.external_stylesheets' => array(
                        'global' => array(
                            'exists' => true,
                            'assets' => array(
                                'text-stylesheet.css',
                            ),
                        ),
                        'cms' => array(
                            'exists' => true,
                            'assets' => array(
                                'text-stylesheet-cms.css',
                            ),
                        ),
                    ),
                ),
                array(
                    'theme-stylesheet.css',
                    'site-listener-stylesheet.css',
                    'language-listener-stylesheet.css',
                    'page-listener-stylesheet.css',
                    'image-stylesheet.css', 
                    'image-stylesheet-cms.css', 
                    'text-stylesheet.css', 
                    'text-stylesheet-cms.css', 
                    'fake-stylesheet-1.css',
                    'fake-stylesheet-2.css',
                ),
                array(
                    'listener' => array('demo'),
                    'language' => 'en',                    
                    'page' => 'index',
                    'assets' => array(
                        'demo.page.external_stylesheets' => array(
                            'global' => array(
                                'exists' => true,
                                'assets' =>  array('site-listener-stylesheet.css'),
                            ),
                        ),
                        'demo.en.external_stylesheets' => array(                            
                            'global' => array(
                                'exists' => true,
                                'assets' =>  array('language-listener-stylesheet.css'),
                            ),
                        ),
                        'demo.index.external_stylesheets' => array(                            
                            'global' => array(
                                'exists' => true,
                                'assets' =>  array('page-listener-stylesheet.css'),
                            ),
                        ),
                    ),
                ),
                array(
                    'slots' => array(
                        'logo' => ''
                    ),
                    'blocks' => array(
                        'logo' => array($this->setUpBlock('getExternalStylesheet', 'fake-stylesheet-1.css,fake-stylesheet-2.css')),
                    ),
                ),
            ),
        );
    }
    
    private function setUpBlock($method, $externalStylesheet, $type = 'Script')
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('getType')
            ->will($this->returnValue($type));
        
        $block->expects($this->once())
            ->method($method)
            ->will($this->returnValue($externalStylesheet)); 
        
        return $block;
    }
    
    private function setUpAssetsCollection(array $storedAssets)
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $assetsCollection = new AlAssetCollection($kernel, $storedAssets);
        $this->template->expects($this->any())
            ->method('__call')
            ->will($this->returnValue($assetsCollection));
    }

    private function initValidPageTree()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->at(0))
            ->method('get')
            ->with('page')
            ->will($this->returnValue('index'));
        
        $request->expects($this->at(1))
            ->method('get')
            ->with('languageId')
            ->will($this->returnValue(null));
        
        $request->expects($this->at(2))
            ->method('get')
            ->with('_locale')
            ->will($this->returnValue('en'));

        $this->initContainer($request);

        $this->language = $this->configureLanguage(2);
        $this->page = $this->setUpPage(2);
        $this->configureTheme();
        $alSeo = $this->setUpSeo(2);
        $this->setUpPageBlocks();

        // Two times because the first one is when the page is setted up from seo
        // then the second one when the page is refreshed
        $alSeo->expects($this->exactly(2))
            ->method('getMetaTitle');

        $alSeo->expects($this->exactly(2))
            ->method('getMetaDescription');

        $alSeo->expects($this->exactly(2))
            ->method('getMetaKeywords');

        $alSeo->expects($this->once())
            ->method('getAlPage')
            ->will($this->returnValue($this->page));

        $this->seoRepository->expects($this->once())
            ->method('fromPageAndLanguage')
            ->will($this->returnValue($alSeo));

        $this->languageRepository->expects($this->any())
            ->method('fromPK')
            ->will($this->returnValue($this->language));

        $this->pageRepository->expects($this->any())
            ->method('fromPK')
            ->will($this->returnValue($this->page));
        
        $this->initEventsDispatcher('page_tree.before_setup', 'page_tree.after_setup');
    }

    private function configureLanguage()
    {
        $alLanguage = $this->setUpLanguage(2);
        $this->languageRepository->expects($this->once())
            ->method('fromLanguageName')
            ->will($this->returnValue($alLanguage));

        return $alLanguage;
    }

    private function configurePage()
    {
        $alPage = $this->setUpPage(2);
        $this->pageRepository->expects($this->once())
            ->method('fromPageName')
            ->will($this->returnValue($alPage));

        return $alPage;
    }

    private function configureTheme()
    {
        $this->activeTheme->expects($this->once())
            ->method('getActiveTheme')
            ->will($this->returnValue('BusinessWebsiteTheme'));
    }

    protected function setUpLanguage($returnId)
    {
        $language = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage');
        $language->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($returnId));

        return $language;
    }

    protected function setUpPage($returnId)
    {
        $page = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlPage');
        $page->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($returnId));

        return $page;
    }

    protected function setUpSeo($returnId)
    {
        $seo = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlSeo');
        $seo->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($returnId));

        return $seo;
    }

    private function setUpPageBlocks()
    {
        $this->pageBlocks->expects($this->once())
            ->method('setIdLanguage')
            ->will($this->returnSelf());

        $this->pageBlocks->expects($this->once())
            ->method('setIdPage')
            ->will($this->returnSelf());

        $this->pageBlocks->expects($this->once())
            ->method('refresh')
            ->will($this->returnSelf());

        $this->templateManager->expects($this->once())
            ->method('setPageBlocks')
            ->will($this->returnSelf());
    }

    private function setUpTemplateAttributes()
    {
        $this->template->expects($this->once())
            ->method('getThemeName')
            ->will($this->returnValue('BusinessWebsiteThemeBundle'));

        $this->template->expects($this->once())
            ->method('getTemplateName')
            ->will($this->returnValue('home'));
    }

    private function initContainer($request)
    {
        $this->blocksManagerFactory = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactory')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->container->expects($this->at(0))
            ->method('get')
            ->with('event_dispatcher')
            ->will($this->returnValue($this->dispatcher));
        
        $this->container->expects($this->at(1))
            ->method('get')
            ->with('red_kite_cms.block_manager_factory')
            ->will($this->returnValue($this->blocksManagerFactory));
        
        $this->container->expects($this->at(2))
            ->method('get')
            ->with('red_kite_labs_theme_engine.active_theme')
            ->will($this->returnValue($this->activeTheme));
        
        if (null !== $request) {
            $this->container->expects($this->at(3))
                ->method('get')
                ->with('request')
                ->will($this->returnValue($request));
        }
    }
    
    private function initEventsDispatcher($beforeEvent = null, $afterEvent = null)
    {
        if (null !== $beforeEvent) {
            $this->dispatcher->expects($this->at(0))
                    ->method('dispatch')
                    ->with($beforeEvent);
        }
        
        if (null !== $afterEvent) {
            $this->dispatcher->expects($this->at(1))
                    ->method('dispatch')
                    ->with($afterEvent);
        }
    }
    
    private function initRegistedListeners($listeners = array())
    {
        $this->container->expects($this->at(4))
            ->method('get')
            ->with('red_kite_labs_theme_engine.registed_listeners')
            ->will($this->returnValue($listeners['listener']));
    }
    
    private function checkAssets($assets, &$startIndex, $ignoreCms = false)
    {
        foreach($assets as $parameter => $asset) {
            
            $globalAsset = $asset['global']; 
            $assetDeclared = $globalAsset['exists'];
            $this->container->expects($this->at($startIndex))
                ->method('hasParameter')
                ->with($parameter)
                ->will($this->returnValue($assetDeclared));
            
            $startIndex++;
            if ($assetDeclared) {
                $this->container->expects($this->at($startIndex))
                    ->method('getParameter')
                    ->with($parameter)
                    ->will($this->returnValue($globalAsset['assets']));
                $startIndex++;
            }
            
            if ( ! $ignoreCms) {
                if (array_key_exists('cms', $asset)) {
                    $parameter .= '.cms';
                    $cmsAsset = $asset['cms']; 
                    $assetDeclared = $cmsAsset['exists'];
                    $this->container->expects($this->at($startIndex))
                        ->method('hasParameter')
                        ->with($parameter)
                        ->will($this->returnValue($assetDeclared));

                    $startIndex++;
                    if ($assetDeclared) {
                        $this->container->expects($this->at($startIndex))
                            ->method('getParameter')
                            ->with($parameter)
                            ->will($this->returnValue($cmsAsset['assets']));
                        $startIndex++;
                    }
                } else {
                    $startIndex++;
                }
            }
        }
    }
}
