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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Deploy;

use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\AlPageTreeCollection;

/**
 * AlPageTreeCollectionTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
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

        $activeTheme = $this->getMock('\RedKiteLabs\ThemeEngineBundle\Core\Theme\AlActiveThemeInterface');
        $activeTheme->expects($this->any())
            ->method('getActiveTheme')
            ->will($this->returnValue('BusinessWebsiteTheme'));
        
        $this->container->expects($this->at(0))
            ->method('get')
            ->with('red_kite_cms.themes_collection_wrapper')
            ->will($this->returnValue($this->themesCollectionWrapper));
        
        $dispatcher = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        
        $numberOfCalls = ($this->cycles * 3);
        $i = 1;
        while ($i < $numberOfCalls) {
            $this->container->expects($this->at($i))
                ->method('get')
                ->with('event_dispatcher')
                ->will($this->returnValue($dispatcher));
        
            $i = $i + 3;
        }
        
        $i = 2;
        while ($i < $numberOfCalls + 1) {
            $this->container->expects($this->at($i))
                ->method('get')
                ->with('red_kite_cms.block_manager_factory')
                ->will($this->returnValue($activeTheme));  
        
            $i = $i + 3; 
        }
        
        $i = 3;
        while ($i < $numberOfCalls + 2) {
            $this->container->expects($this->at($i))
                ->method('get')
                ->with('red_kite_labs_theme_engine.active_theme')
                ->will($this->returnValue($activeTheme));  
        
            $i = $i + 3; 
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
