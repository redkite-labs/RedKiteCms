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
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\ThemeChanger;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\ThemeChanger\AlThemeChanger;
use org\bovigo\vfs\vfsStream;

/**
 * AlSiteBootstrapTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlThemeChangerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        
        $this->blockManagerFactory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');
        
        $this->languageRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
                
        $this->pageRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->blockRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        
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
        
        $this->factoryRepository
             ->expects($this->at(2))
             ->method('createRepository')
             ->with('Block')
             ->will($this->returnValue($this->blockRepository));
        ;
        
        $this->templateManager = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->root = vfsStream::setup('root', null, array('Resources' => array()));
        
        $this->themeChanger = new AlThemeChanger($this->templateManager, $this->factoryRepository, $this->blockManagerFactory);
    }
    
    /**
     * @dataProvider sitePagesProvider
     */
    public function testChangeTheme($languages, $pages, $blocks, $prevThemeName, $themeName, $arrayMap)
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
        
        $this->blockRepository
             ->expects($this->any())
             ->method('retrieveContents')
             ->with(null, null)
             ->will($this->returnValue($this->extractBlocks($blocks)))
        ;
        
        $prevTheme = $this->initTheme($prevThemeName);        
        $theme = $this->initTheme($themeName);
        
        $this->initBlocks($blocks);       
        $this->checkSaveResult($blocks); 
        $this->changeTemplate($languages, $pages, $theme);
        
        $file = vfsStream::url('root\Resources\.site_structure');        
        $this->themeChanger->change($prevTheme, $theme, $file, $arrayMap);
        $this->assertFileExists($file);
        
        $this->assertEquals($this->buildExpectedThemeStructure($prevThemeName, $languages, $pages), file_get_contents($file));
    }
    
    /**
     * @dataProvider slotsProvider
     */
    public function testChangeSlot($sourceSlotName, $targetSlotName, $sourceBlocks, $targetBlocks, $expectedResult, $sourceIncludedBlocks = array(), $targetIncludedBlocks = array())
    {
        $this->blockRepository
             ->expects($this->at(0))
             ->method('retrieveContents')
             ->with(null, null, $sourceSlotName, array(2, 3))
             ->will($this->returnValue($this->extractBlocks($sourceBlocks)))
        ;
        
        $this->blockRepository
             ->expects($this->at(1))
             ->method('retrieveContents')
             ->with(null, null, $targetSlotName)
             ->will($this->returnValue($this->extractBlocks($targetBlocks)))
        ;
        
        $callingCounter = 0;
        $result = $this->initBlocks($sourceBlocks, array(
                'SlotName' => $targetSlotName,
                'ToDelete' => 0,
            ), $callingCounter);
        $this->checkSaveResult($sourceBlocks);
        
        if ( ! empty($sourceIncludedBlocks)) {
            $this->blockRepository
                 ->expects($this->at(5))
                 ->method('retrieveContentsBySlotName')
                 ->with('%2%', array(2, 3))
                 ->will($this->returnValue($this->extractBlocks($sourceIncludedBlocks)))
            ;
            if ($result) {
                $result = $this->initBlocks($sourceIncludedBlocks, array(
                        'ToDelete' => 0,
                    ), $callingCounter);
                $this->checkSaveResult($sourceIncludedBlocks);
            }
        }
        
        if ($result) {
            $this->initBlocks($targetBlocks, array(
                'SlotName' => $sourceSlotName,
                'ToDelete' => 3,
            ), $callingCounter);    
        
            $this->checkSaveResult($sourceBlocks, 1);
        }
        
        if ($result && ! empty($targetIncludedBlocks)) {
            $this->blockRepository
                 ->expects($this->at(10))
                 ->method('retrieveContentsBySlotName')
                 ->with('%2%', 0)
                 ->will($this->returnValue($this->extractBlocks($targetIncludedBlocks)))
            ;
            
            if ($result) {
                $result = $this->initBlocks($targetIncludedBlocks, array(
                        'ToDelete' => 3,
                    ), $callingCounter);
                $this->checkSaveResult($targetIncludedBlocks);
            }
        }
                
        $result = $this->themeChanger->changeSlot($sourceSlotName, $targetSlotName);
        $this->assertEquals($expectedResult, $result);
    }
    
    /**
     * @dataProvider finalizeProvider
     */
    public function testFinalize($action, $blocks)
    {
        $value = ($action == 'full') ? array(2, 3) : 3; 
        $this->blockRepository
             ->expects($this->once())
             ->method('retrieveContents')
             ->with(null, null, null, $value)
             ->will($this->returnValue($this->extractBlocks($blocks)))
        ;
        
        $this->initBlocks($blocks, array(
            'ToDelete' => 1,
        ));
        $expectedResult = $this->checkSaveResult($blocks);
        
        $result = $this->themeChanger->finalize($action);
        $this->assertEquals($expectedResult, $result);
    }
    
    public function finalizeProvider()
    {
        return array(
            array(
                "partial",
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                ),
            ),
            array(
                "full",
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                ),
            ),
            array(
                "partial",
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                    array(
                        'block' => $this->initBlock(),
                        'result' => false,
                    ),
                ),
            ),
            array(
                "full",
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                    array(
                        'block' => $this->initBlock(),
                        'result' => false,
                    ),
                ),
            ),
        );
    }
    
    public function slotsProvider()
    {
        return array(
            array(
                "logo",
                "site_logo",
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                ),
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                ),
                "The slot has been changed",
            ),
            array(
                "logo",
                "site_logo",
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                    array(
                        'block' => $this->initBlock(),
                        'result' => false,
                    ),
                ),
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                ),
                "The slot has not been changed due to an error occoured when saving to database",
            ),
            array(
                "logo",
                "site_logo",
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                ),
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                    array(
                        'block' => $this->initBlock(),
                        'result' => false,
                    ),
                ),
                "The slot has not been changed due to an error occoured when saving to database",
            ), 
            array(
                "logo",
                "site_logo",
                array(
                    array(
                        'block' => $this->initBlock(2),
                        'result' => true,
                    ),
                ),
                array(
                    array(
                        'block' => $this->initBlock(2),
                        'result' => true,
                    ),
                ),
                "The slot has been changed",
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                ),
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                ),
            ), 
            array(
                "logo",
                "site_logo",
                array(
                    array(
                        'block' => $this->initBlock(2),
                        'result' => true,
                    ),
                ),
                array(
                    array(
                        'block' => $this->initBlock(2),
                        'result' => true,
                    ),
                ),
                "The slot has not been changed due to an error occoured when saving to database",
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => false,
                    ),
                ),
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                ),
            ),  
            array(
                "logo",
                "site_logo",
                array(
                    array(
                        'block' => $this->initBlock(2),
                        'result' => true,
                    ),
                ),
                array(
                    array(
                        'block' => $this->initBlock(2),
                        'result' => true,
                    ),
                ),
                "The slot has not been changed due to an error occoured when saving to database",
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                ),
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => false,
                    ),
                ),
            ), 
        );
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
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                ),
                "AlphaLemonThemeBundle",
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
                ),
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                    array(
                        'block' => $this->initBlock(),
                        'result' => false,
                    ),
                ),
                "AlphaLemonThemeBundle",
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
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                ),
                "AlphaLemonThemeBundle",
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
                array(
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                    array(
                        'block' => $this->initBlock(),
                        'result' => true,
                    ),
                ),
                "AlphaLemonThemeBundle",
                "BootbusinessThemeBundle",
                array(
                    'home' => 'home', 
                    'internal' => 'empty', 
                    'internal_1' => 'contacts'
                ),
            ),
        );
    }
    
    protected function initBlocks($blocks, $value = null, &$callingCounter = 0)
    {        
        $result = true;
        foreach ($blocks as $blockValues) {
            $block = $blockValues["block"]; 
            $result = $blockValues["result"];
            $this->blockManagerFactory
                ->expects($this->at($callingCounter))
                ->method('createBlockManager')
                ->with($block)
                ->will($this->returnValue($this->initBlockManager($block, $result, $value)));
           ;
           
           if ( ! $result) {
               break;
           }
           
           $callingCounter++;
        }
        
        return $result;
    }
    
    protected function checkSaveResult($blocks, $callingCounter = 0)
    {       
        $result = true;
        foreach($blocks as $block) {
            if ( ! $block["result"]) {
                $result = false;
                break;
            }
        }
        
        if ($result) {
            $this->blockRepository
                ->expects($this->at($callingCounter))
                ->method('commit')
            ;
        } else {            
            $this->blockRepository
                ->expects($this->at($callingCounter))
                ->method('rollback')
            ;
        }
        
        return $result;
    }
    
    protected function initBlockManager($block, $result, $value = null)
    {
        $blockManager = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\ServiceBlock\AlBlockManagerService')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $blockManager 
             ->expects($this->once())
             ->method('set')
             ->with($block)
             ->will($this->returnSelf());
        ;
        
        if (null !== $value) {
            $blockManager 
                 ->expects($this->once())
                 ->method('save')
                 ->with($value)
                 ->will($this->returnValue($result));
            ;
        } else {
            $blockManager 
                 ->expects($this->once())
                 ->method('save')
                 ->will($this->returnValue($result));
            ;
        }
        
        return $blockManager;
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
                    ->method('setTemplate')
                    ->with($template)
                    ->will($this->returnSelf())
               ;
               $k++;
               
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
        $language = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage');
        $language->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));

        return $language;
    }
    
    protected function initPage($id, $templateName)
    {
        $page = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlPage');
        $page->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));
            
        $page->expects($this->any())
            ->method('getTemplateName')
            ->will($this->returnValue($templateName));

        return $page;
    }
    
    protected function initBlock($id = null)
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');        
        if (null !== $id) {
            $block
                ->expects($this->once())
                ->method('getId')
                ->will($this->returnValue($id))
            ;
        }
    
        return $block;
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
        
        return $theme;
    }
    
    private function extractBlocks($blocks)
    {
        $theBlocks = array();
        foreach($blocks as $block) {
            $theBlocks[] = $block["block"];
        }
        
        return $theBlocks;
    }
}