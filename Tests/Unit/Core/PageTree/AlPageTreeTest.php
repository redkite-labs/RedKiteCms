<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\PageTree;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;

/**
 * AlPageTreeTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlPageTreeTest extends TestCase
{
    private $pageTree;

    protected function setUp()
    {
        parent::setUp();

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $this->templateManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->languageModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlLanguageModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->pageModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlPageModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->themeModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlThemeModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->seoModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlSeoModelPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->pageTree = new AlPageTree($this->container, $this->templateManager, $this->languageModel, $this->pageModel, $this->themeModel, $this->seoModel);
    }

    public function testLanguageIsFetchedFromLanguageParam()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls('en', false));

        $this->container->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValue($request));

        $alLanguage = $this->setUpLanguage(2);
        $this->languageModel->expects($this->once())
            ->method('fromLanguageName')
            ->will($this->returnValue($alLanguage));

        $this->languageModel->expects($this->never())
            ->method('fromPK');

        $this->assertNull($this->pageTree->setup());
        $this->assertEquals($alLanguage, $this->pageTree->getAlLanguage());
        $this->assertNull($this->pageTree->getAlPage());
        $this->assertFalse($this->pageTree->isValid());
    }

    public function testLanguageIsFetchedFromPrimaryKeyLanguageParam()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls(2, false));

        $this->container->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValue($request));

        $alLanguage = $this->setUpLanguage(2);
        $this->languageModel->expects($this->never())
            ->method('fromLanguageName');

        $this->languageModel->expects($this->once())
            ->method('fromPK')
            ->will($this->returnValue($alLanguage));

        $this->assertNull($this->pageTree->setup());
        $this->assertEquals($alLanguage, $this->pageTree->getAlLanguage());
        $this->assertNull($this->pageTree->getAlPage());
        $this->assertFalse($this->pageTree->isValid());
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

        $this->container->expects($this->exactly(3))
            ->method('get')
            ->will($this->onConsecutiveCalls($request, $session, $request));

        $alLanguage = $this->setUpLanguage(2);
        $this->languageModel->expects($this->once())
            ->method('fromLanguageName')
            ->will($this->returnValue($alLanguage));

        $this->languageModel->expects($this->never())
            ->method('fromPK');

        $this->assertNull($this->pageTree->setup());
        $this->assertEquals($alLanguage, $this->pageTree->getAlLanguage());
        $this->assertNull($this->pageTree->getAlPage());
        $this->assertFalse($this->pageTree->isValid());
    }

    public function testPageIsNotFetchedWhenLanguageIsNull()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->once())
            ->method('get')
            ->will($this->returnValue('en'));

        $this->container->expects($this->once())
            ->method('get')
            ->will($this->returnValue($request));

        $this->seoModel->expects($this->never())
            ->method('fromPermalink')
            ->will($this->returnValue(null));

        $this->pageModel->expects($this->never())
            ->method('fromPageName')
            ->will($this->returnValue(null));

        $this->pageModel->expects($this->never())
            ->method('fromPK')
            ->will($this->returnValue(null));

        $this->assertNull($this->pageTree->setup());
        $this->assertNull($this->pageTree->getAlPage());
        $this->assertFalse($this->pageTree->isValid());
    }

    public function testPageIsNotFetched()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls('en', 'index'));

        $this->container->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValue($request));

        $this->configureLanguage();

        $this->seoModel->expects($this->once())
            ->method('fromPermalink')
            ->will($this->returnValue(null));

        $this->pageModel->expects($this->once())
            ->method('fromPageName')
            ->will($this->returnValue(null));

        $this->pageModel->expects($this->once())
            ->method('fromPK')
            ->will($this->returnValue(null));

        $this->assertNull($this->pageTree->setup());
        $this->assertNull($this->pageTree->getAlPage());
        $this->assertFalse($this->pageTree->isValid());
    }

    public function testPageIsFetchedFromPrimaryKey()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls('en', 2));

        $this->container->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValue($request));

        $this->configureLanguage();

        $this->seoModel->expects($this->once())
            ->method('fromPermalink')
            ->will($this->returnValue(null));

        $this->pageModel->expects($this->once())
            ->method('fromPageName')
            ->will($this->returnValue(null));

        $alPage = $this->setUpPage(2);
        $this->pageModel->expects($this->once())
            ->method('fromPK')
            ->will($this->returnValue($alPage));

        $this->pageTree->setup();
        $this->assertEquals($alPage, $this->pageTree->getAlPage());
        $this->assertTrue($this->pageTree->isValid());
    }

    public function testPageIsFetchedFromPageName()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls('en', 'index'));

        $this->container->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValue($request));

        $this->configureLanguage();

        $this->seoModel->expects($this->once())
            ->method('fromPermalink')
            ->will($this->returnValue(null));

        $alPage = $this->setUpPage(2);
        $this->pageModel->expects($this->once())
            ->method('fromPageName')
            ->will($this->returnValue($alPage));

        $this->pageModel->expects($this->never())
            ->method('fromPK');

        $this->pageTree->setup();
        $this->assertEquals($alPage, $this->pageTree->getAlPage());
        $this->assertTrue($this->pageTree->isValid());
    }

    public function testPageIsFetchedFromPermalink()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls('en', 'index'));

        $this->container->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValue($request));

        $this->configureLanguage();

        $alPage = $this->setUpPage(2);
        $alSeo = $this->setUpSeo(2);
        $alSeo->expects($this->once())
            ->method('getAlPage')
            ->will($this->returnValue($alPage));

        $this->seoModel->expects($this->once())
            ->method('fromPermalink')
            ->will($this->returnValue($alSeo));

        $this->pageModel->expects($this->never())
            ->method('fromPageName');

        $this->pageModel->expects($this->never())
            ->method('fromPK');

        $this->pageTree->setup();
        $this->assertEquals($alPage, $this->pageTree->getAlPage());
        $this->assertTrue($this->pageTree->isValid());
    }

    public function testPageIsFetchedFromSeo()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls('en', 'index'));

        $this->container->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValue($request));

        $this->configureLanguage();

        $alPage = $this->setUpPage(2);
        $alSeo = $this->setUpSeo(2);
        $alSeo->expects($this->once())
            ->method('getAlPage')
            ->will($this->returnValue($alPage));

        $this->seoModel->expects($this->once())
            ->method('fromPermalink')
            ->will($this->returnValue(null));

        $this->seoModel->expects($this->once())
            ->method('fromPageAndLanguage')
            ->will($this->returnValue($alSeo));

        $this->pageModel->expects($this->never())
            ->method('fromPageName');

        $this->pageModel->expects($this->never())
            ->method('fromPK');

        $this->pageTree->setup();
        $this->assertEquals($alPage, $this->pageTree->getAlPage());
        $this->assertTrue($this->pageTree->isValid());
    }

    public function testPageTreeHasNotBeenSettedBecauseAnyThemeHasBeenFetched()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls('en', 'index'));

        $this->container->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValue($request));

        $this->configureLanguage();
        $this->configurePage();

        $this->themeModel->expects($this->once())
            ->method('activeBackend')
            ->will($this->returnValue(null));

        $this->assertNull($this->pageTree->setup());
    }

    public function testPageTreeHasBeenSetted()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $request->expects($this->exactly(2))
            ->method('get')
            ->will($this->onConsecutiveCalls('en', 'index'));

        $slots = array('logo' => 'fake', 'content' => 'fake');
        $templateSlots = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface');
        $templateSlots->expects($this->once())
            ->method('getSlots')
            ->will($this->returnValue($slots));

        $templateSlotsFactory = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsFactoryInterface');
        $templateSlotsFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($templateSlots));

        $bundle = $this->getMock('Symfony\Component\HttpKernel\Bundle\BundleInterface');
        $bundle->expects($this->exactly(2))
            ->method('getName')
            ->will($this->returnValue('FakeBundle'));

        $kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $kernel->expects($this->exactly(2))
            ->method('getBundles')
            ->will($this->returnValue(array($bundle)));

        $this->container->expects($this->any())
            ->method('get')
            ->will($this->onConsecutiveCalls($request, $request, $templateSlotsFactory, $kernel, $kernel));

        $this->container->expects($this->any())
            ->method('hasParameter')
            ->will($this->returnValue(true));

        $this->container->expects($this->any())
            ->method('getParameter')
            ->will($this->onConsecutiveCalls('internal javascript',                     // Internal javascript setted from the cms
                                             'internal stylesheet',                     // Internal stylesheet setted from the cms
                                             array('external javascript'),              // External javascript setted from the cms
                                             array('external stylesheet'),              // External stylesheet setted from the cms
                                             array('theme external javascript'),        // External javascript setted as parameter by the theme
                                             array('theme external stylesheet'),        // External stylesheet setted as parameter by the theme
                                             array('template external javascript'),     // External javascript setted as parameter by the template
                                             array('template external stylesheet'),     // External stylesheet setted as parameter by the template
                                             array('cms_external javascript'),          // External javascript setted as parameter by the cms
                                             array('cms_external stylesheet'),          // External stylesheet setted as parameter for the cms
                                             array('cms_theme external javascript'),    // External javascript setted as parameter by the theme for the cms
                                             array('cms_theme external stylesheet'),    // External stylesheet setted as parameter by the theme for the cms
                                             array('cms_template external javascript'), // External javascript setted as parameter by the template for the cms
                                             array('cms_template external stylesheet')  // External stylesheet setted as parameter by the template for the cms
                                             ));

        $blocks = array('logo' => array(array('HtmlContent' => 'fake')),
                        'content' => array(array('HtmlContent' => 'fake content 1'),
                                           array('HtmlContent' => 'fake content 2')));
        $this->templateManager->expects($this->once())
            ->method('slotsToArray')
            ->will($this->returnValue($blocks));

        $this->setUpPageContentsContainer();
        $language = $this->configureLanguage();
        $page = $this->configurePage();
        $page->expects($this->once())
            ->method('getTemplateName')
            ->will($this->returnValue('Home'));
        $theme = $this->configureTheme();

        $this->pageTree->setup();
        $this->assertEquals('FakeTheme', $this->pageTree->getThemeName());
        $this->assertEquals('Home', $this->pageTree->getTemplateName());
        $this->assertEquals($slots, $this->pageTree->getSlots());
        $this->assertEquals('internal javascript', $this->pageTree->getInternalJavascript());
        $this->assertEquals('internal stylesheet', $this->pageTree->getInternalStylesheet());
        $this->assertEquals(array('external javascript', 'theme external javascript', 'template external javascript', 'cms_external javascript', 'cms_theme external javascript', 'cms_template external javascript'), $this->pageTree->getExternalJavascripts());
        $this->assertEquals(array('external stylesheet', 'theme external stylesheet', 'template external stylesheet', 'cms_external stylesheet', 'cms_theme external stylesheet', 'cms_template external stylesheet'), $this->pageTree->getExternalStylesheets());
        $this->assertEquals($blocks, $this->pageTree->getContents());
        $this->assertEquals($this->templateManager, $this->pageTree->getTemplateManager());
        $this->assertEquals($language, $this->pageTree->getAlLanguage());
        $this->assertEquals($page, $this->pageTree->getAlPage());
        $this->assertEquals($theme, $this->pageTree->getAlTheme());
        $this->assertTrue($this->pageTree->isCmsMode());

        return $this->pageTree;
    }

    /**
     * @depends testPageTreeHasBeenSetted
     *
     * To avoid continuosly setting up of PageTree object
     */
    public function testAddANewBlockToPageTree1($pageTree)
    {
        $blocks = $pageTree->getContents();

        $newBlock = array('HtmlContent' => 'Another fake block');
        $pageTree->addBlock('logo', $newBlock);

        $blocks['logo'][] = $newBlock;

        $this->assertEquals($blocks, $pageTree->getContents());
    }

    /**
     * @depends testPageTreeHasBeenSetted
     */
    public function testAddANewBlockWithAllAttributesToPageTree($pageTree)
    {
        $blocks = $pageTree->getContents();
        $internalJavascript = $pageTree->getInternalJavascript();
        $internalStylesheet = $pageTree->getInternalStylesheet();
        $externalJavascript = $pageTree->getExternalJavascripts();
        $externalStylesheet = $pageTree->getExternalStylesheets();


        $newBlock = array('HtmlContent' => 'Another fake block',
                          'ExternalJavascript' => 'Another fake external javascript',
                          'InternalJavascript' => 'Another fake internal javascript',
                          'ExternalStylesheet' => 'Another fake external stylesheet',
                          'InternalStylesheet' => 'Another fake internal stylesheet',);
        $pageTree->addBlock('logo', $newBlock);

        $blocks['logo'][] = $newBlock;
        $externalJavascript[] = $newBlock['ExternalJavascript'];
        $externalStylesheet[] = $newBlock['ExternalStylesheet'];

        $this->assertEquals($blocks, $pageTree->getContents());
        $this->assertEquals($internalJavascript . $newBlock['InternalJavascript'], $pageTree->getInternalJavascript());
        $this->assertEquals($internalStylesheet . $newBlock['InternalStylesheet'], $pageTree->getInternalStylesheet());
        $this->assertEquals($externalJavascript, $pageTree->getExternalJavascripts());
        $this->assertEquals($externalStylesheet, $pageTree->getExternalStylesheets());
    }

    /**
     * @depends testPageTreeHasBeenSetted
     */
    public function testAddANewExternalJavascript($pageTree)
    {
        $externalJavascripts = $pageTree->getExternalJavascripts();

        $asset = 'new fake external javascript';
        $pageTree->addJavascript($asset);

        $externalJavascripts[] = $asset;

        $this->assertEquals($externalJavascripts, $pageTree->getExternalJavascripts());
    }

    /**
     * @depends testPageTreeHasBeenSetted
     */
    public function testAnExternalJavascriptCannotBeAddedMoreThanOnce($pageTree)
    {
        $externalJavascripts = $pageTree->getExternalJavascripts();

        $asset = 'fake external javascript added once';
        $pageTree->addJavascript($asset);
        $pageTree->addJavascript($asset);
        $externalJavascripts[] = $asset;

        $this->assertEquals($externalJavascripts, $pageTree->getExternalJavascripts());
    }

    /**
     * @depends testPageTreeHasBeenSetted
     */
    public function testAddANewExternalStylesheet($pageTree)
    {
        $externalStylesheets = $pageTree->getExternalStylesheets();

        $asset = 'new fake stylesheet javascript';
        $pageTree->addStylesheet($asset);
        $externalStylesheets[] = $asset;

        $this->assertEquals($externalStylesheets, $pageTree->getExternalStylesheets());
    }

    /**
     * @depends testPageTreeHasBeenSetted
     */
    public function testAnExternalStylesheetCannotBeAddedMoreThanOnce($pageTree)
    {
        $externalStylesheets = $pageTree->getExternalStylesheets();

        $asset = 'fake external stylesheet added once';
        $pageTree->addStylesheet($asset);
        $pageTree->addStylesheet($asset);
        $externalStylesheets[] = $asset;

        $this->assertEquals($externalStylesheets, $pageTree->getExternalStylesheets());
    }

    private function configureLanguage()
    {
        $alLanguage = $this->setUpLanguage(2);
        $this->languageModel->expects($this->once())
            ->method('fromLanguageName')
            ->will($this->returnValue($alLanguage));

        return $alLanguage;
    }

    private function configurePage()
    {
        $alPage = $this->setUpPage(2);
        $this->pageModel->expects($this->once())
            ->method('fromPageName')
            ->will($this->returnValue($alPage));

        return $alPage;
    }

    private function configureTheme()
    {
        $theme = $this->setUpTheme();
        $this->themeModel->expects($this->once())
            ->method('activeBackend')
            ->will($this->returnValue($theme));

        return $theme;
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

    protected function setUpTheme()
    {
        $theme = $this->getMock('AlphaLemon\ThemeEngineBundle\Model\AlTheme');
        $theme->expects($this->once())
            ->method('getThemeName')
            ->will($this->returnValue('FakeTheme'));

        return $theme;
    }

    protected function setUpSeo($returnId)
    {
        $seo = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo');
        $seo->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($returnId));

        $seo->expects($this->once())
            ->method('getMetaTitle');

        $seo->expects($this->once())
            ->method('getMetaDescription');

        $seo->expects($this->once())
            ->method('getMetaKeywords');

        return $seo;
    }

    private function setUpPageContentsContainer()
    {
        $pageContents = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageContentsContainer\AlPageContentsContainer')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $pageContents->expects($this->once())
            ->method('setIdLanguage')
            ->will($this->returnSelf());

        $pageContents->expects($this->once())
            ->method('setIdPage')
            ->will($this->returnSelf());

        $pageContents->expects($this->once())
            ->method('refresh')
            ->will($this->returnSelf());

        $this->templateManager->expects($this->once())
            ->method('getPageContentsContainer')
            ->will($this->returnValue($pageContents));

        $this->templateManager->expects($this->once())
            ->method('setPageContentsContainer')
            ->will($this->returnSelf());

        $this->templateManager->expects($this->once())
            ->method('setTemplateSlots')
            ->will($this->returnSelf());
    }
}