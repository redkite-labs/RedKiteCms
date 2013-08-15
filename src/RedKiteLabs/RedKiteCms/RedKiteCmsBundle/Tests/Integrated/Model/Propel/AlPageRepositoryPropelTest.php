<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Integrated\Model\Propel;

/**
 * AlPageRepositoryPropelTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlPageRepositoryPropelTest extends Base\BaseModelPropel
{
    private $pageRepository;

    protected function setUp()
    {
        parent::setUp();

        $container = $this->client->getContainer();
        $factoryRepository = $container->get('red_kite_cms.factory_repository');
        $this->pageRepository = $factoryRepository->createRepository('Page');
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @expectedExceptionMessage AlPageRepositoryPropel accepts only AlPage propel objects
     */
    public function testRepositoryAcceptsOnlyAlPageObjects()
    {
        $this->pageRepository->setRepositoryObject(new \RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage());
    }

    public function testAPageIsRetrievedFromItsPrimaryKey()
    {
        $page = $this->pageRepository->fromPk(2);
        $this->assertInstanceOf('\RedKiteLabs\RedKiteCmsBundle\Model\AlPage', $page);
        $this->assertEquals(2, $page->getId());
    }

    public function testFetchTheActivePages()
    {
        $pages = $this->pageRepository->activePages();
        $this->assertEquals(2, count($pages));
    }

    public function testPageIsNullWhenANullValueIsGiven()
    {
        $page = $this->pageRepository->fromPageName(null);
        $this->assertNull($page);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAnExceptionIsThrownWhenTheGivenParameterIsNotString()
    {
        $this->pageRepository->fromPageName(array('page1'));
    }

    public function testThePageIsRetrieved()
    {
        $pageName = 'page1';
        $page = $this->pageRepository->fromPageName($pageName);
        $this->assertEquals($pageName, $page->getPageName());
    }

    public function testTheHomePageIsRetrieved()
    {
        $page = $this->pageRepository->homePage();
        $this->assertEquals('index', $page->getPageName());
    }
    
    public function testPagesAreRetrievedFromTemplateName()
    {
        $page = $this->pageRepository->fromTemplateName('home');
        $this->assertCount(1, $page);
    }
    
    public function testOnlyFirstPageIsRetrievedFromTemplateName()
    {
        $page = $this->pageRepository->fromTemplateName('home', true);
        $this->assertInstanceOf('\RedKiteLabs\RedKiteCmsBundle\Model\AlPage', $page);
    }
}