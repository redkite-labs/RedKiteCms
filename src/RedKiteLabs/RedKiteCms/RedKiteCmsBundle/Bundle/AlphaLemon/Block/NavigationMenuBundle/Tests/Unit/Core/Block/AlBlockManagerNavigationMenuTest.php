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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\Block\NavigationMenuBundle\Core\Block\AlBlockManagerNavigationMenu;

/**
 * AlBlockManagerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerNavigationMenuTest extends TestCase
{
    protected function setUp()
    {
        $this->eventsHandler = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface');

        $this->languageRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->languageRepository));

        $this->urlManager = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Core\UrlManager\AlUrlManagerInterface');
        $this->urlManager->expects($this->any())
            ->method('buildInternalUrl')
            ->will($this->returnSelf());
    }

    public function testDefaultValue()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->any())
            ->method('get')
            ->will($this->onConsecutiveCalls($this->eventsHandler, $this->factoryRepository));

        $blockManager = new AlBlockManagerNavigationMenu($container);

        $expectedValue = array("Content" => "<ul><li>En</li></ul>");
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }

    public function testHtml()
    {
        $language = $this->initLanguage();
        $pageTree = $this->initPageTree();
        $this->initUrlManager('/alcms.php/backend/a-fancy-permalink');
        $container = $this->initContainer($pageTree);

        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language)));

        $blockManager = new AlBlockManagerNavigationMenu($container);
        $this->assertEquals('<ul><li><a href="/alcms.php/backend/a-fancy-permalink">en</a></li></ul>', $blockManager->getHtml());
    }

    public function testHtmlWhenRouteDoesNotExist()
    {
        $language = $this->initLanguage();
        $pageTree = $this->initPageTree();
        $this->initUrlManager(null);
        $container = $this->initContainer($pageTree);

        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language)));

        $blockManager = new AlBlockManagerNavigationMenu($container);
        $this->assertEquals('<ul><li><a href="#">en</a></li></ul>', $blockManager->getHtml());
    }

    public function testHtmlWithMoreLanguages()
    {
        $language1 = $this->initLanguage();
        $language2 = $this->initLanguage('es');
        $pageTree = $this->initPageTree();
        $container = $this->initContainer($pageTree);
        
        $this->urlManager->expects($this->exactly(2))
            ->method('buildInternalUrl')
            ->will($this->returnSelf());
        
        $this->urlManager->expects($this->exactly(2))
            ->method('getInternalUrl')
            ->will($this->onConsecutiveCalls('/alcms.php/backend/a-fancy-permalink', '/alcms.php/backend/another-fancy-permalink'));
        
        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language1, $language2)));

        $blockManager = new AlBlockManagerNavigationMenu($container);
        $this->assertEquals('<ul><li><a href="/alcms.php/backend/a-fancy-permalink">en</a></li><li><a href="/alcms.php/backend/another-fancy-permalink">es</a></li></ul>', $blockManager->getHtml());
    }
    
    public function testHtmlCmsActive()
    {
        $language = $this->initLanguage();
        $pageTree = $this->initPageTree();
        $this->initUrlManager('/alcms.php/backend/a-fancy-permalink');
        $container = $this->initContainer($pageTree);

        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language)));

        $blockManager = new AlBlockManagerNavigationMenu($container);
        $this->assertEquals('<ul><li><a href="/alcms.php/backend/a-fancy-permalink">en</a></li></ul>', $blockManager->getHtmlCmsActive());
    }

    public function testHtmlCmsActiveWhenRouteDoesNotExist()
    {
        $language = $this->initLanguage();
        $pageTree = $this->initPageTree();
        $this->initUrlManager(null);
        $container = $this->initContainer($pageTree);

        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language)));

        $blockManager = new AlBlockManagerNavigationMenu($container);
        $this->assertEquals('<ul><li><a href="#">en [Er]</a></li></ul>', $blockManager->getHtmlCmsActive());
    }

    public function testHtmlCmsActiveWithMoreLanguages()
    {
        $language1 = $this->initLanguage();
        $language2 = $this->initLanguage('es');
        $pageTree = $this->initPageTree();
        $container = $this->initContainer($pageTree);
        
        $this->urlManager->expects($this->exactly(2))
            ->method('buildInternalUrl')
            ->will($this->returnSelf());
        
        $this->urlManager->expects($this->exactly(2))
            ->method('getInternalUrl')
            ->will($this->onConsecutiveCalls('/alcms.php/backend/a-fancy-permalink', '/alcms.php/backend/another-fancy-permalink'));
        
        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language1, $language2)));

        $blockManager = new AlBlockManagerNavigationMenu($container);
        $this->assertEquals('<ul><li><a href="/alcms.php/backend/a-fancy-permalink">en</a></li><li><a href="/alcms.php/backend/another-fancy-permalink">es</a></li></ul>', $blockManager->getHtmlCmsActive());
    }
    
    private function initUrlManager($value)
    {
        $this->urlManager->expects($this->once())
            ->method('buildInternalUrl')
            ->will($this->returnSelf());
        
        $this->urlManager->expects($this->once())
            ->method('getInternalUrl')
            ->will($this->returnValue($value));
    }
    
    private function initPageTree()
    {
        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $pageTree = $this->getMockBuilder('\AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $pageTree->expects($this->once())
            ->method('getAlPage')
            ->will($this->returnValue($page));
        
        return $pageTree;
    }
    
    private function initLanguage($value = 'en')
    {
        $language = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language->expects($this->once())
            ->method('getLanguageName')
            ->will($this->returnValue($value));
        
        return $language;
    }
    
    private function initContainer($pageTree)
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->at(0))
            ->method('get')
            ->with('alpha_lemon_cms.events_handler')   
            ->will($this->returnValue($this->eventsHandler));
        
        $container->expects($this->at(1))
            ->method('get')
            ->with('alpha_lemon_cms.factory_repository')   
            ->will($this->returnValue($this->factoryRepository));
        
        $container->expects($this->at(2))
            ->method('get')
            ->with('alpha_lemon_cms.url_manager')   
            ->will($this->returnValue($this->urlManager));
        
        $container->expects($this->at(3))
            ->method('get')
            ->with('alpha_lemon_cms.page_tree')   
            ->will($this->returnValue($pageTree));
        
        return $container;
    }
}
