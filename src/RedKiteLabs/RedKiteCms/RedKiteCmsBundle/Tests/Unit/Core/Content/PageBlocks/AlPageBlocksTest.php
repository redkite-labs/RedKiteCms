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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\PageBlocks;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;

/**
 * AlPageBlocksTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlPageBlocksTest extends TestCase
{
    private $dispatcher;
    private $blockRepository;
    private $pageContentsContainer;

    protected function setUp()
    {
        parent::setUp();

        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->blockRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));

        $this->pageContentsContainer = new AlPageBlocks($this->dispatcher, $this->factoryRepository);
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterIsEmptyException
     */
    public function testRefreshThrownAnExceptionWhenPageAndLanguageHaveNotBeenSet()
    {
        $this->pageContentsContainer->refresh();
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterIsEmptyException
     */
    public function testRefreshThrownAnExceptionWhenPageHaveNotBeenSet()
    {
        $this->pageContentsContainer
                ->setIdLanguage(2)
                ->refresh();
    }

    /**
     * @expectedException AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterIsEmptyException
     */
    public function testRefreshThrownAnExceptionWhenLanguageHaveNotBeenSet()
    {
        $this->pageContentsContainer
                ->setIdPage(2)
                ->refresh();
    }

    public function testAnEmptyArrayIsRetrievedWhenAnyBlockExists()
    {
        $this->blockRepository->expects($this->once())
            ->method('retrieveContents')
            ->will($this->returnValue(array()));


        $this->pageContentsContainer
                ->setIdLanguage(2)
                ->setIdPage(2)
                ->refresh();

        $this->assertEquals(0, count($this->pageContentsContainer->getBlocks()));
    }

    public function testContentsAreRetrieved()
    {
        $blocks = array(
            $this->setUpBlock('logo'),
            $this->setUpBlock('logo'),
            $this->setUpBlock('menu'),
        );

        $this->blockRepository->expects($this->once())
            ->method('retrieveContents')
            ->will($this->returnValue($blocks));


        $this->pageContentsContainer
                ->setIdLanguage(2)
                ->setIdPage(2)
                ->refresh();

        $this->assertEquals(2, count($this->pageContentsContainer->getBlocks()));
        $this->assertEquals(2, count($this->pageContentsContainer->getSlotBlocks('logo')));
        $this->assertEquals(1, count($this->pageContentsContainer->getSlotBlocks('menu')));
    }

    private function setUpBlock($slotName)
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('getSlotName')
            ->will($this->returnValue($slotName));

        return $block;
    }
}