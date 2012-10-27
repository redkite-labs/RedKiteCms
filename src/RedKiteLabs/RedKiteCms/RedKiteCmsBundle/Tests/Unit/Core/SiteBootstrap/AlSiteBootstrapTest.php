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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\PageTree;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\SiteBootstrap\AlSiteBootstrap;

/**
 * AlSiteBootstrapTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlSiteBootstrapTest extends TestCase
{
    private $siteBoostrap;    
    private $languageManager;
    private $pageManager;
    private $templateManager;
    
    protected function setUp()
    {
        parent::setUp();

        $this->languageRepository = 
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->languageManager = 
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->pageRepository = 
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->pageManager = 
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->template = 
            $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->templateManager = 
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $this->siteBoostrap = new AlSiteBootstrap($this->languageManager, $this->pageManager, $this->templateManager);
    }
    
    public function testDeleteExistingLanguageFails()
    {   
        $this->initLanguageManager();
        $this->initPageManager();
        
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
                'MetaTitle' => 'A website made with AlphaLemon CMS',
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
    
    private function initLanguage()
    {
         $language = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlLanguage', array('delete'));
         
         return $language;
    }
    
    private function initPage()
    {
         $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPage', array('delete'));
         
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