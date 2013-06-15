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
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @expectedExceptionMessage {"message":"AlBlockRepositoryPropel accepts only AlBlock propel objects","domain":"exceptions"}
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
        $this->assertCount(5, $blocks);
    }

    public function testRetrieveAllPageContents()
    {
        $blocks = $this->blockRepository->retrieveContents(array(1, 2), array(1, 2));
        $this->assertCount(24, $blocks);
    }

    public function testRetrieveSlotContents()
    {
        $blocks = $this->blockRepository->retrieveContents(array(1, 2), array(1, 2), 'navbar');
        $this->assertCount(1, $blocks);
        $this->assertEquals('navbar', $blocks[0]->getSlotName());
    }

    public function testRetrieveContentsRepeatedAtSiteLevelBySlotName()
    {
        $blocks = $this->blockRepository->retrieveContentsBySlotName('navbar');
        $this->assertCount(1, $blocks);
    }

    public function testRetrieveContentsRepeatedAtLanguageLevelBySlotName()
    {
        $blocks = $this->blockRepository->retrieveContentsBySlotName('footer_title_1');
        $this->assertCount(2, $blocks);
    }

    public function testRetrieveContentsRepeatedAtPageLevelBySlotName()
    {
        $blocks = $this->blockRepository->retrieveContentsBySlotName('content_title_1');
        $this->assertCount(2, $blocks);
    }

    public function testRetrieveContentsByLanguageId()
    {
        $blocks = $this->blockRepository->fromLanguageId(2);
        $this->assertCount(23, $blocks);
    }

    public function testRetrieveAllContentsByLanguageId()
    {
        $blocks = $this->blockRepository->fromLanguageId(array(1, 2));
        $this->assertCount(26, $blocks);
    }

    public function testRetrieveContentsByPageId()
    {
        $blocks = $this->blockRepository->fromPageId(2);
        $this->assertCount(10, $blocks);
    }

    public function testRetrieveAllContentsByPageId()
    {
        $blocks = $this->blockRepository->fromPageId(array(1, 2));
        $this->assertCount(45, $blocks);
    }

    public function testRetrieveContentsByContent()
    {
        $blocks = $this->blockRepository->fromContent('<h4>');
        $this->assertCount(14, $blocks);
    }

    public function testRetrieveContentsByType()
    {
        $blocks = $this->blockRepository->fromType('Text');
        $this->assertCount(44, $blocks);
    }

    public function testRetrieveNumberOfContentsByType()
    {
        $blocks = $this->blockRepository->fromType('Text', 'count');
        $this->assertEquals(44, $blocks);
    }
    
    public function testDeleteIncludedBlocks()
    {
        $blocks = $this->blockRepository->deleteIncludedBlocks('footer'); // assumes that footer is an included block
        $this->assertEquals(28, $blocks);
    }
    
    public function testDeleteBlocks()
    {
        $this->blockRepository->deleteBlocks(2, 2);
        
        $this->assertCount(5, $this->getDeletedBlocks(2, 2));
        $this->assertCount(0, $this->blockRepository->retrieveContents(2, 2));
    }
    
    public function testBlocksAreRemoved()
    {
        $this->assertCount(5, $this->getDeletedBlocks(2, 3));
        $this->blockRepository->deleteBlocks(2, 3, true);
        $this->assertCount(0, $this->getDeletedBlocks(2, 3));
    }
    
    private function getDeletedBlocks($idLanguage, $idPage)
    {
        return \AlphaLemon\AlphaLemonCmsBundle\Model\AlBlockQuery::create()
                ->filterByPageId($idLanguage)
                ->filterByLanguageId($idPage)
                ->orderBySlotName()
                ->orderByContentPosition()
                ->find();
    }
}