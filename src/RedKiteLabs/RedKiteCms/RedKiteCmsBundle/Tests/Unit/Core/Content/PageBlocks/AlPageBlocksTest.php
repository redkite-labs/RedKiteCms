<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\PageBlocks;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocks;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General;

/**
 * AlPageBlocksTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlPageBlocksTest extends TestCase
{
    private $blockRepository;
    private $pageContentsContainer;

    protected function setUp()
    {
        parent::setUp();

        $this->blockRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));

        $this->pageContentsContainer = new AlPageBlocks($this->factoryRepository);
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentIsEmptyException
     */
    public function testRefreshThrownAnExceptionWhenPageAndLanguageHaveNotBeenSet()
    {
        $this->pageContentsContainer->refresh();
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @expectedException The language id must be a numeric value
     */
    public function testLanguageIdMustBeAnInteger()
    {
        $this->pageContentsContainer->setIdLanguage('fake');
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @expectedException The page id must be a numeric value
     */
    public function testPageIdMustBeAnInteger()
    {
        $this->pageContentsContainer->setIdPage('fake');
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentIsEmptyException
     */
    public function testRefreshThrownAnExceptionWhenPageHaveNotBeenSet()
    {
        $this->pageContentsContainer
                ->setIdLanguage(2)
                ->refresh();
    }

    /**
     * @expectedException RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentIsEmptyException
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
        $this->assertEquals(2, $this->pageContentsContainer->getIdLanguage());
        $this->assertEquals(2, $this->pageContentsContainer->getIdPage());
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
        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('getSlotName')
            ->will($this->returnValue($slotName));

        return $block;
    }
}
