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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\PageTree;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\AlPageTree;

class AlPageTreeTester extends AlPageTree
{
    private $dataManager;
    private $templateAssetsManager;
    
    public function getDataManager()
    {
        return $this->dataManager;
    }
    
    public function getTemplateAssetsManager()
    {
        return $this->templateAssetsManager;
    }
}

/**
 * AlPageTreeTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlPageTreeTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        
        $this->dataManager = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\DataManager\DataManager')
                                            ->disableOriginalConstructor()
                                            ->getMock();
        
        $this->templateAssetsManager = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\TemplateAssetsManager\TemplateAssetsManager')
                                            ->disableOriginalConstructor()
                                            ->getMock();
        
        $this->templateManager = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager')
                                            ->disableOriginalConstructor()
                                            ->getMock();
        
        $this->pageBlocks = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocksInterface');
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        
        $themeSlots = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\AlThemeSlotsInterface');
        $this->theme = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Theme\AlTheme')
                            ->disableOriginalConstructor()
                            ->getMock();
        $this->theme
            ->expects($this->any())
            ->method('getThemeSlots')
            ->will($this->returnValue($themeSlots))
        ;
    }
    
    public function testDataManagerInjectedBySetters()
    {
        $pageTree = new AlPageTreeTester($this->templateAssetsManager);
        $this->assertNull($pageTree->getDataManager());
        $pageTree->setDataManager($this->dataManager);
        $this->assertNotSame($this->dataManager, $pageTree->getDataManager());
    }
    
    public function testTemplateAssetsManagerInjectedBySetters()
    {
        $pageTree = new AlPageTreeTester($this->templateAssetsManager);
        $this->assertNull($pageTree->getTemplateAssetsManager());
        
        $templateAssetsManager = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\TemplateAssetsManager\TemplateAssetsManager')
                                      ->disableOriginalConstructor()
                                      ->getMock();
        $pageTree->setTemplateAssetsManager($templateAssetsManager);
        $this->assertNotSame($templateAssetsManager, $pageTree->getTemplateAssetsManager());
    }
    
    public function testCmsMode()
    {
        $pageTree = new AlPageTree($this->templateAssetsManager);
        $this->assertTrue($pageTree->isCmsMode());
        $pageTree->productionMode(true);
        $this->assertFalse($pageTree->isCmsMode());
    }
    
    public function testGetBlockManagersReturnsAnEmptyArrayWhenTemplateManagerInNull()
    {
        $pageTree = new AlPageTree($this->templateAssetsManager);
        $pageTree->setUp($this->theme, $this->templateManager, $this->pageBlocks);
        $this->assertEquals(array(), $pageTree->getBlockManagers('logo'));
    }
    
    /**
     * @dataProvider blockManagersProvider
     */
    public function testGetBlockManagers($slotName, $slotManager, $expectedResult)
    {
        if (null !== $slotManager) {
            $this->templateManager->expects($this->once())
                ->method('getSlotManager')
                ->with($slotName)
                ->will($this->returnValue($slotManager))
            ;
                                
            $bmCollection = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Blocks\BlockManagersCollection')
                    ->disableOriginalConstructor()
                    ->getMock();
            $bmCollection->expects($this->once())
                ->method('getBlockManagers')
                ->will($this->returnValue($expectedResult))
            ;
            
            $slotManager->expects($this->once())
                ->method('getBlockManagersCollection')
                ->will($this->returnValue($bmCollection))
            ;
        }
        
        $pageTree = new AlPageTree($this->templateAssetsManager);
        $pageTree->setUp($this->theme, $this->templateManager, $this->pageBlocks);
        $this->assertEquals($expectedResult, $pageTree->getBlockManagers($slotName));
    }
    
    public function blockManagersProvider()
    {
        $slotManager = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\AlSlotManager')
                            ->disableOriginalConstructor()
                            ->getMock();
        
        return array(
            array(
                "logo",
                null,
                array(),
            ),
            array(
                "logo",
                $slotManager,
                array('foo'),
            ),
        );
    }
    
    /**
     *  @dataProvider pageTreeProvider
     */
    public function testPageTreeSetUp($language, $page, $templateName =null, $template = null, $hasDispatcher = false)
    {
        $dispatcher = null;
        if ($hasDispatcher) {
            $dispatcher = $this->dispatcher;
        }
        $this->initDispatcher($dispatcher);
        $this->initDataManager($language, $page);
        $this->initPageBlocks($language, $page);
                
        $pageTree = new AlPageTree($this->templateAssetsManager, $dispatcher, $this->dataManager);
        
        if (null === $page || null === $template) {
            $this->templateManager->expects($this->never())
                ->method('refresh')
            ;
            
            $pageTree->setUp($this->theme, $this->templateManager, $this->pageBlocks);
            
            return;
        }
        
        $resetTemplate = $this->initTemplate($templateName, $page);
        $this->completeSetup($template);
        
        // Resets the tamplate before calling setUp method because it is not passed as argument
        if ($resetTemplate) {
            $template = null;
        }
        
        $pageTree->setUp($this->theme, $this->templateManager, $this->pageBlocks, $template);
    }
    
    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Call to undefined method: AlPageTree->getSomething()
     */
    public function testAnExceptionIsThrownWhenCalledMethodDoesNotExists()
    {   
        $pageTree = new AlPageTree($this->templateAssetsManager, $this->dispatcher, $this->dataManager);
        $pageTree->getSomething();
    }
    
    /**
     * @dataProvider assetsProvider
     */
    public function testGetAssets($method, $assets, $expectedResult)
    {   
        $templateAssetsManager = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\TemplateAssetsManager\TemplateAssetsManager')
                                            ->setMethods(array('getExternalStylesheets', 'getInternalStylesheets', 'getExternalJavascripts', 'getInternalJavascripts',))
                                            ->disableOriginalConstructor()
                                            ->getMock();
        
        $templateAssetsManager->expects($this->once())
            ->method($method)
            ->will($this->returnValue($assets))
        ;
        
        $pageTree = new AlPageTree($templateAssetsManager, $this->dispatcher, $this->dataManager);
        $this->assertEquals($expectedResult, $pageTree->__call($method, ""));
    }
    
    /**
     * @dataProvider metatagsProvider
     */
    public function testGetMetatags($method)
    {   
        $pageTree = new AlPageTree($this->templateAssetsManager, $this->dispatcher, $this->dataManager);
        $pageTree->__call($method, "");
    }
    
    /**
     * @dataProvider dataProvider
     */
    public function testDataManager($method, $dmMethod, $object)
    { 
        $this->dataManager->expects($this->once())
            ->method($dmMethod)
            ->will($this->returnValue($object))
        ;
        
        $pageTree = new AlPageTree($this->templateAssetsManager, $this->dispatcher, $this->dataManager);
        $this->assertSame($object, $pageTree->__call($method, ""));
    }
    
    /**
     * @dataProvider objectsProvider
     */
    public function testGetObjectes($method)
    {   
        $template = $this->createTemplate();
        switch ($method){
            case "getTheme":
                $object = $this->theme;
                break;
            case "getTemplateManager":
                $object = $this->templateManager;
                break;
            case "getPageBlocks":
                $object = $this->pageBlocks;
                break;
            case "getTemplate":
                $object = $template;
                break;
        }
        $this->completeSetup($template);
        $pageTree = new AlPageTree($this->templateAssetsManager, $this->dispatcher, $this->dataManager);
        $pageTree->setUp($this->theme, $this->templateManager, $this->pageBlocks, $template);
        $this->assertSame($object, $pageTree->__call($method, ""));
    }
    
    public function dataProvider()
    {
        return array(
            array(
                'getAlLanguage',
                'getLanguage',
                $this->createLanguage('en'),
            ),
            array(
                'getAlPage',
                'getPage',
                $this->createPage('index'),
            ),
            array(
                'getAlSeo',
                'getSeo',
                $this->createSeo(),
            ),
        );
    }
        
    public function objectsProvider()
    {
        return array(
            array(
                'getTheme',
            ),
            array(
                'getTemplateManager',
            ),
            array(
                'getPageBlocks',
            ),
            array(
                'getTemplate',                
            ),
        );
    }
    
    public function metatagsProvider()
    {        
        return array(
            array(
                'getMetaTitle',
            ),
            array(
                'getMetaDescription',
            ),
            array(
                'getMetaKeywords',
            ),
        );
    }
    
    public function assetsProvider()
    {        
        return array(
            array(
                'getExternalStylesheets',
                null,
                array(),
            ),
            array(
                'getExternalStylesheets',
                array("asset1", "asset2"),
                array("asset1", "asset2"),
            ),
            array(
                'getInternalStylesheets',
                null,
                "",
            ),
            array(
                'getInternalStylesheets',
                array("code1", "code2"),
                "code1\ncode2",
            ),
            array(
                'getExternalJavascripts',
                array("asset1", "asset2"),
                array("asset1", "asset2"),
            ),
            array(
                'getInternalJavascripts',
                array("code1", "code2"),
                "code1\ncode2",
            ),
        );
    }
    
    
    public function pageTreeProvider()
    {
        return array(
            array(
                null,
                null,
            ),
            array(
                $this->createLanguage('en'),
                $this->createPage('index'),
            ),
            array(
                $this->createLanguage('en'),
                $this->createPage('index'),
                'home',
            ),
            array(
                $this->createLanguage('en'),
                $this->createPage('index'),
                null,
                $this->createTemplate(),
            ),
            array(
                $this->createLanguage('en'),
                $this->createPage('index'),
                null,
                $this->createTemplate(),
                true,
            ),
        );
    }
    
    private function completeSetup($template)
    {
        $this->templateAssetsManager->expects($this->once())
            ->method('withExtraAssets')
            ->will($this->returnSelf())
        ;
        
        $this->templateAssetsManager->expects($this->once())
            ->method('setUp')
            ->with($template)
        ;
        
        $this->templateManager->expects($this->once())
            ->method('refresh')
        ;
    }
    
    private function initDataManager($language, $page)
    {
        $this->dataManager->expects($this->once())
            ->method('getLanguage')
            ->will($this->returnValue($language));
        ;
        
        $this->dataManager->expects($this->once())
            ->method('getPage')
            ->will($this->returnValue($page));
        ;
        
        $this->dataManager->expects($this->any())
            ->method('getSeo')
            ->will($this->returnValue($this->createSeo()));
        ;
    }
    
    private function initPageBlocks($language, $page)
    {
        if (null !== $language && null !== $page) {            
            $this->pageBlocks->expects($this->once())
                ->method('refresh')
                ->with(2, 3)
            ;
        } else {
            $this->pageBlocks->expects($this->never())
                ->method('refresh')
            ;
        }
    }
    
    private function initTemplate($templateName, $page)
    {
        if (null !== $templateName) {
            $page->expects($this->once())
                ->method('getTemplateName')
                ->will($this->returnValue($templateName));
            ;
            
            $template = $this->createTemplate();
            $this->theme->expects($this->once())
                ->method('getTemplate')
                ->with($templateName)
                ->will($this->returnValue($template))
            ;
            
            return true;
        }
        
        return false;
    }

    private function createLanguage($languageName)
    {
        $language = $this->getMock("RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlLanguage");
        $language->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));
        ;
        
        $language->expects($this->once())
            ->method('getLanguageName')
            ->will($this->returnValue($languageName));
        ;
        
        return $language;
    }
    
    private function createPage($pageName)
    {
        $page = $this->getMock("RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlPage");
        $page->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(3));
        ;
        
        $page->expects($this->once())
            ->method('getPageName')
            ->will($this->returnValue($pageName));
        ;
        
        return $page;
    }
    
    private function createSeo()
    {
        return $this->getMock("RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlSeo");
    }
    
    private function createTemplate()
    {
        return $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate')
                    ->disableOriginalConstructor()
                    ->getMock()
        ;
    }
    
    private function initDispatcher($dispatcher)
    {
        if (null === $dispatcher) {
            $this->dispatcher->expects($this->never())
                ->method('dispatch')
            ;
            
            return;
        }
        
        $this->dispatcher->expects($this->at(0))
            ->method('dispatch')
            ->with('page_tree.before_setup')
        ;
        
        $this->dispatcher->expects($this->at(1))
            ->method('dispatch')
            ->with('page_tree.after_setup')
        ;
    }
}