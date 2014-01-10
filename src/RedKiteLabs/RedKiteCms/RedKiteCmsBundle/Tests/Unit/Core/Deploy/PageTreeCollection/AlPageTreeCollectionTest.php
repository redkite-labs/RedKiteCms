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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Deploy\PageTreeCollection;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\PageTreeCollection\AlPageTreeCollection;

/**
 * AlPageTreeCollectionTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlPageTreeCollectionTest extends TestCase
{
    protected function setUp()
    {
        $this->assetsManager = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\PageTree\TemplateAssetsManager\TemplateAssetsManager')
                                        ->disableOriginalConstructor()
                                        ->getMock();
        $this->assetsManager->expects($this->any())
            ->method('withExtraAssets')
            ->will($this->returnSelf())
        ;
        
        $this->templateManager = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager')
                                        ->disableOriginalConstructor()
                                        ->getMock();        
        $this->pageBlocks = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocks')
                                        ->disableOriginalConstructor()
                                        ->getMock();  
        
        $this->languageRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface');
        $this->pageRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\PageRepositoryInterface');
        $this->seoRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\SeoRepository')
                                        ->setMethods(array('fromPageAndLanguage'))
                                        ->disableOriginalConstructor()
                                        ->getMock();
        $this->blocksRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\BlocksRepositoryInterface', array('retrieveContents'));        
       
        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->at(0))
            ->method('createRepository')
            ->with('Language')
            ->will($this->returnValue($this->languageRepository))
        ;
        
        $this->factoryRepository->expects($this->at(1))
            ->method('createRepository')
            ->with('Page')
            ->will($this->returnValue($this->pageRepository))
        ;
        
        $this->factoryRepository->expects($this->at(2))
            ->method('createRepository')
            ->with('Block')
            ->will($this->returnValue($this->blocksRepository))
        ;
        
        $themeSlots = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\AlThemeSlotsInterface'); 
        $this->theme = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Theme\AlTheme')
                                        ->disableOriginalConstructor()
                                        ->getMock();
        $this->theme
            ->expects($this->atLeastOnce())
            ->method('getThemeSlots')
            ->will($this->returnValue($themeSlots))
        ;
        
        $this->activeTheme = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\ActiveTheme\AlActiveThemeInterface');
        $this->activeTheme->expects($this->once())
            ->method('getActiveTheme')
            ->will($this->returnValue($this->theme))
        ;
    }
    
    /**
     * @dataProvider pageTreeCollectionRepository
     */
    public function testPageTreeCollection($languages, $pages, $templates, $expectedlanguages, $expectedPages)
    {
        $this->configureLanguagesRepository($languages);
        $this->configurePagesRepository($pages);
        $this->configureBlocksRepository();
        $this->configureSeoRepository($languages, $pages);
        $this->configureTheme($templates);
        
        $pageTreeCollection = new AlPageTreeCollection($this->assetsManager, $this->activeTheme, $this->templateManager, $this->pageBlocks, $this->factoryRepository);
        $pageTreeCollection->fill();
        $this->assertCount($expectedlanguages, $pageTreeCollection->getPages());
        $this->assertCount($expectedPages, $pageTreeCollection->getBasePages());
    }
    
    public function pageTreeCollectionRepository()
    {
        return array(
            array(
                array(
                    $this->createLanguage('en'),
                ),
                array(
                    $this->createPage('index'),
                ),
                array(
                    $this->createTemplate(),
                ),
                1,
                1,
            ),
            array(
                array(
                    $this->createLanguage('en'),
                ),
                array(
                    $this->createPage('index'),
                    $this->createPage('internal'),
                ),
                array(
                    $this->createTemplate(),
                ),
                2,
                1,
            ),
            array(
                array(
                    $this->createLanguage('en'),
                ),
                array(
                    $this->createPage('index', false),
                    $this->createPage('internal'),
                ),
                array(
                    $this->createTemplate(),
                ),
                1,
                1,
            ),
            array(
                array(
                    $this->createLanguage('en'),
                    $this->createLanguage('it'),
                ),
                array(
                    $this->createPage('index'),
                    $this->createPage('internal'),
                ),
                array(
                    $this->createTemplate(),
                ),
                4,
                2,
            ),
            array(
                array(
                    $this->createLanguage('en'),
                    $this->createLanguage('it'),
                ),
                array(
                    $this->createPage('index'),
                    $this->createPage('internal', false),
                ),
                array(
                    $this->createTemplate(),
                ),
                2,
                2,
            ),
            array(
                array(
                    $this->createLanguage('en'),
                ),
                array(
                    $this->createPage('index'),
                    $this->createPage('internal'),
                ),
                array(
                    $this->createTemplate(),
                    $this->createTemplate(),
                ),
                2,
                2,
            ),
            array(
                array(
                    $this->createLanguage('en'),
                    $this->createLanguage('it'),
                ),
                array(
                    $this->createPage('index'),
                    $this->createPage('internal'),
                ),
                array(
                    $this->createTemplate(),
                    $this->createTemplate(),
                ),
                4,
                4,
            ),
            array(
                array(
                    $this->createLanguage('en'),
                    $this->createLanguage('it'),
                ),
                array(
                    $this->createPage('index'),
                    $this->createPage('internal', false),
                ),
                array(
                    $this->createTemplate(),
                    $this->createTemplate(),
                    $this->createTemplate(),
                ),
                2,
                6,
            ),
        );
    }
    
    private function configureTheme(array $templates)
    {
        $this->theme->expects($this->once())
            ->method('getTemplates')
            ->will($this->returnValue($templates))
        ;
    }
    
    private function configureLanguagesRepository(array $languages)
    {
        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue($languages))
        ;
    }
    
    private function configurePagesRepository(array $pages)
    {
        $this->pageRepository->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue($pages))
        ;
    }
    
    private function configureBlocksRepository(array $blocks = array())
    {
        $this->blocksRepository->expects($this->any())
            ->method('retrieveContents')
            ->will($this->returnValue($blocks))
        ;
    }
    
    private function configureSeoRepository($languages, $pages)
    {
        $seo = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlSeo');
        $this->seoRepository->expects($this->any())
            ->method('fromPageAndLanguage')
            ->will($this->returnValue($seo))
        ;
        
        $at = 3;
        foreach($languages as $language) {
            foreach($pages as $page) {
                if ( ! $page->getIsPublished()) {
                    continue;
                }
                
                $this->factoryRepository->expects($this->at($at))
                    ->method('createRepository')
                    ->with('Seo')
                    ->will($this->returnValue($this->seoRepository))
                ;
                $at++;
            }
        }
    }
    
    protected function createPage($pageName, $isPublished = true)
    {
        $page = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlPage');

        $page->expects($this->atLeastOnce())
            ->method('getPageName')
            ->will($this->returnValue($pageName));
        
        $page->expects($this->atLeastOnce())
            ->method('getIsPublished')
            ->will($this->returnValue($isPublished));
        
        $page->expects($this->atLeastOnce())
            ->method('getId')
            ->will($this->returnValue(2));
        
        return $page;
    }

    protected function createLanguage($languageName)
    {
        $language = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage');

        $language->expects($this->atLeastOnce())
            ->method('getLanguageName')
            ->will($this->returnValue($languageName));
        
        $language->expects($this->atLeastOnce())
            ->method('getId')
            ->will($this->returnValue(2));

        return $language;
    }
    
    protected function createTemplate()
    {
        $template = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate')
                        ->disableOriginalConstructor()
                        ->getMock();

        return $template;
    }
}