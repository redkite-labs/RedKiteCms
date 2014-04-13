<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Integrated\Model\Propel;

/**
 * PageRepositoryPropelTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class PageRepositoryPropelTest extends Base\BaseModelPropel
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
     * @expectedException RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @expectedExceptionMessage exception_only_propel_page_objects_are_accepted
     */
    public function testRepositoryAcceptsOnlyPageObjects()
    {
        $this->pageRepository->setRepositoryObject(new \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Language());
    }

    public function testAPageIsRetrievedFromItsPrimaryKey()
    {
        $page = $this->pageRepository->fromPk(2);
        $this->assertInstanceOf('\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Page', $page);
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
        $this->assertInstanceOf('\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Page', $page);
    }
}