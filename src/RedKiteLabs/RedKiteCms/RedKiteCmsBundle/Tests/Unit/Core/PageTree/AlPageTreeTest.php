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

        $this->template = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->pageContents = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->templateManager->expects($this->once())
            ->method('getTemplate')
            ->will($this->returnValue($this->template));

        $this->templateManager->expects($this->any())
            ->method('getPageBlocks')
            ->will($this->returnValue($this->pageContents));

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
            ->method('getMetaTitle');

        $alSeo->expects($this->once())
            ->method('getMetaDescription');

        $alSeo->expects($this->once())
            ->method('getMetaKeywords');
        
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
        
        $this->container->expects($this->any())
            ->method('get')
            ->will($this->returnValue($request));
        
        $language = $this->configureLanguage();
        $page = $this->setUpPage(2); 
        $theme = $this->configureTheme();
        
        $this->setUpPageBlocks();
        
        $alSeo = $this->setUpSeo(2);  
        
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
            ->will($this->returnValue($page));
        
        $this->seoModel->expects($this->exactly(2))
            ->method('fromPageAndLanguage')
            ->will($this->returnValue($alSeo));
        
        $templateSlots = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface');        
        $this->template->expects($this->any())
            ->method('getTemplateSlots')
            ->will($this->returnValue($templateSlots));
        
        $this->pageTree->setup();
        
        $this->assertEquals($language, $this->pageTree->getAlLanguage());
        $this->assertEquals($page, $this->pageTree->getAlPage());
        $this->assertEquals($theme, $this->pageTree->getAlTheme());
        $this->assertTrue($this->pageTree->isValid());
        $this->assertTrue($this->pageTree->isCmsMode());
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

        return $seo;
    }

    private function setUpPageBlocks()
    {
        $this->pageContents->expects($this->once())
            ->method('setIdLanguage')
            ->will($this->returnSelf());

        $this->pageContents->expects($this->once())
            ->method('setIdPage')
            ->will($this->returnSelf());

        $this->pageContents->expects($this->once())
            ->method('refresh')
            ->will($this->returnSelf());
        
        $this->templateManager->expects($this->once())
            ->method('setPageBlocks')
            ->will($this->returnSelf());

        $this->templateManager->expects($this->once())
            ->method('setTemplateSlots')
            ->will($this->returnSelf());
    }
}