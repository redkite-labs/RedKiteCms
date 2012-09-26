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

        $expectedValue = array("HtmlContent" => "<ul><li>En</li></ul>");
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }

    public function testHtmlContent()
    {
        $language = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language->expects($this->once())
            ->method('getLanguage')
            ->will($this->returnValue('en'));

        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $page->expects($this->once())
            ->method('getPageName')
            ->will($this->returnValue('index'));

        $pageTree = $this->getMockBuilder('\AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $pageTree->expects($this->once())
            ->method('getAlPage')
            ->will($this->returnValue($page));

        $this->urlManager->expects($this->any())
            ->method('getInternalUrl')
            ->will($this->returnValue('/alcms.php/backend/a-fancy-permalink'));

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->any())
            ->method('get')
            ->will($this->onConsecutiveCalls($this->eventsHandler, $this->factoryRepository, $pageTree, $this->urlManager));

        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language)));

        $blockManager = new AlBlockManagerNavigationMenu($container);
        $this->assertEquals('<ul><li><a href="/alcms.php/backend/a-fancy-permalink">en</a></li></ul>', $blockManager->getHtmlCmsActive());
    }

    public function testHtmlContentWhenRouteDoesNotExist()
    {
        $language = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language->expects($this->once())
            ->method('getLanguage')
            ->will($this->returnValue('en'));

        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $page->expects($this->once())
            ->method('getPageName')
            ->will($this->returnValue('index'));

        $pageTree = $this->getMockBuilder('\AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $pageTree->expects($this->once())
            ->method('getAlPage')
            ->will($this->returnValue($page));

        $this->urlManager->expects($this->any())
            ->method('getInternalUrl')
            ->will($this->returnValue(null));

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->any())
            ->method('get')
            ->will($this->onConsecutiveCalls($this->eventsHandler, $this->factoryRepository, $pageTree, $this->urlManager));

        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language)));

        $blockManager = new AlBlockManagerNavigationMenu($container);
        $this->assertEquals('<ul><li><a href="#">en</a></li></ul>', $blockManager->getHtmlCmsActive());
    }

    public function testHtmlContentWithMoreLanguages()
    {
        $language1 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language1->expects($this->once())
            ->method('getLanguage')
            ->will($this->returnValue('en'));

        $language2 = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language2->expects($this->once())
            ->method('getLanguage')
            ->will($this->returnValue('es'));

        $page = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $page->expects($this->once())
            ->method('getPageName')
            ->will($this->returnValue('index'));

        $pageTree = $this->getMockBuilder('\AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $pageTree->expects($this->once())
            ->method('getAlPage')
            ->will($this->returnValue($page));

        $this->urlManager->expects($this->any())
            ->method('getInternalUrl')
            ->will($this->onConsecutiveCalls('/alcms.php/backend/a-fancy-permalink', '/alcms.php/backend/another-fancy-permalink'));

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->any())
            ->method('get')
            ->will($this->onConsecutiveCalls($this->eventsHandler, $this->factoryRepository, $pageTree, $this->urlManager));

        $this->languageRepository->expects($this->once())
            ->method('activeLanguages')
            ->will($this->returnValue(array($language1, $language2)));

        $blockManager = new AlBlockManagerNavigationMenu($container);
        $this->assertEquals('<ul><li><a href="/alcms.php/backend/a-fancy-permalink">en</a></li><li><a href="/alcms.php/backend/another-fancy-permalink">es</a></li></ul>', $blockManager->getHtmlCmsActive());
    }
}
