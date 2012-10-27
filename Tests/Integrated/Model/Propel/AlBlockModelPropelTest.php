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
 *testRetrieveContentsByStrictContent
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Integrated\Model\Propel;

/**
 * AlBlockRepositoryPropelTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockRepositoryPropelTest extends Base\BaseModelPropel
{
    private $blockRepository;

    protected function setUp()
    {
        parent::setUp();
        
        $container = $this->client->getContainer();
        $factoryRepository = $container->get('alpha_lemon_cms.factory_repository');
        $this->blockRepository = $factoryRepository->createRepository('Block');
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException
     * @expectedExceptionMessage AlBlockRepositoryPropel accepts only AlBlock propel objects
     */
    public function testRepositoryAcceptsOnlyAlBlockObjects()
    {
        $this->blockRepository->setRepositoryObject(new \AlphaLemon\AlphaLemonCmsBundle\Model\AlPage());
    }

    public function testABlockIsRetrievedFromItsPrimaryKey()
    {
        $block = $this->blockRepository->fromPk(2);
        $this->assertInstanceOf('\AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock', $block);
        $this->assertEquals(2, $block->getId());
    }

    public function testRetrieveContentsWithoutRepeatedOnes()
    {
        $blocks = $this->blockRepository->retrieveContents(2, 2);
        $this->assertEquals(11, count($blocks));
    }

    public function testRetrieveAllPageContents()
    {
        $blocks = $this->blockRepository->retrieveContents(array(1, 2), array(1, 2));
        $this->assertEquals(22, count($blocks));
    }

    public function testRetrieveSlotContents()
    {
        $blocks = $this->blockRepository->retrieveContents(array(1, 2), array(1, 2), 'logo');
        $this->assertEquals(1, count($blocks));
        $this->assertEquals('logo', $blocks[0]->getSlotName());
    }

    public function testRetrieveContentsRepeatedAtSiteLevelBySlotName()
    {
        $blocks = $this->blockRepository->retrieveContentsBySlotName('logo');
        $this->assertEquals(1, count($blocks));
    }

    public function testRetrieveContentsRepeatedAtLanguageLevelBySlotName()
    {
        $blocks = $this->blockRepository->retrieveContentsBySlotName('nav_menu');
        $this->assertEquals(2, count($blocks));
    }

    public function testRetrieveContentsRepeatedAtPageLevelBySlotName()
    {
        $blocks = $this->blockRepository->retrieveContentsBySlotName('right_sidebar_content');
        $this->assertEquals(2, count($blocks));
    }

    public function testRetrieveContentsByLanguageId()
    {
        $blocks = $this->blockRepository->fromLanguageId(2);
        $this->assertEquals(21, count($blocks));
    }

    public function testRetrieveAllContentsByLanguageId()
    {
        $blocks = $this->blockRepository->fromLanguageId(array(1, 2));
        $this->assertEquals(23, count($blocks));
    }

    public function testRetrieveContentsByPageId()
    {
        $blocks = $this->blockRepository->fromPageId(2);
        $this->assertEquals(22, count($blocks));
    }

    public function testRetrieveAllContentsByPageId()
    {
        $blocks = $this->blockRepository->fromPageId(array(1, 2));
        $this->assertEquals(42, count($blocks));
    }

    public function testRetrieveContentsByContent()
    {
        $blocks = $this->blockRepository->fromContent('<h4>');
        $this->assertEquals(8, count($blocks));
    }

    public function testRetrieveContentsByStrictContent()
    {
        $blocks = $this->blockRepository->fromContent('logo.png');
        $this->assertEquals(1, count($blocks));
    }

    public function testRetrieveContentsByType()
    {
        $blocks = $this->blockRepository->fromType('Text');
        $this->assertCount(30, $blocks);
    }

    public function testRetrieveNumberOfContentsByType()
    {
        $blocks = $this->blockRepository->fromType('Text', 'count');
        $this->assertEquals(30, $blocks);
    }
}