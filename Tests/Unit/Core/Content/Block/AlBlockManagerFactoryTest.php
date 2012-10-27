<?php
/**
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
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactory;

/**
 * AlBlockManagerFactoryTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerFactoryTest extends TestCase
{
    private $factoryRepository;
    private $translator;
    private $blockManager;
    private $blockManagerFactory;

    protected function setUp()
    {
        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');

        $this->blockManagerFactory = new AlBlockManagerFactory($this->factoryRepository, $this->translator);
    }

    public function testTypeOptionIsrequiredToAddANewBlockManager()
    {
        $blockManager = $this->createBlockManager();
        $blockManager->expects($this->never())
                     ->method('setFactoryRepository');

        $this->assertNull($this->blockManagerFactory->addBlockManager($blockManager, array()));
    }

    public function testANewBlockManagerAsBeenAddedToFactory()
    {
        $this->initBlockManager();
        $this->assertCount(1, $this->blockManagerFactory->getBlocks());
    }

    public function testNothigIsCreatedWhenAnyBlockHasBeenAddedToFactory()
    {
        $this->assertNull($this->blockManagerFactory->createBlockManager('Text'));
    }

    public function testFactoryCreateANewBlockManagerFromBlockType()
    {
        $this->initBlockManager();
        $blockManager = $this->blockManagerFactory->createBlockManager('Text');
        $this->assertInstanceOf('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager', $blockManager);
        $this->assertNotSame($this->blockManager, $blockManager);
    }

    public function testFactoryCreateANewBlockManagerFromAnAlBlockObject()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
              ->method('getType')
              ->will($this->returnValue('Text'));
        $this->initBlockManager();
        $blockManager = $this->blockManagerFactory->createBlockManager($block);
        $this->assertInstanceOf('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager', $blockManager);
        $this->assertNotSame($this->blockManager, $blockManager);
    }
    
    public function testFactoryRemovesABlockThatDoesNotExist()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
              ->method('getType')
              ->will($this->returnValue('Removed'));
        $this->initBlockManager();

        $this->blockRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                      ->disableOriginalConstructor()
                                      ->getMock();
        $this->blockRepository->expects($this->once())
                              ->method('setRepositoryObject')
                              ->with($block);

        $this->blockRepository->expects($this->once())
                              ->method('delete');

        $this->blockManager->expects($this->once())
                           ->method('getBlockRepository')
                           ->will($this->returnValue($this->blockRepository));

        $blockManager = $this->blockManagerFactory->createBlockManager($block);
        $this->assertNull($blockManager);
    }

    public function testGetBlocks()
    {
        $attributes = array('id' => 'app_fake.block', 'description' => 'Text block',  'type' => 'Text', 'group' => 'group_1');
        $this->blockManagerFactory->addBlockManager($this->createBlockManager(), $attributes);

        $attributes = array('id' => 'app_fake.block', 'description' => 'Menu block',  'type' => 'Menu', 'group' => 'group_2');
        $this->blockManagerFactory->addBlockManager($this->createBlockManager(), $attributes);

        $attributes = array('id' => 'app_fake.block', 'description' => 'Script block',  'type' => 'Script', 'group' => 'group_1');
        $this->blockManagerFactory->addBlockManager($this->createBlockManager(), $attributes);

        $expectedValue = array(
            'Script' => 'Script block',
            'Text' => 'Text block',
            'Menu' => 'Menu block',
        );

        $this->assertEquals($expectedValue, $this->blockManagerFactory->getBlocks());
    }

    private function initBlockManager(array $attributes = null)
    {
        if (null === $attributes) $attributes = array('id' => 'app_fake.block', 'description' => 'Fake block',  'type' => 'Text');
        $this->blockManager = $this->createBlockManager();
        $this->blockManager->expects($this->once())
                           ->method('setFactoryRepository')
                           ->with($this->factoryRepository);

        $this->blockManagerFactory->addBlockManager($this->blockManager, $attributes);
    }

    private function createBlockManager()
    {
        $blockManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        return $blockManager;
    }
}
