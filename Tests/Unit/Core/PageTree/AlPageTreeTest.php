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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\PageTree;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;
use AlphaLemon\ThemeEngineBundle\Core\Asset\AlAssetCollection;

/**
 * AlPageTreeTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlPageTreeTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $this->templateManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->template = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->pageBlocks = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->templateManager->expects($this->any())
            ->method('getTemplate')
            ->will($this->returnValue($this->template));

        $this->templateManager->expects($this->any())
            ->method('getPageBlocks')
            ->will($this->returnValue($this->pageBlocks));

        $this->languageRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->pageRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->seoRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlSeoRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->activeTheme = $this->getMock('\AlphaLemon\ThemeEngineBundle\Core\Theme\AlActiveThemeInterface');

        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->onConsecutiveCalls($this->languageRepository, $this->pageRepository, $this->seoRepository));

        $this->themesCollectionWrapper = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\ThemesCollectionWrapper\AlThemesCollectionWrapper')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->themesCollectionWrapper->expects($this->any())
            ->method('assignTemplate')
            ->will($this->returnValue($this->templateManager));
    }

    public function testTemplateManagerInjectedBySetters()
    {
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $templateManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->assertEquals($pageTree, $pageTree->setTemplateManager($templateManager));
        $this->assertEquals($templateManager, $pageTree->getTemplateManager());
        $this->assertNotSame($this->templateManager, $pageTree->getTemplateManager());
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

        $blockManager = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\BlockManagerInterface');

        $slotManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager')
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

    public function testLanguageIsFetchedFromLanguageParam()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls('en', false));

        $this->initContainer($request);

        $alLanguage = $this->setUpLanguage(2);
        $this->languageRepository->expects($this->once())
            ->method('fromLanguageName')
            ->will($this->returnValue($alLanguage));

        $this->languageRepository->expects($this->never())
            ->method('fromPK');

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
        $request->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls(2, false));

        $this->initContainer($request);

        $alLanguage = $this->setUpLanguage(2);
        $this->languageRepository->expects($this->never())
            ->method('fromLanguageName');

        $this->languageRepository->expects($this->once())
            ->method('fromPK')
            ->will($this->returnValue($alLanguage));

        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $this->assertNull($pageTree->setup());
        $this->assertEquals($alLanguage, $pageTree->getAlLanguage());
        $this->assertNull($pageTree->getAlPage());
        $this->assertFalse($pageTree->isValid());
    }

    public function testLanguageIsFetchedFromRequest()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->any())
            ->method('get')
            ->will($this->onConsecutiveCalls(null, false));

        $request->expects($this->any())
            ->method('getLocale')
            ->will($this->returnValue('en'));

        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $session->expects($this->any())
            ->method('getLocale')
            ->will($this->returnValue('en'));

        $this->container->expects($this->at(0))
            ->method('get')
            ->with('alphalemon_theme_engine.active_theme')
            ->will($this->returnValue($this->activeTheme));

        $this->container->expects($this->at(1))
            ->method('get')
            ->with('request')
            ->will($this->returnValue($request));

        $this->container->expects($this->at(2))
            ->method('get')
            ->with('session')
            ->will($this->returnValue($session));

        $this->container->expects($this->at(3))
            ->method('get')
            ->with('request')
            ->will($this->returnValue($request));

        $alLanguage = $this->setUpLanguage(2);
        $this->languageRepository->expects($this->once())
            ->method('fromLanguageName')
            ->will($this->returnValue($alLanguage));

        $this->languageRepository->expects($this->never())
            ->method('fromPK');

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
        $request->expects($this->once())
            ->method('get')
            ->will($this->returnValue('en'));

        $this->container->expects($this->at(0))
            ->method('get')
            ->with('alphalemon_theme_engine.active_theme')
            ->will($this->returnValue($this->activeTheme));

        $this->container->expects($this->at(1))
            ->method('get')
            ->with('request')
            ->will($this->returnValue($request));

        $this->seoRepository->expects($this->never())
            ->method('fromPermalink')
            ->will($this->returnValue(null));

        $this->pageRepository->expects($this->never())
            ->method('fromPageName')
            ->will($this->returnValue(null));

        $this->pageRepository->expects($this->never())
            ->method('fromPK')
            ->will($this->returnValue(null));

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
        $request->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls('en', 'index'));

        $this->initContainer($request);
        $this->configureLanguage();

        $this->seoRepository->expects($this->once())
            ->method('fromPermalink')
            ->will($this->returnValue(null));

        $this->pageRepository->expects($this->once())
            ->method('fromPageName')
            ->will($this->returnValue(null));

        $this->pageRepository->expects($this->once())
            ->method('fromPK')
            ->will($this->returnValue(null));

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
        $request->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls('en', 2));

        $this->initContainer($request);
        $this->configureLanguage();

        $this->seoRepository->expects($this->once())
            ->method('fromPermalink')
            ->will($this->returnValue(null));

        $this->pageRepository->expects($this->once())
            ->method('fromPageName')
            ->will($this->returnValue(null));

        $alPage = $this->setUpPage(2);
        $this->pageRepository->expects($this->once())
            ->method('fromPK')
            ->will($this->returnValue($alPage));

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
        $request->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls('en', 'index'));

        $this->initContainer($request);
        $this->configureLanguage();

        $this->seoRepository->expects($this->once())
            ->method('fromPermalink')
            ->will($this->returnValue(null));

        $alPage = $this->setUpPage(2);
        $this->pageRepository->expects($this->once())
            ->method('fromPageName')
            ->will($this->returnValue($alPage));

        $this->pageRepository->expects($this->never())
            ->method('fromPK');

        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $pageTree->setup();
        $this->assertEquals($alPage, $pageTree->getAlPage());
        $this->assertTrue($pageTree->isValid());
    }

    public function testPageIsFetchedFromPermalink()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls('en', 'index'));

        $this->initContainer($request);
        $this->configureLanguage();

        $alPage = $this->setUpPage(2);
        $alSeo = $this->setUpSeo(2);
        $alSeo->expects($this->once())
            ->method('getAlPage')
            ->will($this->returnValue($alPage));

        $this->seoRepository->expects($this->once())
            ->method('fromPermalink')
            ->will($this->returnValue($alSeo));

        $this->pageRepository->expects($this->never())
            ->method('fromPageName');

        $this->pageRepository->expects($this->never())
            ->method('fromPK');

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
        $request->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls('en', 'index'));

        $this->initContainer($request);
        $this->configureLanguage();

        $alPage = $this->setUpPage(2);
        $alSeo = $this->setUpSeo(2);
        $alSeo->expects($this->once())
            ->method('getMetaTitle');

        $alSeo->expects($this->once())
            ->method('getMetaDescription');

        $alSeo->expects($this->once())
            ->method('getMetaKeywords');

        $alSeo->expects($this->once())
            ->method('getAlPage')
            ->will($this->returnValue($alPage));

        $this->seoRepository->expects($this->once())
            ->method('fromPermalink')
            ->will($this->returnValue(null));

        $this->seoRepository->expects($this->once())
            ->method('fromPageAndLanguage')
            ->will($this->returnValue($alSeo));

        $this->pageRepository->expects($this->never())
            ->method('fromPageName');

        $this->pageRepository->expects($this->never())
            ->method('fromPK');

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
        $request->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls('en', 'index'));

        $this->initContainer($request);
        $this->configureLanguage();
        $this->configurePage();

        $this->activeTheme->expects($this->any())
            ->method('getActiveTheme')
            ->will($this->returnValue(null));

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

        $this->container->expects($this->at(0))
            ->method('get')
            ->with('alphalemon_theme_engine.active_theme')
            ->will($this->returnValue($this->activeTheme));

        $this->container->expects($this->at(1))
            ->method('get')
            ->with('request')
            ->will($this->returnValue($request));

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
        $this->assertInstanceOf('\AlphaLemon\ThemeEngineBundle\Core\Theme\AlTheme', $pageTree->getTheme());
        $this->assertInstanceOf('\AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo', $pageTree->getAlSeo());
    }

    public function testPageTreeSetsUpExternalAssetsFromABlock()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
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

        $themeAssets = array('theme-stylesheet.css');
        $this->setUpAssetsCollection($themeAssets);

        $this->initValidPageTree();
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $pageTree->setup();
        $this->assertEquals(array_merge($themeAssets, explode(",", $externalStylesheet)), $pageTree->getExternalStylesheets());
    }

    public function testPageTreeSetsUpInternalAssetsFromABlock()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('getType')
            ->will($this->returnValue('Script'));

        $internalStylesheet = 'fake javascript code';
        $block->expects($this->once())
            ->method('getInternalStylesheet')
            ->will($this->returnValue($internalStylesheet));

        $this->pageBlocks->expects($this->once())
            ->method('getBlocks')
            ->will($this->returnValue(array('logo' => array($block))));

        $themeAssets = array('some code retrieved from template');
        $this->setUpAssetsCollection($themeAssets);

        $this->initValidPageTree();
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $pageTree->setup();
        $this->assertEquals($themeAssets[0] . $internalStylesheet, $pageTree->getInternalStylesheets());
    }

    public function testPageTreeSetsUpExtraAssetsForCurrentTemplate()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('getType')
            ->will($this->returnValue('FancyApp'));

        $this->setUpTemplateAttributes();

        $this->pageBlocks->expects($this->once())
            ->method('getBlocks')
            ->will($this->returnValue(array('logo' => array($block))));

        $this->container->expects($this->exactly(3))
            ->method('hasParameter')
            ->will($this->onConsecutiveCalls(true, false, false));

        $appAssets = array('fake-stylesheet-1.css', 'fake-stylesheet-2.css');
        $this->container->expects($this->any())
            ->method('getParameter')
            ->with('business_website.home.external_stylesheets.cms')
            ->will($this->returnValue($appAssets));

        $themeAssets = array('theme-stylesheet.css');
        $this->setUpAssetsCollection($themeAssets);

        $this->initValidPageTree();
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $pageTree->setup();
        $this->assertEquals(array_merge($themeAssets, $appAssets), $pageTree->getExternalStylesheets());
    }

    public function testPageTreeSetsUpExternalAssetsForCurrentAppBlock()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('getType')
            ->will($this->returnValue('FancyApp'));

        $this->setUpTemplateAttributes();

        $this->pageBlocks->expects($this->once())
            ->method('getBlocks')
            ->will($this->returnValue(array('logo' => array($block))));

        $this->container->expects($this->exactly(3))
            ->method('hasParameter')
            ->will($this->onConsecutiveCalls(false, true, false));

        $appAssets = array('fake-stylesheet-1.css', 'fake-stylesheet-2.css');
        $this->container->expects($this->any())
            ->method('getParameter')
            ->with('fancyapp.external_stylesheets')
            ->will($this->returnValue($appAssets));

        $themeAssets = array('theme-stylesheet.css');
        $this->setUpAssetsCollection($themeAssets);

        $this->initValidPageTree();
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $pageTree->setup();
        $this->assertEquals(array_merge($themeAssets, $appAssets), $pageTree->getExternalStylesheets());
    }

    public function testPageTreeSetsUpExternalAssetsUsedByTheCmsFromTheParameterDeclaredOnTheBlockConfiguration()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('getType')
            ->will($this->returnValue('FancyApp'));

        $this->setUpTemplateAttributes();

        $this->pageBlocks->expects($this->once())
            ->method('getBlocks')
            ->will($this->returnValue(array('logo' => array($block))));

        $this->container->expects($this->exactly(3))
            ->method('hasParameter')
            ->will($this->onConsecutiveCalls(false, false, true));

        $appAssets = array('fake-stylesheet-1.css', 'fake-stylesheet-2.css');
        $this->container->expects($this->any())
            ->method('getParameter')
            ->with('fancyapp.external_stylesheets.cms')
            ->will($this->returnValue($appAssets));

        $themeAssets = array('theme-stylesheet.css');
        $this->setUpAssetsCollection($themeAssets);

        $this->initValidPageTree();
        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $pageTree->setup();
        $this->assertEquals(array_merge($themeAssets, $appAssets), $pageTree->getExternalStylesheets());
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

        $this->seoRepository->expects($this->never())
            ->method('fromPageAndLanguage');

        $this->languageRepository->expects($this->any())
            ->method('fromPK')
            ->will($this->returnValue($this->language));

        $this->pageRepository->expects($this->any())
            ->method('fromPK')
            ->will($this->returnValue($this->page));

        $this->container->expects($this->at(0))
            ->method('get')
            ->with('alphalemon_theme_engine.active_theme')
            ->will($this->returnValue($this->activeTheme));

        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $this->assertNull($pageTree->refresh(2, 2));
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

        $this->container->expects($this->at(0))
            ->method('get')
            ->with('alphalemon_theme_engine.active_theme')
            ->will($this->returnValue($this->activeTheme));

        $pageTree = new AlPageTree($this->container, $this->factoryRepository, $this->themesCollectionWrapper);
        $pageTree->refresh(2, 2);
        $this->assertEquals($this->language, $pageTree->getAlLanguage());
        $this->assertEquals($this->page, $pageTree->getAlPage());
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
        $request->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls('en', 'index'));

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

        $this->seoRepository->expects($this->exactly(2))
            ->method('fromPageAndLanguage')
            ->will($this->returnValue($alSeo));

        $this->languageRepository->expects($this->any())
            ->method('fromPK')
            ->will($this->returnValue($this->language));

        $this->pageRepository->expects($this->any())
            ->method('fromPK')
            ->will($this->returnValue($this->page));
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
        $language = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($returnId));

        return $language;
    }

    protected function setUpPage($returnId)
    {
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $page->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($returnId));

        return $page;
    }

    protected function setUpSeo($returnId)
    {
        $seo = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo');
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
        $this->container->expects($this->at(0))
            ->method('get')
            ->with('alphalemon_theme_engine.active_theme')
            ->will($this->returnValue($this->activeTheme));

        for ($i = 1; $i < 3; $i++) {
            $this->container->expects($this->at($i))
                ->method('get')
                ->with('request')
                ->will($this->returnValue($request));
        }
    }
}