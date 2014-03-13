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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\PageTree\DataManager;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\DataManager\DataManager;

/**
 * DataManagerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class DataManagerTest extends TestCase
{
    protected $factoryRepository;
    private $seo;
    private $page;
    private $language;

    protected function setUp()
    {
        parent::setUp();
        
        $this->factoryRepository = $this->getMock("RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface");
        $this->dataManager = new DataManager($this->factoryRepository);
    }
    
    /**
     * @dataProvider seoProvider
     */
    public function testFromSeo($options, $queryResults)
    {
        $this->fromSeo($options, $queryResults);
        
        $this->dataManager->fromOptions($options);
        $this->doTest();
    }
    
    /**
     * @dataProvider seoRequestProvider
     */
    public function testFromSeoRequest($options, $queryResults)
    {
        $request = $this->createRequest($options);
        
        $this->fromSeo($options, $queryResults);
        
        if (null !== $this->seo) {
            $this->language = $this->createLanguage();
            $this->page = $this->createPage();

            $this->seo->expects($this->once())
                ->method('getAlLanguage')
                ->will($this->returnValue($this->language))
            ;
            $this->seo->expects($this->once())
                ->method('getAlPage')
                ->will($this->returnValue($this->page))
            ;
        }
        
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
        return array(/*
            array(
                array(
                    "pageId" => 0,            
                    "languageId" => 2,
                    "languageName" => "en",
                    "pageName" => "page-removed",
                ),
                array(
                    "fromPageAndLanguage" => null,
                    "fromLanguageAndPageNames" => null,
                    "fromPermalinkLanguage" => null,
                    "fromPermalinkPage" => null,
                ),
            ),*/
            array(
                array(
                    "pageId" => 2,            
                    "languageId" => 2,
                    "languageName" => "en",
                    "pageName" => "index",
                ),
                array(
                    "fromPageAndLanguage" => $this->createSeo(),
                ),
            ),
            array(
                array(
                    "pageId" => 0,            
                    "languageId" => 0,
                    "languageName" => "en",
                    "pageName" => "index",
                ),
                array(
                    "fromLanguageAndPageNames" => $this->createSeo(),
                ),
            ),
            array(
                array(
                    "pageId" => 0,            
                    "languageId" => 0,
                    "languageName" => "welcome-to-our-website",
                    "pageName" => "index",
                ),
                array(
                    "fromLanguageAndPageNames" => null,
                    "fromPermalinkLanguage" => $this->createSeo(),
                ),
            ),
            array(
                array(
                    "pageId" => 0,            
                    "languageId" => 0,
                    "languageName" => "en",
                    "pageName" => "welcome-to-our-website",
                ),
                array(
                    "fromLanguageAndPageNames" => null,
                    "fromPermalinkLanguage" => null,
                    "fromPermalinkPage" => $this->createSeo(),
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
                    "languageId" => 2,
                    "_locale" => "en",
                    "page" => "page-removed",
                    "languageName" => "en",
                    "pageName" => "page-removed",
                ),
                array(
                    "fromPageAndLanguage" => null,
                    "fromLanguageAndPageNames" => null,
                    "fromPermalinkLanguage" => null,
                    "fromPermalinkPage" => null,
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
                array(
                    "fromPageAndLanguage" => $this->createSeo(),
                ),
            ),
            array(
                array(
                    "pageId" => 0,            
                    "languageId" => 0,
                    "_locale" => "en",
                    "page" => "index",
                    "languageName" => "en",
                    "pageName" => "index",
                ),
                array(
                    "fromLanguageAndPageNames" => $this->createSeo(),
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
                array(
                    "fromLanguageAndPageNames" => null,
                    "fromPermalinkLanguage" => $this->createSeo(),
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
                array(
                    "fromLanguageAndPageNames" => null,
                    "fromPermalinkLanguage" => null,
                    "fromPermalinkPage" => $this->createSeo(),
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
                    "seo" => $this->getMock("RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlSeo"),
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
    
    private function fromSeo($options, $queryResults)
    {
        $seoRepository = $this->initSeoRepository(0);
        if ($options["languageId"] != 0 && $options["pageId"] != 0) {
            $this->seo = $queryResults["fromPageAndLanguage"];
            $seoRepository->expects($this->once())
                ->method('fromPageAndLanguage')
                ->with($options["languageId"], $options["pageId"])
                ->will($this->returnValue($this->seo))
            ;
            
            return;
        }
        
        $this->seo = $queryResults["fromLanguageAndPageNames"];
        $seoRepository->expects($this->once())
            ->method('fromLanguageAndPageNames')
            ->with($options["languageName"], $options["pageName"])
            ->will($this->returnValue($this->seo))
        ;
        if (null !== $this->seo) {
            return;
        }
        
        $this->seo = $queryResults["fromPermalinkLanguage"];
        $seoRepository->expects($this->at(1))
            ->method('fromPermalink')
            ->with($options["languageName"])
            ->will($this->returnValue($this->seo))
        ;
        if (null !== $this->seo) {
            return;
        }
        
        $this->seo = $queryResults["fromPermalinkPage"];
        $seoRepository->expects($this->at(2))
            ->method('fromPermalink')
            ->with($options["pageName"])
            ->will($this->returnValue($this->seo))
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
    
    private function initSeoRepository($at = null)
    {
        $seoRepository = $this->getMock("RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\SeoRepositoryInterface");
        
        $this->factoryRepository->expects($this->at($at))
            ->method('createRepository')
            ->with('Seo')
            ->will($this->returnValue($seoRepository))
        ;
        
        return $seoRepository;
    }
    
    private function createLanguage()
    {
        return $this->getMock("RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlLanguage");
    }
    
    private function createPage()
    {
        return $this->getMock("RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlPage");
    }
    
    private function createSeo()
    {
        return $this->getMock("RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlSeo");
        
        
        return $seo;
    }
}