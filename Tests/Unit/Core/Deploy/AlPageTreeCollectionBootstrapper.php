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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Deploy;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlPageTreeCollection;

/**
 * Inits the object required to setup a pageTreeCollection
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
abstract class AlPageTreeCollectionBootstrapper extends TestCase
{
    protected $page1;
    protected $page2;
    protected $language1;
    protected $language2;
    protected $languageRepository;
    protected $pageRepository;
    protected $themeRepository;
    protected $seoRepository;
    protected $template;
    protected $pageBlocks;
    protected $themes;

    protected function initSomeLangugesAndPages()
    {
        // Prepares page and languages
        $this->page1 = $this->setUpPage('index', true);
        $this->page2 = $this->setUpPage('page-1');
        $this->language1 = $this->setUpLanguage('en', true);
        $this->language2 = $this->setUpLanguage('es');

        $this->seoRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\SeoRepositoryInterface');

        $this->languageRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel');
        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($this->language1, $this->language2)));

        $this->languageRepository->expects($this->exactly(4))
            ->method('fromPK')
            ->will($this->onConsecutiveCalls($this->language1, $this->language1, $this->language2, $this->language2));

        $this->pageRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel');
        $this->pageRepository->expects($this->once())
            ->method('activePages')
            ->will($this->returnValue(array($this->page1, $this->page2)));

        $this->pageRepository->expects($this->exactly(4))
            ->method('fromPK')
            ->will($this->onConsecutiveCalls($this->page1, $this->page2, $this->page1, $this->page2));

        $this->themeRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlThemeRepositoryPropel');
        $this->themeRepository->expects($this->any())
            ->method('activeBackend')
            ->will($this->returnValue($this->setUpTheme()));

        // Prepares the template object
        $this->template = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        /*
        $this->template->expects($this->exactly(4))
            ->method('setThemeName')
            ->will($this->returnSelf());

        $this->template->expects($this->exactly(4))
            ->method('setTemplateName')
            ->will($this->returnSelf());*/

        $this->templateSlots = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsInterface');
        $this->template->expects($this->any())
            ->method('getTemplateSlots')
            ->will($this->returnValue($this->templateSlots));

        // Prepares the pageBlocks object
        $this->pageBlocks = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->pageBlocks->expects($this->any())
            ->method('setIdLanguage')
            ->will($this->returnSelf());

        $this->pageBlocks->expects($this->any())
            ->method('setIdPage')
            ->will($this->returnSelf());

        $this->pageBlocks->expects($this->any())
            ->method('refresh')
            ->will($this->returnSelf());

        $this->pageBlocks->expects($this->any())
            ->method('getBlocks')
            ->will($this->returnValue(array("logo" => array($this->setUpBlock('my content')))));

        // Prepares the templateManager object
        $this->templateManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager')
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
        
        $this->themesCollectionWrapper = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\ThemesCollectionWrapper\AlThemesCollectionWrapper')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->themesCollectionWrapper->expects($this->any())
            ->method('assignTemplate')
            ->will($this->returnValue($this->templateManager));
    }

    protected function setUpPage($pageName, $isHome = false)
    {
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
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
            ->method('getTemplateName')
            ->will($this->returnValue('home'));

        return $page;
    }

    protected function setUpLanguage($languageName, $isMain = false)
    {
        $language = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(2));

        $language->expects($this->any())
            ->method('getLanguage')
            ->will($this->returnValue($languageName));

        $language->expects($this->any())
            ->method('getMainLanguage')
            ->will($this->returnValue($isMain));

        return $language;
    }

    protected function setUpSeo($permalink, $page, $language)
    {
        $seo = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo');
        $seo->expects($this->any())
            ->method('getPermalink')
            ->will($this->onConsecutiveCalls($permalink));

        $seo->expects($this->any())
            ->method('getAlPage')
            ->will($this->returnValue($page));

        $seo->expects($this->any())
            ->method('getAlLanguage')
            ->will($this->returnValue($language));

        return $seo;
    }

    protected function setUpTheme()
    {
        $theme = $this->getMock('AlphaLemon\ThemeEngineBundle\Model\AlTheme');
        $theme->expects($this->any()) // $this->once()
            ->method('getThemeName')
            ->will($this->returnValue('FakeTheme'));

        return $theme;
    }

    protected function setUpBlock($content)
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->any())
            ->method('getHtmlContent')
            ->will($this->returnValue($content));

        return $block;
    }
}
