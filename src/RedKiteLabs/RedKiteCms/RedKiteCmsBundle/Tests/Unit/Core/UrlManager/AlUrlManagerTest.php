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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\UrlManager;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\UrlManager\AlUrlManager;

/**
 * AlUrlManager
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlUrlManagerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $this->factory = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
    }

    public function testInternalUrlIsNullWhenTheRequestedLanguageFromLanguageNameHasNotBeenFound1()
    {
        $seo = $this->setUpSeo('my-awesome-permalink');
        $this->seoRepository = $this->setUpSeoRepository('fromPageAndLanguage', $seo);

        $this->factory->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->seoRepository));

        $urlManager = new AlUrlManager($this->kernel, $this->factory);
        $this->assertEquals($urlManager, $urlManager->buildInternalUrl(array('en'), 'index'));
        $this->assertNull($urlManager->getPermalink());
        $this->assertNull($urlManager->getInternalUrl());
        $this->assertNull($urlManager->getProductionRoute());
        $this->assertEquals('The given parameter to fetch a language is not valid', $urlManager->getError());
    }

    public function testInternalUrlIsNullWhenTheRequestedLanguageFromLanguageNameHasNotBeenFound12()
    {
        $seo = $this->setUpSeo('my-awesome-permalink');
        $this->seoRepository = $this->setUpSeoRepository('fromPageAndLanguage', $seo);

        $language = $this->setUpLanguage();
        $languageRepository = $this->setUpLanguageRepository('fromLanguageName', $language);

        $this->factory->expects($this->any())
            ->method('createRepository')
            ->will($this->onConsecutiveCalls($this->seoRepository, $languageRepository));

        $urlManager = new AlUrlManager($this->kernel, $this->factory);
        $this->assertEquals($urlManager, $urlManager->buildInternalUrl('en', array('index')));
        $this->assertNull($urlManager->getPermalink());
        $this->assertNull($urlManager->getInternalUrl());
        $this->assertNull($urlManager->getProductionRoute());
        $this->assertEquals('The given parameter to fetch a page is not valid', $urlManager->getError());
    }

    public function testInternalUrlIsNullWhenTheRequestedLanguageFromLanguageNameHasNotBeenFound()
    {
        $seo = $this->setUpSeo('my-awesome-permalink');
        $this->seoRepository = $this->setUpSeoRepository('fromPageAndLanguage', $seo);

        $languageRepository = $this->setUpLanguageRepository('fromLanguageName', null);

        $this->factory->expects($this->any())
            ->method('createRepository')
            ->will($this->onConsecutiveCalls($this->seoRepository, $languageRepository));

        $urlManager = new AlUrlManager($this->kernel, $this->factory);
        $this->assertEquals($urlManager, $urlManager->buildInternalUrl('fake', 'index'));
        $this->assertNull($urlManager->getPermalink());
        $this->assertNull($urlManager->getInternalUrl());
        $this->assertNull($urlManager->getProductionRoute());
        $this->assertEquals('The requested language has not been found', $urlManager->getError());
    }

    public function testInternalUrlIsNullWhenTheRequestedLanguageFromPrimaryKeyHasNotBeenFound()
    {
        $seo = $this->setUpSeo('my-awesome-permalink');
        $this->seoRepository = $this->setUpSeoRepository('fromPageAndLanguage', $seo);

        $languageRepository = $this->setUpLanguageRepository('fromPk', null);

        $this->factory->expects($this->any())
            ->method('createRepository')
            ->will($this->onConsecutiveCalls($this->seoRepository, $languageRepository));

        $urlManager = new AlUrlManager($this->kernel, $this->factory);
        $this->assertEquals($urlManager, $urlManager->buildInternalUrl(9999, 'index'));
        $this->assertNull($urlManager->getPermalink());
        $this->assertNull($urlManager->getInternalUrl());
        $this->assertNull($urlManager->getProductionRoute());
        $this->assertEquals('The requested language has not been found', $urlManager->getError());
    }

    public function testInternalUrlIsNullWhenTheRequestedPageFromPageNameHasNotBeenFound()
    {
        $seo = $this->setUpSeo('my-awesome-permalink');
        $this->seoRepository = $this->setUpSeoRepository('fromPageAndLanguage', $seo);

        $language = $this->setUpLanguage();
        $languageRepository = $this->setUpLanguageRepository('fromLanguageName', $language);

        $pageRepository = $this->setUpPageRepository('fromPageName', null);

        $this->factory->expects($this->any())
            ->method('createRepository')
            ->will($this->onConsecutiveCalls($this->seoRepository, $languageRepository, $pageRepository));

        $urlManager = new AlUrlManager($this->kernel, $this->factory);
        $this->assertEquals($urlManager, $urlManager->buildInternalUrl('en', 'fake'));
        $this->assertNull($urlManager->getPermalink());
        $this->assertNull($urlManager->getInternalUrl());
        $this->assertNull($urlManager->getProductionRoute());
        $this->assertEquals('The requested page has not been found', $urlManager->getError());
    }

    public function testInternalUrlIsNullWhenTheRequestedPageFromPrimaryKeyHasNotBeenFound()
    {
        $seo = $this->setUpSeo('my-awesome-permalink');
        $this->seoRepository = $this->setUpSeoRepository('fromPageAndLanguage', $seo);

        $language = $this->setUpLanguage();
        $languageRepository = $this->setUpLanguageRepository('fromLanguageName', $language);

        $pageRepository = $this->setUpPageRepository('fromPK', null);

        $this->factory->expects($this->any())
            ->method('createRepository')
            ->will($this->onConsecutiveCalls($this->seoRepository, $languageRepository, $pageRepository));

        $urlManager = new AlUrlManager($this->kernel, $this->factory);
        $this->assertEquals($urlManager, $urlManager->buildInternalUrl('en', 9999));
        $this->assertNull($urlManager->getPermalink());
        $this->assertNull($urlManager->getInternalUrl());
        $this->assertNull($urlManager->getProductionRoute());
        $this->assertEquals('The requested page has not been found', $urlManager->getError());
    }

    public function testInternalUrlIsNotBuiltWhenSeoIsNull()
    {
        $this->seoRepository = $this->setUpSeoRepository('fromPageAndLanguage', null);

        $this->factory->expects($this->once())
            ->method('createRepository')
            ->will($this->returnValue($this->seoRepository));

        $language = $this->setUpLanguage(2);
        $page = $this->setUpPage(2);

        $urlManager = new AlUrlManager($this->kernel, $this->factory);
        $this->assertEquals($urlManager, $urlManager->buildInternalUrl($language, $page));
        $this->assertNull($urlManager->getPermalink());
        $this->assertNull($urlManager->getInternalUrl());
        $this->assertNull($urlManager->getProductionRoute());
    }

    public function testInternalUrlIsBuiltFromPageAndLanguageObjects()
    {
        $this->setUpKernel();

        $seo = $this->setUpSeo('my-awesome-permalink');
        $this->seoRepository = $this->setUpSeoRepository('fromPageAndLanguage', $seo);

        $this->factory->expects($this->once())
            ->method('createRepository')
            ->will($this->returnValue($this->seoRepository));

        $language = $this->setUpLanguage(2, 'en');
        $page = $this->setUpPage(2, 'index');

        $urlManager = new AlUrlManager($this->kernel, $this->factory);
        $this->assertEquals($urlManager, $urlManager->buildInternalUrl($language, $page));
        $this->assertEquals('my-awesome-permalink', $urlManager->getPermalink());
        $this->assertEquals('/al_cms.php/backend/my-awesome-permalink', $urlManager->getInternalUrl());
        $this->assertEquals('_en_index', $urlManager->getProductionRoute());
    }

    public function testInternalUrlIsBuiltFromPageAndLanguageName()
    {
        $this->setUpKernel();

        $seo = $this->setUpSeo('my-awesome-permalink');
        $this->seoRepository = $this->setUpSeoRepository('fromPageAndLanguage', $seo);

        $language = $this->setUpLanguage(2, 'en');
        $languageRepository = $this->setUpLanguageRepository('fromLanguageName', $language);

        $page = $this->setUpPage(2, 'index');
        $pageRepository = $this->setUpPageRepository('fromPageName', $page);

        $this->factory->expects($this->any())
            ->method('createRepository')
            ->will($this->onConsecutiveCalls($this->seoRepository, $languageRepository, $pageRepository));

        $urlManager = new AlUrlManager($this->kernel, $this->factory);
        $this->assertEquals($urlManager, $urlManager->buildInternalUrl('en', 'index'));
        $this->assertEquals('my-awesome-permalink', $urlManager->getPermalink());
        $this->assertEquals('/al_cms.php/backend/my-awesome-permalink', $urlManager->getInternalUrl());
        $this->assertEquals('_en_index', $urlManager->getProductionRoute());
    }

    public function testInternalUrlIsBuiltFromPageAndLanguageIds()
    {
        $this->setUpKernel();

        $seo = $this->setUpSeo('my-awesome-permalink');
        $this->seoRepository = $this->setUpSeoRepository('fromPageAndLanguage', $seo);

        $language = $this->setUpLanguage(2, 'en');
        $languageRepository = $this->setUpLanguageRepository('fromPK', $language);

        $page = $this->setUpPage(2, 'index');
        $pageRepository = $this->setUpPageRepository('fromPK', $page);

        $this->factory->expects($this->any())
            ->method('createRepository')
            ->will($this->onConsecutiveCalls($this->seoRepository, $languageRepository, $pageRepository));

        $urlManager = new AlUrlManager($this->kernel, $this->factory);
        $this->assertEquals($urlManager, $urlManager->buildInternalUrl(2, 2));
        $this->assertEquals('my-awesome-permalink', $urlManager->getPermalink());
        $this->assertEquals('/al_cms.php/backend/my-awesome-permalink', $urlManager->getInternalUrl());
        $this->assertEquals('_en_index', $urlManager->getProductionRoute());
    }

    public function testInternalUrlIsBuiltWhenPageAndLanguageAreMixed()
    {
        $this->setUpKernel();

        $seo = $this->setUpSeo('my-awesome-permalink');
        $this->seoRepository = $this->setUpSeoRepository('fromPageAndLanguage', $seo);

        $language = $this->setUpLanguage(2, 'en');
        $languageRepository = $this->setUpLanguageRepository('fromLanguageName', $language);

        $page = $this->setUpPage(2, 'index');
        $pageRepository = $this->setUpPageRepository('fromPK', $page);

        $this->factory->expects($this->any())
            ->method('createRepository')
            ->will($this->onConsecutiveCalls($this->seoRepository, $languageRepository, $pageRepository));

        $urlManager = new AlUrlManager($this->kernel, $this->factory);
        $this->assertEquals($urlManager, $urlManager->buildInternalUrl('en', 2));
        $this->assertEquals('my-awesome-permalink', $urlManager->getPermalink());
        $this->assertEquals('/al_cms.php/backend/my-awesome-permalink', $urlManager->getInternalUrl());
        $this->assertEquals('_en_index', $urlManager->getProductionRoute());
    }

    public function testFromUrlDoesNothigWhenTheGivenUrlIsNotAString()
    {
        $seo = $this->setUpSeo('my-awesome-permalink');
        $this->seoRepository = $this->setUpSeoRepository('fromPermalink', $seo);

        $this->factory->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->seoRepository));

        $urlManager = new AlUrlManager($this->kernel, $this->factory);
        $urlManager->fromUrl(null);
        $this->assertNull($urlManager->getPermalink());
        $this->assertNull($urlManager->getInternalUrl());
        $this->assertNull($urlManager->getProductionRoute());
        $this->assertEquals('The url parameter must be a string', $urlManager->getError());

        $this->assertEquals($urlManager, $urlManager->fromUrl(array('fake')));
        $this->assertNull($urlManager->getPermalink());
        $this->assertNull($urlManager->getInternalUrl());
        $this->assertNull($urlManager->getProductionRoute());
        $this->assertEquals('The url parameter must be a string', $urlManager->getError());
    }

    public function testFromUrlDoesNothigWhenSeoIsNull()
    {
        $this->seoRepository = $this->setUpSeoRepository('fromPermalink', null);

        $this->factory->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->seoRepository));

        $urlManager = new AlUrlManager($this->kernel, $this->factory);
        $this->assertEquals($urlManager, $urlManager->fromUrl('my-awesome-permalink'));
        $this->assertNull($urlManager->getPermalink());
        $this->assertNull($urlManager->getInternalUrl());
        $this->assertNull($urlManager->getProductionRoute());
    }

    public function testInitializedFromPermalink()
    {
        $this->setUpKernel();

        $language = $this->setUpLanguage(null, 'en');
        $page = $this->setUpPage(null, 'index');
        $seo = $this->setUpSeo('my-awesome-permalink', $language, $page);
        $this->seoRepository = $this->setUpSeoRepository('fromPermalink', $seo);

        $this->factory->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->seoRepository));

        $urlManager = new AlUrlManager($this->kernel, $this->factory);
        $this->assertEquals($urlManager, $urlManager->fromUrl('my-awesome-permalink'));
        $this->assertEquals('my-awesome-permalink', $urlManager->getPermalink());
        $this->assertEquals('/al_cms.php/backend/my-awesome-permalink', $urlManager->getInternalUrl());
        $this->assertEquals('_en_index', $urlManager->getProductionRoute());
    }

    public function testInitializedFromInternalUrl()
    {
        $this->setUpKernel();

        $language = $this->setUpLanguage(null, 'en');
        $page = $this->setUpPage(null, 'index');
        $seo = $this->setUpSeo('my-awesome-permalink', $language, $page);
        $this->seoRepository = $this->setUpSeoRepository('fromPermalink', $seo);

        $this->factory->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->seoRepository));

        $urlManager = new AlUrlManager($this->kernel, $this->factory);
        $this->assertEquals($urlManager, $urlManager->fromUrl('/al_cms.php/backend/my-awesome-permalink'));
        $this->assertEquals('my-awesome-permalink', $urlManager->getPermalink());
        $this->assertEquals('/al_cms.php/backend/my-awesome-permalink', $urlManager->getInternalUrl());
        $this->assertEquals('_en_index', $urlManager->getProductionRoute());
    }

    public function testFromUrlDoesNotFetchAnyInformationWhenTheUrlPointsAnExternalWebsite()
    {
        $this->setUpKernel();

        $seo = $this->setUpSeo('my-awesome-permalink');
        $this->seoRepository = $this->setUpSeoRepository('fromPermalink', $seo);

        $this->factory->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->seoRepository));

        $urlManager = new AlUrlManager($this->kernel, $this->factory);
        $this->assertEquals($urlManager, $urlManager->fromUrl('http://example.com'));
        $this->assertNull($urlManager->getPermalink());
        $this->assertNull($urlManager->getInternalUrl());
        $this->assertNull($urlManager->getProductionRoute());
    }

    public function testFromUrlDoesNotFetchAnyInformationWhenTheUrlIsUndefined()
    {
        $this->setUpKernel();

        $seo = $this->setUpSeo('my-awesome-permalink');
        $this->seoRepository = $this->setUpSeoRepository('fromPermalink', $seo);

        $this->factory->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->seoRepository));

        $urlManager = new AlUrlManager($this->kernel, $this->factory);
        $this->assertEquals($urlManager, $urlManager->fromUrl('#'));
        $this->assertNull($urlManager->getPermalink());
        $this->assertNull($urlManager->getInternalUrl());
        $this->assertNull($urlManager->getProductionRoute());
    }

    protected function setUpKernel()
    {
        $this->kernel->expects($this->once())
            ->method('getEnvironment')
            ->will($this->returnValue('al_cms'));
    }

    protected function setUpSeoRepository($method, $seo)
    {
        $seoRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlSeoRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $seoRepository->expects($this->any())
            ->method($method)
            ->will($this->returnValue($seo));

        return $seoRepository;
    }

    protected function setUpLanguageRepository($method, $language)
    {
        $languageRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $languageRepository->expects($this->once())
            ->method($method)
            ->will($this->returnValue($language));

        return $languageRepository;
    }

    protected function setUpPageRepository($method, $page)
    {
        $pageRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $pageRepository->expects($this->once())
            ->method($method)
            ->will($this->returnValue($page));

        return $pageRepository;
    }

    protected function setUpSeo($permalink, $language = null, $page = null)
    {
        $seo = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo');
        $seo->expects($this->any())
            ->method('getPermalink')
            ->will($this->returnValue($permalink));

        if (null !== $language && null !== $page) {
            $seo->expects($this->once())
                ->method('getAlLanguage')
                ->will($this->returnValue($language));

            $seo->expects($this->once())
                ->method('getAlPage')
                ->will($this->returnValue($page));
        }

        return $seo;
    }

    protected function setUpLanguage($returnId = null, $languageName = null)
    {
        $language = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        if (null !== $returnId) {
            $language->expects($this->once())
                ->method('getId')
                ->will($this->returnValue($returnId));
        }

        if (null !== $languageName) {
            $language->expects($this->once())
                ->method('getLanguage')
                ->will($this->returnValue($languageName));
        }

        return $language;
    }

    protected function setUpPage($returnId = null, $pageName = null)
    {
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        if (null !== $returnId) {
            $page->expects($this->once())
                ->method('getId')
                ->will($this->returnValue($returnId));
        }

        if (null !== $pageName) {
            $page->expects($this->once())
                ->method('getPageName')
                ->will($this->returnValue($pageName));
        }

        return $page;
    }
}
