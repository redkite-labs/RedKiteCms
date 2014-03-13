<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\ThemeChanger;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ThemeChanger\AlThemeChanger;
use org\bovigo\vfs\vfsStream;

/**
 * AlThemeChangerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlThemeChangerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        
        $this->blockManagerFactory = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        
        $this->languageRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
                
        $this->pageRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        
        $this->factoryRepository
             ->expects($this->at(0))
             ->method('createRepository')
             ->with('Language')
             ->will($this->returnValue($this->languageRepository));
        ;
        
        $this->factoryRepository
             ->expects($this->at(1))
             ->method('createRepository')
             ->with('Page')
             ->will($this->returnValue($this->pageRepository));
        ;
        
        $this->templateManager = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->root = vfsStream::setup('root', null, array('Resources' => array()));
        
        $this->themeChanger = new AlThemeChanger($this->templateManager, $this->factoryRepository);
    }
    
    public function testATemplateNotMappedIsSkipped()
    {
        $languages = array($this->initLanguage(2));
        $this->languageRepository
             ->expects($this->any())
             ->method('activeLanguages')
             ->will($this->returnValue($languages))
        ;
        
        $pages = array($this->initPage(2, 'foo'),);
        $this->pageRepository
             ->expects($this->any())
             ->method('activePages')
             ->will($this->returnValue($pages))
        ;
                
        $prevTheme = $this->initTheme("RedKiteLabsThemeBundle");        
        $theme = $this->initTheme("BootbusinessThemeBundle");
        
        $theme
            ->expects($this->never())
            ->method('getTemplate')
        ;

        $this->templateManager
             ->expects($this->never())
             ->method('refresh')
        ;

        $this->templateManager
             ->expects($this->never())
             ->method('populate')
        ;
        
        $arrayMap = array(
            'home' => 'home', 
            'internal' => 'empty',
        );
        
        $file = vfsStream::url('root\Resources\.site_structure');        
        $this->themeChanger->change($prevTheme, $theme, $file, $arrayMap);
    }
    
    /**
     * @dataProvider sitePagesProvider
     */
    public function testChangeTheme($languages, $pages, $prevThemeName, $themeName, $arrayMap)
    {
        $this->languageRepository
             ->expects($this->any())
             ->method('activeLanguages')
             ->will($this->returnValue($languages))
        ;
        
        $this->pageRepository
             ->expects($this->any())
             ->method('activePages')
             ->will($this->returnValue($pages))
        ;
        
        $prevTheme = $this->initTheme($prevThemeName);        
        $theme = $this->initTheme($themeName);
        
        $this->changeTemplate($languages, $pages, $theme);
        
        $file = vfsStream::url('root\Resources\.site_structure');        
        $this->themeChanger->change($prevTheme, $theme, $file, $arrayMap);
        $this->assertFileExists($file);
        
        $this->assertEquals($this->buildExpectedThemeStructure($prevThemeName, $languages, $pages), file_get_contents($file));
    }
    
    public function sitePagesProvider()
    {
        return array(
            array(
                array(
                    $this->initLanguage(2),
                ),                
                array(
                    $this->initPage(2, 'home'),
                ),
                "RedKiteLabsThemeBundle",
                "BootbusinessThemeBundle",
                array(
                    'home' => 'home', 
                    'internal' => 'empty',
                ),
            ),
            array(
                array(
                    $this->initLanguage(2),
                    $this->initLanguage(3),
                ),                
                array(
                    $this->initPage(2, 'home'),
                    $this->initPage(3, 'internal'),
                ),
                "RedKiteLabsThemeBundle",
                "BootbusinessThemeBundle",
                array(
                    'home' => 'home', 
                    'internal' => 'empty'
                ),
            ),
            array(
                array(
                    $this->initLanguage(2),
                    $this->initLanguage(3),
                ),                
                array(
                    $this->initPage(2, 'home'),
                    $this->initPage(3, 'internal'),
                    $this->initPage(4, 'internal'),
                ),
                "RedKiteLabsThemeBundle",
                "BootbusinessThemeBundle",
                array(
                    'home' => 'home', 
                    'internal' => 'empty'
                ),
            ),
            array(
                array(
                    $this->initLanguage(2),
                    $this->initLanguage(3),
                ),                
                array(
                    $this->initPage(2, 'home'),
                    $this->initPage(3, 'internal'),
                    $this->initPage(4, 'internal'),
                    $this->initPage(5, 'internal_1'),
                ),
                "RedKiteLabsThemeBundle",
                "BootbusinessThemeBundle",
                array(
                    'home' => 'home', 
                    'internal' => 'empty', 
                    'internal_1' => 'contacts',
                ),
            ),
        );
    }
    
    protected function changeTemplate($languages, $pages, $theme)
    {
        $c = 0;
        $k = 0;
        $ignoreRepeatedSlots = false;
        foreach ($languages as $language) {
            foreach ($pages as $page) {
                
                $template = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate')
                    ->disableOriginalConstructor()
                    ->getMock()
                ;
                
                $theme
                    ->expects($this->at($c))
                    ->method('getTemplate')
                    ->will($this->returnValue($template))
               ;
               
               $this->templateManager
                    ->expects($this->at($k))
                    ->method('refresh')
               ;
               $k++;
               
               $this->templateManager
                    ->expects($this->at($k))
                    ->method('populate')
                    ->with($language->getId(), $page->getId(), $ignoreRepeatedSlots)
               ;
               $k++;
               
               $ignoreRepeatedSlots = true;
               $c++;
            }
        }
    }
    
    protected function buildExpectedThemeStructure($themeName, $languages, $pages)
    {
        $content = "";
        $templates = array();
        foreach ($languages as $language) {
            foreach ($pages as $page) {
                $key = $language->getId() . '-' . $page->getId();                
                $templates[] = sprintf('"%s":"%s"', $key, $page->getTemplateName());
            }
        }
    
        $file = sprintf('{"Theme":"%s","Templates":{%s}}', $themeName, implode(",", $templates));

        return $file;
    }
    
    protected function initLanguage($id)
    {
        $language = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlLanguage');
        $language->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));

        return $language;
    }
    
    protected function initPage($id, $templateName)
    {
        $page = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlPage');
        $page->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));
            
        $page->expects($this->any())
            ->method('getTemplateName')
            ->will($this->returnValue($templateName));

        return $page;
    }
    
    protected function initTheme($themeName)
    {
        $theme = $this
            ->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Theme\AlTheme')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $theme
             ->expects($this->any())
             ->method('getThemeName')
             ->will($this->returnValue($themeName));
        ;
        
        $themeSlots = $this->getMock("RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\AlThemeSlotsInterface");
        $theme
             ->expects($this->any())
             ->method('getThemeSlots')
             ->will($this->returnValue($themeSlots));
        ;
        
        return $theme;
    }
}