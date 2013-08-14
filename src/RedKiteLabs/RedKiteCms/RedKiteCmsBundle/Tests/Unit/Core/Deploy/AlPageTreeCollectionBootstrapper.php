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
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Deploy;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;

/**
 * Inits the object required to setup a pageTreeCollection
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class AlPageTreeCollectionBootstrapper extends TestCase
{
    protected $pages;
    protected $languages;
    protected $themeRepository;
    protected $template;    
    protected $templateManager;
    protected $pageBlocks;
    protected $themes;
    protected $factoryRepository;
    protected $themesCollectionWrapper;
    protected $cycles;
    protected $publishedPages;
    protected $counterRepositoriesCreation;
    
    protected function setUpLanguagesAndPages($languages, $pages, $seo = array())
    {
        foreach ($languages as $language) {
            $alLanguage = $this->setUpLanguage($language['language'], $language['isMain']);
            $this->languages[$language['language']] = $alLanguage;
        }
        
        $publishedPages = 0;
        foreach ($pages as $page) {
            $template = (array_key_exists('template', $page)) ? $page['template'] : null;
            $alPage = $this->setUpPage($page['page'], $page['isHome'], $page['published'], $template);
            $this->pages[$page['page']] = $alPage;
            if ($page['published']) {
                $publishedPages++;
            }
        }
        $this->publishedPages = $publishedPages;
        
        $this->seo = array();
        foreach ($seo as $seoAttributes) {
            $alSeo = $this->setUpSeo($seoAttributes['permalink'], $this->languages[$seoAttributes['language']], $this->pages[$seoAttributes['page']]);
            $key = $seoAttributes['language'] . '-' . $seoAttributes['page'];
            $this->seo[$key] = $alSeo;
        }
        
        $this->cycles = count($languages) * $publishedPages;
    
        $this->initPageBlocks($this->cycles);
        $this->initTemplateManager();
        $this->themesCollectionWrapper = $this->initThemesCollectionWrapper($this->cycles);
        
        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
                
        $languageRepository = $this->initLanguageRepository();                                
        $languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue($this->languages));
        
        $pageRepository = $this->initPageRepository();       
        $pageRepository->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue($this->pages));
        
        $counter = 0;
        $this->setUpCreateRepositoryMethod($languageRepository, $pageRepository, $counter);
        
        foreach($this->languages as $languageName => $language) {
            $languageRepository = $this->initLanguageRepository();
            $languageRepository->expects($this->exactly($publishedPages))
                    ->method('fromPK')
                    ->will($this->returnValue($language));
                    
            foreach($this->pages as $pageName => $page) { 
                if( ! $page->getIsPublished()) {
                    continue;
                }
                
                $pageRepository = $this->initPageRepository();                
                $pageRepository->expects($this->once())
                    ->method('fromPK')
                    ->will($this->returnValue($page));
                
                $key = $languageName . '-' . $pageName;
                $seo = array_key_exists($key, $this->seo) ? $this->seo[$key] : null;
                $seoRepository = $this->initSeoRepository();     
                
                if (null !== $seo) {
                    $seoRepository->expects($this->once())
                        ->method('fromPageAndLanguage')
                        ->will($this->returnValue($seo));
                }
                    
                $this->setUpCreateRepositoryMethod($languageRepository, $pageRepository, $counter, $seoRepository); 
            }       
        }
        
        $this->counterRepositoriesCreation = $counter;
    }
        
    protected function setUpCreateRepositoryMethod($languageRepository, $pageRepository, &$counter, $seoRepository = null)
    {
        $this->factoryRepository->expects($this->at($counter))
                ->method('createRepository')
                ->with('Language')
                ->will($this->returnValue($languageRepository));
        $counter++;
        
        $this->factoryRepository->expects($this->at($counter))
            ->method('createRepository')
            ->with('Page')
            ->will($this->returnValue($pageRepository));
        $counter++;
        
        if (null !== $seoRepository) {
            $this->factoryRepository->expects($this->at($counter))
                ->method('createRepository')
                ->with('Seo')
                ->will($this->returnValue($seoRepository));
            $counter++;
        }
    }
    
    protected function initSeoRepository()
    {
        return $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\SeoRepositoryInterface')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
    
    protected function initLanguageRepository()
    {
        return $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
    
    protected function initPageRepository()
    {
        return $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
    
    protected function initPageBlocks($expects)
    {
        // Prepares the pageBlocks object
        $this->pageBlocks = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocks')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this
            ->pageBlocks->expects($this->exactly($expects))
            ->method('setIdLanguage')
            ->will($this->returnSelf());

        $this
            ->pageBlocks->expects($this->exactly($expects))
            ->method('setIdPage')
            ->will($this->returnSelf());

        $this
            ->pageBlocks
            ->expects($this->exactly($expects))
            ->method('refresh')
            ->will($this->returnSelf())
        ;
    }
    
    protected function initTemplateManager()
    {
        // Prepares the template object
        $this->template = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        // Prepares the templateManager object
        $this->templateManager = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->templateManager->expects($this->any())
            ->method('getTemplate')
            ->will($this->returnValue($this->template));

        $this->templateManager->expects($this->any())
            ->method('getPageBlocks')
            ->will($this->returnValue($this->pageBlocks));

        $this->templateManager->expects($this->any())
            ->method('setPageBlocks')
            ->will($this->returnSelf());

        $this->templateManager->expects($this->any())
            ->method('setTemplateSlots')
            ->will($this->returnSelf());
    }
    
    protected function initThemesCollectionWrapper($expected)
    {
        $themesCollectionWrapper = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\ThemesCollectionWrapper\AlThemesCollectionWrapper')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $themesCollectionWrapper->expects($this->any())
            ->method('assignTemplate')
            ->will($this->returnValue($this->templateManager));

        $themesCollectionWrapper->expects($this->exactly($expected))
            ->method('getTemplateManager')
            ->will($this->returnValue($this->templateManager));
        
        $theme = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface');
        $theme->expects($this->exactly($expected))
            ->method('getTemplate')
            ->will($this->returnValue($this->template));

        $themesCollection = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection');
        $themesCollection->expects($this->exactly($expected))
            ->method('getTheme')
            ->will($this->returnValue($theme));

        $themesCollectionWrapper->expects($this->exactly($expected))
            ->method('getThemesCollection')
            ->will($this->returnValue($themesCollection));
        
        
        return $themesCollectionWrapper;
    }

    protected function setUpPage($pageName, $isHome = false, $isPublished = true, $template = 'home')
    {
        $page = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlPage');
        $page->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(2));

        $page->expects($this->any())
            ->method('getPageName')
            ->will($this->returnValue($pageName));

        $page->expects($this->any())
            ->method('getIsHome')
            ->will($this->returnValue($isHome));
        
        $page->expects($this->any())
            ->method('getIsPublished')
            ->will($this->returnValue($isPublished));

        $page->expects($this->any())
            ->method('getTemplateName')
            ->will($this->returnValue($template));

        return $page;
    }

    protected function setUpLanguage($languageName, $isMain = false)
    {
        $language = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage');
        $language->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(2));

        $language->expects($this->any())
            ->method('getLanguageName')
            ->will($this->returnValue($languageName));

        $language->expects($this->any())
            ->method('getMainLanguage')
            ->will($this->returnValue($isMain));

        return $language;
    }

    protected function setUpSeo($permalink, $language, $page)
    {
        $seo = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlSeo');
        $seo->expects($this->any())
            ->method('getPermalink')
            ->will($this->returnValue($permalink));

        $seo->expects($this->any())
            ->method('getAlLanguage')
            ->will($this->returnValue($language));
            
        $seo->expects($this->any())
            ->method('getAlPage')
            ->will($this->returnValue($page));

        return $seo;
    }

    protected function setUpBlock($content)
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue($content));

        return $block;
    }
}
