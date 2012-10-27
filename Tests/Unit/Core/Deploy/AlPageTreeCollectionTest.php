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

use AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlPageTreeCollection;

/**
 * AlPageTreeCollectionTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlPageTreeCollectionTest extends AlPageTreeCollectionBootstrapper
{
    protected function setUp()
    {
        parent::setUp();

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
    }

    public function testPageTreeCollectionHasBeenPopulated()
    {
        $this->initSomeLangugesAndPages();
        $this->initThemesCollectionWrapper();

        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->onConsecutiveCalls($this->languageRepository, $this->pageRepository, $this->seoRepository,
                    $this->languageRepository, $this->pageRepository, $this->seoRepository,
                    $this->languageRepository, $this->pageRepository, $this->seoRepository,
                    $this->languageRepository, $this->pageRepository, $this->seoRepository,
                    $this->languageRepository, $this->pageRepository, $this->seoRepository));

        $activeTheme = $this->getMock('\AlphaLemon\ThemeEngineBundle\Core\Theme\AlActiveThemeInterface');
        $activeTheme->expects($this->any())
            ->method('getActiveTheme')
            ->will($this->returnValue('BusinessWebsiteTheme'));
        
        $this->container->expects($this->at(0))
            ->method('get')
            ->with('alpha_lemon_cms.themes_collection_wrapper')
            ->will($this->returnValue($this->themesCollectionWrapper));
        
        for ($i = 1; $i < 5; $i++) {
            $this->container->expects($this->at($i))
                ->method('get')
                ->with('alphalemon_theme_engine.active_theme')
                ->will($this->returnValue($activeTheme));   
        }
        
        $pageTreeCollection = new AlPageTreeCollection($this->container, $this->factoryRepository);
        $this->assertEquals(4, count($pageTreeCollection));

        $pageTree = $pageTreeCollection->at(0);
        $this->assertEquals('en', $pageTree->getAlLanguage()->getLanguageName());
        $this->assertEquals('index', $pageTree->getAlPage()->getPageName());

        $pageTree = $pageTreeCollection->at(1);
        $this->assertEquals('en', $pageTree->getAlLanguage()->getLanguageName());
        $this->assertEquals('page-1', $pageTree->getAlPage()->getPageName());

        $pageTree = $pageTreeCollection->at(2);
        $this->assertEquals('es', $pageTree->getAlLanguage()->getLanguageName());
        $this->assertEquals('index', $pageTree->getAlPage()->getPageName());

        $pageTree = $pageTreeCollection->at(3);
        $this->assertEquals('es', $pageTree->getAlLanguage()->getLanguageName());
        $this->assertEquals('page-1', $pageTree->getAlPage()->getPageName());
        
        $this->assertNull($pageTreeCollection->at(4));
        $this->assertEquals(0, $pageTreeCollection->key(0));
    }
    public function testPageTreeCollectionHasSkippedThePagesWhichHasNotToBePublished()
    {
        $this->page1 = $this->setUpPage('index', true);
        $this->page2 = $this->setUpPage('page-1', false, false);
        $this->language1 = $this->setUpLanguage('en', true);
        $this->language2 = $this->setUpLanguage('es');

        $this->initSeoRepository();
        $this->initLanguageRepository();
        $this->languageRepository->expects($this->exactly(2))
            ->method('fromPK')
            ->will($this->onConsecutiveCalls($this->language1, $this->language2));

        $this->initPageRepository();
        $this->pageRepository->expects($this->exactly(2))
            ->method('fromPK')
            ->will($this->returnValue($this->page1));

        $this->initPageBlocks(2);
        $this->initTemplateManager();        
        $this->initThemesCollectionWrapper(2); 
        
        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->onConsecutiveCalls($this->languageRepository, $this->pageRepository, $this->seoRepository,
                    $this->languageRepository, $this->pageRepository, $this->seoRepository,
                    $this->languageRepository, $this->pageRepository, $this->seoRepository,
                    $this->languageRepository, $this->pageRepository, $this->seoRepository,
                    $this->languageRepository, $this->pageRepository, $this->seoRepository));

        $this->container->expects($this->at(0))
            ->method('get')
            ->with('alpha_lemon_cms.themes_collection_wrapper')
            ->will($this->returnValue($this->themesCollectionWrapper));
        
        $activeTheme = $this->getMock('\AlphaLemon\ThemeEngineBundle\Core\Theme\AlActiveThemeInterface');
        $activeTheme->expects($this->any())
            ->method('getActiveTheme')
            ->will($this->returnValue('BusinessWebsiteTheme'));
        
        for ($i = 1; $i < 3; $i++) {
            $this->container->expects($this->at($i))
                ->method('get')
                ->with('alphalemon_theme_engine.active_theme')
                ->will($this->returnValue($activeTheme));   
        }
        
        $pageTreeCollection = new AlPageTreeCollection($this->container, $this->factoryRepository);
        $this->assertEquals(2, count($pageTreeCollection));

        $pageTree = $pageTreeCollection->at(0);
        $this->assertEquals('en', $pageTree->getAlLanguage()->getLanguageName());
        $this->assertEquals('index', $pageTree->getAlPage()->getPageName());
        
        $pageTree = $pageTreeCollection->at(1);
        $this->assertEquals('es', $pageTree->getAlLanguage()->getLanguageName());
        $this->assertEquals('index', $pageTree->getAlPage()->getPageName());
    }
}
