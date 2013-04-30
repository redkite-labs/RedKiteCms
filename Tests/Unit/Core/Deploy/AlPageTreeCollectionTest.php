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
                        'isHome' => true,
                        'published' => true,
                    ),
                    array(
                        'page' => 'page-1',
                        'isHome' => false,
                        'published' => true,
                    ),
                ),
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
                        'isHome' => true,
                        'published' => true,
                    ),
                ),
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
                        'isHome' => true,
                        'published' => true,
                    ),
                    array(
                        'page' => 'page-1',
                        'isHome' => false,
                        'published' => false,
                    ),
                    array(
                        'page' => 'page-2',
                        'isHome' => false,
                        'published' => true,
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
                        'published' => false,
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
                        'published' => false,
                    ),
                    array(
                        'page' => 'page-3',
                        'isHome' => false,
                        'published' => true,
                    ),
                ),
            ),      
            array(
                array(
                    array(
                        'language' => 'en', 
                        'isMain' => false,
                    ),
                    array(
                        'language' => 'es', 
                        'isMain' => true,
                    ),
                    array(
                        'language' => 'it', 
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
                        'published' => false,
                    ),
                    array(
                        'page' => 'page-3',
                        'isHome' => false,
                        'published' => true,
                    ),
                ),
            ),                   
            
        );
    }

    /**
     * @dataProvider pages
     */
    public function testPageTreeCollectionHasBeenPopulated($languages, $pages)
    {     
        $this->setUpLanguagesAndPages($languages, $pages);
        /*
        foreach ($languages as $language) {
            $alLanguage = $this->setUpLanguage($language['language'], $language['isMain']);
            $this->languages[] = $alLanguage;
        }
        
        $publishedPages = 0;
        foreach ($pages as $page) {
            $alPage = $this->setUpPage($page['page'], $page['isHome'], $page['published']);
            $this->pages[] = $alPage;
            if ($page['published']) {
                $publishedPages++;
            }
        }
        
        $cycles = count($languages) * $publishedPages;
    
        $this->initPageBlocks($cycles);
        $this->initTemplateManager();
        $this->initThemesCollectionWrapper($cycles);
        
        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
                
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
        
        foreach($this->languages as $language) {
            $languageRepository = $this->initLanguageRepository();
            $languageRepository->expects($this->exactly($publishedPages))
                    ->method('fromPK')
                    ->will($this->returnValue($language));
                    
            foreach($this->pages as $page) { 
                if( ! $page->getIsPublished()) {
                    continue;
                }
                
                $pageRepository = $this->initPageRepository();                
                $pageRepository->expects($this->once())
                    ->method('fromPK')
                    ->will($this->returnValue($page));
                
                $this->setUpCreateRepositoryMethod($languageRepository, $pageRepository, $counter); 
            }       
        }*/

        $activeTheme = $this->getMock('\AlphaLemon\ThemeEngineBundle\Core\Theme\AlActiveThemeInterface');
        $activeTheme->expects($this->any())
            ->method('getActiveTheme')
            ->will($this->returnValue('BusinessWebsiteTheme'));
        
        $this->container->expects($this->at(0))
            ->method('get')
            ->with('alpha_lemon_cms.themes_collection_wrapper')
            ->will($this->returnValue($this->themesCollectionWrapper));
        
        $dispatcher = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        
        $numberOfCalls = ($this->cycles * 2);
        $i = 1;
        while ($i < $numberOfCalls) {
            $this->container->expects($this->at($i))
                ->method('get')
                ->with('event_dispatcher')
                ->will($this->returnValue($dispatcher));
        
            $i = $i + 2;
        }
        
        $i = 2;
        while ($i < $numberOfCalls + 1) {
            $this->container->expects($this->at($i))
                ->method('get')
                ->with('alphalemon_theme_engine.active_theme')
                ->will($this->returnValue($activeTheme));  
        
            $i = $i + 2; 
        }
        
        $pageTreeCollection = new AlPageTreeCollection($this->container, $this->factoryRepository);
        $this->assertEquals($this->cycles, count($pageTreeCollection));
        
        $counter = 0;
        foreach ($languages as $language) {
            foreach ($pages as $page) {
                if ( ! $page['published']) {
                    continue;
                }
                
                $pageTree = $pageTreeCollection->at($counter);
                $this->assertEquals($language['language'], $pageTree->getAlLanguage()->getLanguageName());
                $this->assertEquals($page['page'], $pageTree->getAlPage()->getPageName());
                
                $counter++;
            }
        }
        
        $this->assertNull($pageTreeCollection->at($this->cycles));
        $this->assertEquals(0, $pageTreeCollection->key(0));
    }
}
