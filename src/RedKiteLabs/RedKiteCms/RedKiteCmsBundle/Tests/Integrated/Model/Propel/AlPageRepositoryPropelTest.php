<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Integrated\Model\Propel;

/**
 * AlPageRepositoryPropelTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlPageRepositoryPropelTest extends Base\BaseModelPropel
{
    private $pageRepository;

    protected function setUp()
    {
        parent::setUp();

        $container = $this->client->getContainer();
        $factoryRepository = $container->get('alpha_lemon_cms.factory_repository');
        $this->pageRepository = $factoryRepository->createRepository('Page');
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException
     * @expectedExceptionMessage AlPageRepositoryPropel accepts only AlPage propel objects
     */
    public function testRepositoryAcceptsOnlyAlPageObjects()
    {
        $this->pageRepository->setRepositoryObject(new \AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage());
    }

    public function testAPageIsRetrievedFromItsPrimaryKey()
    {
        $page = $this->pageRepository->fromPk(2);
        $this->assertInstanceOf('\AlphaLemon\AlphaLemonCmsBundle\Model\AlPage', $page);
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
        $this->assertInstanceOf('\AlphaLemon\AlphaLemonCmsBundle\Model\AlPage', $page);
    }
}