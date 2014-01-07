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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\PageTree\DataManager;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\PageTree\DataManager\DataManager;

/**
 * DataManagerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class DataManagerTest extends TestCase
{
    protected $factoryRepository;

    protected function setUp()
    {
        parent::setUp();
        
        $this->factoryRepository = $this->getMock("RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface");
        $this->dataManager = new DataManager($this->factoryRepository);
    }
    
    /**
     * @dataProvider seoProvider
     */
    public function testFromSeo($options)
    {
        $this->fromSeo($options);
        
        $this->dataManager->fromOptions($options);
        $this->doTest();
    }
    
    /**
     * @dataProvider languagesAndPagesProvider
     */
    public function testFromLanguageAndPageNames($options)
    {
        $this->fromLanguageAndPageNames($options);
        
        $this->dataManager->fromOptions($options);
        $this->doTest();
    }
    
    /**
     * @dataProvider seoRequestProvider
     */
    public function testFromSeoRequest($options)
    {
        $request = $this->createRequest($options);
        
        $this->fromSeo($options);
        
        $this->dataManager->fromRequest($request);
        $this->doTest();
    } 
    
    /**
     * @dataProvider seoRequestProvider
     */
    public function testFromLanguageAndPageRequest($options)
    {
        $request = $this->createRequest($options);
        
        $this->fromLanguageAndPageNames($options);
        
        $this->dataManager->fromRequest($request);
        $this->doTest();
    } 
    
    /**
     * @dataProvider entitiesProvider
     */
    public function testFromEntities($entities)
    {   
        $this->language = $entities["language"];
        $this->page = $entities["page"];        
        $this->seo = $entities["seo"];
        
        if (null !== $this->page) {
            $this->language->expects($this->once())
                ->method('getId')
                ->will($this->returnValue(2))
            ;
            
            $this->page->expects($this->once())
                ->method('getId')
                ->will($this->returnValue(3))
            ;
            
            $seoRepository = $this->initSeoRepository(0);
            $seoRepository->expects($this->once())
                ->method('fromPageAndLanguage')
                ->with(2, 3)
                ->will($this->returnValue($this->seo))
            ;     

            $seoRepository->expects($this->never())
                ->method('fromPermalink')
            ;       
        } else {
            $this->factoryRepository->expects($this->never())
                ->method('createRepository');
        }
        
        $this->dataManager->fromEntities($this->language, $this->page);
        $this->doTest();
    } 
    
    public function seoProvider()
    {
        return array(
            array(
                array(
                    "pageId" => 2,            
                    "languageId" => 2,
                    "languageName" => "en",
                    "pageName" => "index",
                ),
            ),
            array(
                array(
                    "pageId" => 0,            
                    "languageId" => 0,
                    "languageName" => "welcome-to-our-website",
                    "pageName" => "index",
                ),
            ),
            array(
                array(
                    "pageId" => 0,            
                    "languageId" => 0,
                    "languageName" => "en",
                    "pageName" => "welcome-to-our-website",
                ),
            ),
        );
    }
    
    public function languagesAndPagesProvider()
    {
        return array(
            array(
                array(
                    "pageId" => 0,            
                    "languageId" => 0,
                    "pageName" => "index",            
                    "languageName" => "en",
                ),
            ),
            array(
                array(
                    "pageId" => 0,            
                    "languageId" => 0,
                    "pageName" => "backend",            
                    "languageName" => "en",
                ),
            ),
        );
    }
    
    public function seoRequestProvider()
    {
        return array(
           array(
                array(
                    "pageId" => 0,            
                    "languageId" => 0,              
                    "_locale" => "en",
                    "page" => "index",              
                    "languageName" => "en",
                    "pageName" => "index", 
                ),
            ),
            array(
                array(   
                    "pageId" => 0,            
                    "languageId" => 0,                  
                    "_locale" => "welcome-to-our-website",
                    "page" => "index",             
                    "languageName" => "welcome-to-our-website",
                    "pageName" => "index", 
                ),
            ),
            array(
                array(   
                    "pageId" => 0,            
                    "languageId" => 0,                  
                    "_locale" => "en",
                    "page" => "welcome-to-our-website",             
                    "languageName" => "en",
                    "pageName" => "welcome-to-our-website", 
                ),
            ),
            array(
                 array(
                     "pageId" => 2,            
                     "languageId" => 2,              
                     "_locale" => "en",
                     "page" => "index",              
                    "languageName" => "en",
                    "pageName" => "index", 
                 ),
             ),
            array(
                array(   
                    "pageId" => 0,            
                    "languageId" => 0,                  
                    "_locale" => "welcome-to-our-website",
                    "page" => "backend",             
                    "languageName" => "welcome-to-our-website",
                    "pageName" => "backend", 
                ),
            ),
        );
    }
    
    public function entitiesProvider()
    {
        return array(
            array(
                array(
                    "language" => $this->createLanguage(),            
                    "page" => $this->createPage(),       
                    "seo" => $this->createSeo(),
                ),
            ),
            array(
                array(
                    "language" => $this->createLanguage(),            
                    "page" => null,       
                    "seo" => null,
                ),
            ),
        );
    }
    
    private function fromSeo($options)
    {
       $this->seo = $this->createSeo();
       $seoRepository = $this->initSeoRepository(0);

       if (array_key_exists("languageId", $options) && $options["languageId"] !== 0) {
           $seoRepository->expects($this->once())
               ->method('fromPageAndLanguage')
               ->with($options["languageId"], $options["pageId"])
               ->will($this->returnValue($this->seo))
           ;

           $seoRepository->expects($this->never())
               ->method('fromPermalink')
           ;
       } else {
            $seoRepository->expects($this->never())
            ->method('fromPageAndLanguage')
            ;
           
            $seo = $this->seo;
            if ($options["languageName"] != "welcome-to-our-website") {
                 $seo = null;
            }
           
            $seoRepository->expects($this->at(0))
                 ->method('fromPermalink')
                 ->with($options["languageName"])
                 ->will($this->returnValue($seo))
            ;
           
            if (null === $seo) {
                $seoRepository->expects($this->at(1))
                     ->method('fromPermalink')
                     ->with($options["pageName"])
                     ->will($this->returnValue($this->seo))
                ;
            }
       }

       $this->language = $this->createLanguage();
       $this->seo->expects($this->once())
           ->method('getAlLanguage')
           ->will($this->returnValue($this->language))
       ;

       $this->page = $this->createPage();
       $this->seo->expects($this->once())
           ->method('getAlPage')
           ->will($this->returnValue($this->page))
       ;
    }

    private function fromLanguageAndPageNames($options)
    {
        $seoRepository = $this->initSeoRepository(0);        
        $languageRepository = $this->initLanguageRepository(1);
        
        $seoRepository->expects($this->any())
            ->method('fromPermalink')
            ->will($this->returnValue(null))
        ;
        
        $pageName = (array_key_exists('pageName', $options)) ? $options["pageName"] : $options["page"];
        $languageName = (array_key_exists('languageName', $options)) ? $options["languageName"] : $options["_locale"];
        
        $this->seo = null;
        $this->page = null;
        if ($pageName != "backend") {
            $this->page = $this->createPage();
            
            $pageRepository = $this->initPageRepository(2);
            $pageRepository->expects($this->once())
                ->method('fromPageName')
                ->with($pageName)
                ->will($this->returnValue($this->page))
            ;
        }
        
        $this->language = $this->createLanguage();
        $languageRepository->expects($this->once())
            ->method('fromLanguageName')
            ->with($languageName)
            ->will($this->returnValue($this->language))
        ;
    }
    
    private function createRequest($options)
    {
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $request->expects($this->at(0))
           ->method('get')
           ->with('page')     
           ->will($this->returnValue($options["page"]))
        ;
        
        $request->expects($this->at(1))
           ->method('get')
           ->with('_locale')     
           ->will($this->returnValue($options["_locale"]))
        ;
        
        $request->expects($this->at(2))
           ->method('get')
           ->with('pageId')     
           ->will($this->returnValue($options["pageId"]))
        ;
        
        $request->expects($this->at(3))
           ->method('get')
           ->with('languageId')     
           ->will($this->returnValue($options["languageId"]))
        ;
        
        return $request;
    }

    private function doTest()
    {
        $this->assertSame($this->seo, $this->dataManager->getSeo());
        $this->assertSame($this->language, $this->dataManager->getLanguage());
        $this->assertSame($this->page, $this->dataManager->getPage());
    }

    private function initLanguageRepository($at = null)
    {
        $languageRepository = $this->getMock("RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface");
        
        $this->factoryRepository->expects($this->at($at))
            ->method('createRepository')
            ->with('Language')
            ->will($this->returnValue($languageRepository))
        ;
        
        return $languageRepository;
    }
    
    private function initPageRepository($at = null)
    {
        $pageRepository = $this->getMock("RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\PageRepositoryInterface");
        
        $this->factoryRepository->expects($this->at($at))
            ->method('createRepository')
            ->with('Page')
            ->will($this->returnValue($pageRepository))
        ;
        
        return $pageRepository;
    }
    
    private function initSeoRepository($at = null)
    {
        $seoRepository = $this->getMock("RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\SeoRepositoryInterface");
        
        $this->factoryRepository->expects($this->at($at))
            ->method('createRepository')
            ->with('Seo')
            ->will($this->returnValue($seoRepository))
        ;
        
        return $seoRepository;
    }
    
    private function createLanguage()
    {
        return $this->getMock("RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage");
    }
    
    private function createPage()
    {
        return $this->getMock("RedKiteLabs\RedKiteCmsBundle\Model\AlPage");
    }
    
    private function createSeo()
    {
        return $this->getMock("RedKiteLabs\RedKiteCmsBundle\Model\AlSeo");
    }
}