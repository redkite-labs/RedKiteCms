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
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactory;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlPage;

use Symfony\Bundle\AsseticBundle\Tests\TestKernel;

/**
 * AlBlockManagerFactoryTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerFactoryTest extends TestCase
{
    protected function setUp()
    {
        $this->blockManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText')
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->factory = new AlBlockManagerFactory();
    }

    public function testCreateBlockManagerReturnsNullWhenFactoryHasAnyBaseBlockManager()
    {
        $blockManager = $this->factory->createBlockManager('Fake');
        $this->assertNull($blockManager);

        $blocks = $this->factory->getBlocks();
        $this->assertEquals(0, count($blocks));
    }

    public function testCreateBlockManagerReturnsNullWhenFactoryDoesNotKnowTheBlockManagerType()
    {
        $this->setupBaseBlockManager();

        $blockManager = $this->factory->createBlockManager('Fake');
        $this->assertNull($blockManager);
    }

    public function testGetBlocksReturnsTheBlockManagerDescription()
    {
        $this->setupBaseBlockManager();

        $blocks = $this->factory->getBlocks();
        $this->assertEquals(1, count($blocks));
        $this->assertEquals('Html text content', $blocks[0]);
    }

    public function testCreateBlockManagerFromAValidContentTypeReturnsTheNewBlockManager()
    {
        $this->setupBaseBlockManager();

        $blockManager = $this->factory->createBlockManager('Text');
        $this->assertInstanceOf('\AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $blockManager);
    }

    public function testCreateBlockManagerFromAnUnknownBlockDeletesTheBlockAndReturnsNull()
    {
        $this->setupBaseBlockManager();

        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
                        ->method('getClassName')
                        ->will($this->returnValue('Fake'));

        $this->blockManager->expects($this->once())
                        ->method('getBlockModel')
                        ->will($this->returnValue($this->blockModel));
        
        $this->blockModel->expects($this->once())
                        ->method('setModelObject')
                        ->with($block);

        $this->blockModel->expects($this->once())
                        ->method('delete');

        $blockManager = $this->factory->createBlockManager($block);
        $this->assertNull($blockManager);
    }

    public function testCreateBlockManagerFromAKnownBlockReturnsTheNewBlockManager()
    {
        $this->setupBaseBlockManager();

        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
                        ->method('getClassName')
                        ->will($this->returnValue('Text'));

        $blockManager = $this->factory->createBlockManager($block);
        $this->assertInstanceOf('\AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $blockManager);
    }

    public function testCreateBlockManagerWithTranslator()
    {
        $translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $this->factory = new AlBlockManagerFactory($translator);
        $this->setupBaseBlockManager();

        $this->blockManager->expects($this->once())
                        ->method('setTranslator')
                        ->with($translator);

        $this->blockManager->expects($this->any())
                        ->method('getTranslator')
                        ->will($this->returnValue($translator));

        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
                        ->method('getClassName')
                        ->will($this->returnValue('Text'));

        $blockManager = $this->factory->createBlockManager($block);
        $this->assertInstanceOf('\AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Core\Block\AlBlockManagerText', $blockManager);
        $this->assertInstanceOf('Symfony\Component\Translation\TranslatorInterface', $blockManager->getTranslator());
    }

    private function setupBaseBlockManager()
    {
        $this->blockModel = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factory->addBlockManager($this->blockManager, array('id' => 'app_text.block', 'description' => 'Html text content'));
    }

/*
    TODO!!!!

    public function testCreatingFromARemovedBlockObjectDeletesTheBlock()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');

        $block->expects($this->once())
                ->method('setToDelete');

        $block->expects($this->once())
                ->method('save');

        $block->expects($this->any())
                ->method('getToDelete')
                ->will($this->returnValue(1));

        $contenManager = $this->factory->createBlock($this->blockModel, $block);
        $this->assertNull($contenManager);
        $this->assertEquals(1, $block->getToDelete());
    }

    public function testCreateBlockWithTranslator()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
                ->method('getClassName')
                ->will($this->returnValue('Text'));

        $translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');

        $factory = new AlBlockManagerFactory($this->dispatcher, $translator);
        $contenManager = $factory->createBlock($this->blockModel, $block);
        $this->assertInstanceOf('Symfony\Component\Translation\TranslatorInterface', $contenManager->getTranslator());
    }*/
}