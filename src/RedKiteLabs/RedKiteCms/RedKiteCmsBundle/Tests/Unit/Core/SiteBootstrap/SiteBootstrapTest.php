<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\SiteBootstrap\SiteBootstrap;

class SiteBootstrapTester extends SiteBootstrap
{
    public function getLanguageManager()
    {
        return $this->languageManager;
    }
    
    public function getPageManager()
    {
        return $this->pageManager;
    }
    
    public function getTemplateManager()
    {
        return $this->templateManager;
    }
}

/**
 * SiteBootstrapTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class SiteBootstrapTest extends TestCase
{
    private $siteBoostrap;    
    private $languageManager;
    private $pageManager;
    private $templateManager;
    
    protected function setUp()
    {
        parent::setUp();

        $this->languageRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\LanguageRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->languageManager = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Language\LanguageManager')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->pageRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\PageRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->pageManager = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Page\PageManager')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->blockRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\BlockRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->blockManager = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManager')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->template = 
            $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Template\Template')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->templateManager = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Template\TemplateManager')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->siteBoostrap = new SiteBootstrap($this->languageManager, $this->pageManager, $this->blockManager, $this->templateManager);
    }
    
    public function testLanguageManagerInjectedBySetters()
    {
        $siteBootstrap = new SiteBootstrapTester($this->languageManager, $this->pageManager, $this->blockManager, $this->templateManager);
        $languageManager = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Language\LanguageManager')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        $this->assertEquals($siteBootstrap, $siteBootstrap->setLanguageManager($languageManager));
        $this->assertEquals($languageManager, $siteBootstrap->getLanguageManager());
        $this->assertNotSame($this->languageManager, $siteBootstrap->getLanguageManager());
    }
    
    public function testPageManagerInjectedBySetters()
    {
        $siteBootstrap = new SiteBootstrapTester($this->languageManager, $this->pageManager, $this->blockManager, $this->templateManager);
        $pageManager = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Page\PageManager')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        $this->assertEquals($siteBootstrap, $siteBootstrap->setPageManager($pageManager));
        $this->assertEquals($pageManager, $siteBootstrap->getPageManager());
        $this->assertNotSame($this->pageManager, $siteBootstrap->getPageManager());
    }
    
    public function testTemplateManagerInjectedBySetters()
    {
        $siteBootstrap = new SiteBootstrapTester($this->languageManager, $this->pageManager, $this->blockManager, $this->templateManager);
        $templateManager = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Template\TemplateManager')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        $this->assertEquals($siteBootstrap, $siteBootstrap->setTemplateManager($templateManager));
        $this->assertEquals($templateManager, $siteBootstrap->getTemplateManager());
        $this->assertNotSame($this->templateManager, $siteBootstrap->getTemplateManager());
    }
    
    public function testDeleteExistingLanguageFails()
    {   
        $this->initLanguageManager();
        $this->initPageManager();
        $this->initBlockManager();
        
        $language = $this->initLanguage();
        $language
              ->expects($this->once())
              ->method('delete')
              ->will($this->throwException(new \PropelException('Unknown error')))
        ;
        $this->initLanguageRepository(array($language), true);
        
        $this->languageManager
             ->expects($this->never())
             ->method('save')
        ;
        
        $this->pageManager
             ->expects($this->never())
             ->method('save')
        ;
        
        $this->pageRepository
             ->expects($this->never())
             ->method('activePages')
        ;
        
        $this->assertFalse($this->siteBoostrap->bootstrap());
        $this->assertEquals('An error occoured during the removing of existing languages. The reported error is: Unknown error', $this->siteBoostrap->getErrorMessage());
    }
    
    public function testDeleteExistingPageFails()
    {   
        $this->initLanguageManager();
        $this->initPageManager();
        $this->initBlockManager();
        
        $language = $this->initLanguage();
        $language
              ->expects($this->once())
              ->method('delete')
        ;
        $this->initLanguageRepository(array($language), true);
        
        $page = $this->initPage();
        $page
              ->expects($this->once())
              ->method('delete')                
              ->will($this->throwException(new \PropelException('Unknown error')))
        ;
        $this->initPageRepository(array($page));
                        
        $this->languageManager
             ->expects($this->never())
             ->method('save')
        ;
        
        $this->pageManager
             ->expects($this->never())
             ->method('save')
        ;
        
        $this->assertFalse($this->siteBoostrap->bootstrap());
        $this->assertEquals('An error occoured during the removing of existing pages. The reported error is: Unknown error', $this->siteBoostrap->getErrorMessage());
    }
    
    public function testAddingNewLanguageFails()
    {   
        $this->initLanguageManager();
        $this->initPageManager();
        $this->initBlockManager();
        
        $language = $this->initLanguage();
        $language
              ->expects($this->once())
              ->method('delete')
        ;
        $this->initLanguageRepository(array($language), true);
        
        $page = $this->initPage();
        $page
              ->expects($this->once())
              ->method('delete')
        ;
        $this->initPageRepository(array($page));
        $this->deleteBlocks();
                
        $this->languageManager
             ->expects($this->once())
             ->method('set')
             ->with(null)
             ->will($this->returnSelf())
        ;
        
        $this->languageManager
             ->expects($this->once())
             ->method('save')
             ->will($this->throwException(new \PropelException('Unknown error')))
        ;

        $this->pageManager
            ->expects($this->never())
            ->method('save')
        ;
        
        $this->assertFalse($this->siteBoostrap->bootstrap());
        $this->assertEquals('An error occoured during the saving of the new language. The reported error is: Unknown error', $this->siteBoostrap->getErrorMessage());
    }
    
    public function testAddingNewPageFails()
    {   
        $this->initLanguageManager();
        $this->initPageManager();
        $this->initBlockManager();
        
        $language = $this->initLanguage();
        $language
              ->expects($this->once())
              ->method('delete')
        ;
        $this->initLanguageRepository(array($language), true);
        
        $page = $this->initPage();
        $page
              ->expects($this->once())
              ->method('delete')
        ;
        $this->initPageRepository(array($page));
        $this->deleteBlocks();
                
        $this->languageManager
             ->expects($this->once())
             ->method('set')
             ->with(null)
             ->will($this->returnSelf())
        ;
        
        $this->languageManager
             ->expects($this->once())
             ->method('save')
             ->will($this->returnValue(true))
        ;
        
        $this->initTemplate();            
        $this->pageManager
            ->expects($this->once())
            ->method('set')
            ->with(null)
            ->will($this->returnSelf())
        ;

        $this->pageManager
            ->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \PropelException('Unknown error')))
        ;
        
        $this->assertFalse($this->siteBoostrap->bootstrap());
        $this->assertEquals('An error occoured during the saving of the new page. The reported error is: Unknown error', $this->siteBoostrap->getErrorMessage());
    }
    
    /**
     * @dataProvider savingProvider
     */
    public function testBootstrapSite($languageAddedResult, $pageAddedResult, $expectedMessage, $languagesValues = null, $pagesValues = null)
    {   
        $this->initLanguageManager();
        $this->initPageManager();
        $this->initBlockManager();
        
        $language = $this->initLanguage();
        $language
              ->expects($this->once())
              ->method('delete')
        ;
        $fails = ( ! $languageAddedResult || ! $pageAddedResult) ? true : false;
        $this->initLanguageRepository(array($language), $fails);
        
        $page = $this->initPage();
        $page
              ->expects($this->once())
              ->method('delete')
        ;
        $this->initPageRepository(array($page));
        $this->deleteBlocks();
                
        $this->languageManager
             ->expects($this->once())
             ->method('set')
             ->with(null)
             ->will($this->returnSelf())
        ;
        
        if (null !== $languagesValues) {
            $this->siteBoostrap->setDefaultLanguageValues($languagesValues);
            
            $this->languageManager
                ->expects($this->once())
                ->method('save')
                ->with($languagesValues)
                ->will($this->returnValue($languageAddedResult))
            ;
        }
        else {
            $this->languageManager
                ->expects($this->once())
                ->method('save')
                ->will($this->returnValue($languageAddedResult))
            ;
        }
        
        if (null !== $pageAddedResult) {
            $this->initTemplate();
            
            $this->pageManager
                ->expects($this->once())
                ->method('set')
                ->with(null)
                ->will($this->returnSelf())
            ;
        
            if (null !== $pagesValues) {
                $this->siteBoostrap->setDefaultPageValues($pagesValues);
                
                $pagesValues['TemplateName'] = 'home';
                $this->pageManager
                    ->expects($this->once())
                    ->method('save')
                    ->with($pagesValues)
                    ->will($this->returnValue($pageAddedResult))
                ;
            }
            else {
                $this->pageManager
                    ->expects($this->once())
                    ->method('save')
                    ->will($this->returnValue($pageAddedResult))
                ;
            }
        }
        
        $result = $this->siteBoostrap->bootstrap();
        $this->assertEquals(!$fails, $result);
        $this->assertEquals($expectedMessage, $this->siteBoostrap->getErrorMessage());
    }
    
    public function savingProvider()
    {
        return array(
            array(false, null, 'An error occoured during the saving of the new language'),
            array(true, false, 'An error occoured during the saving of the new page'),
            array(true, true, ''),
            array(true, true, '', array('LanguageName' => 'es')),
            array(true, true, '', null, array(
                'PageName' => 'another page',
                'Permalink' => 'another page',
                'MetaTitle' => 'A website made with RedKiteCms',
                'MetaDescription' => 'Website homepage',
                'MetaKeywords' => '',
            )),
        );
    }
    
    
    private function initLanguageManager()
    {
        $this->languageManager
             ->expects($this->once())
             ->method('getLanguageRepository')
             ->will($this->returnValue($this->languageRepository))
        ;
    }
    
    private function initPageManager()
    {
        $this->pageManager
             ->expects($this->once())
             ->method('getPageRepository')
             ->will($this->returnValue($this->pageRepository))
        ;
    }
    
    private function initBlockManager()
    {
        $this->blockManager
             ->expects($this->once())
             ->method('getBlockRepository')
             ->will($this->returnValue($this->blockRepository))
        ;
    }
    
    private function initLanguageRepository($languages, $fails)
    {
        $this->languageRepository
             ->expects($this->once())
             ->method('startTransaction')
        ;
        
        $this->languageRepository
             ->expects($this->once())
             ->method('activeLanguages')
             ->will($this->returnValue($languages))
        ;
        
        if ($fails) {
            $this->languageRepository
                ->expects($this->never())
                ->method('commit')
            ;
            
            $this->languageRepository
                ->expects($this->once())
                ->method('rollback')
            ;
        }
        else {
            $this->languageRepository
                ->expects($this->never())
                ->method('rollback')
            ;
            
            $this->languageRepository
                ->expects($this->once())
                ->method('commit')
            ;
        }
    }
    
    private function initPageRepository($values)
    {
        $this->pageRepository
             ->expects($this->once())
             ->method('activePages')
             ->will($this->returnValue($values))
        ;
    }
    
    private function deleteBlocks()
    {
        $this->blockRepository
             ->expects($this->once())
             ->method('deleteBlocks')
             ->with(1, 1, true)
        ;
    }
    
    private function initLanguage()
    {
         $language = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Model\Language', array('delete'));
         
         return $language;
    }
    
    private function initPage()
    {
         $page = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Model\Page', array('delete'));
         
         return $page;
    }
    
    private function initTemplate($templateName = 'home')
    {
        $this->templateManager
             ->expects($this->once())
             ->method('getTemplate')
             ->will($this->returnValue($this->template))
        ;
        
        $this->template
             ->expects($this->once())
             ->method('getTemplateName')
             ->will($this->returnValue($templateName))
        ;
    }
}