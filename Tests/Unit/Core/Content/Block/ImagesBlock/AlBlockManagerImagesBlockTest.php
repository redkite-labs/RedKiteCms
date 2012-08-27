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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block\ImagesBlock;

use AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block\Base\AlBlockManagerContainerBase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\ImagesBlock\AlBlockManagerImages;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;

/**
 * AlBlockManagerJsonBlockTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerImagesBlockTest extends AlBlockManagerContainerBase
{   
    private $blockManager;
    
    protected function setUp()
    {
        parent::setUp();

        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $this->validator = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->blockRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
    }


    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\Exception\InvalidJsonFormatException
     */
    public function testAnExceptionIsThrownWhenTheSavedJsonContentIsNotDecodable()
    {
        $htmlContent = '{
            "0" : {
                "image" : "/path/to/image"
            },
        }';
        $block = $this->initBlock(2, $htmlContent);

        $this->initContainer();
        $params = array('AddFile' => "/new/path/to/image");
        $this->blockManager = new AlBlockManagerImagesBlockTester($this->container, $this->validator);
        $this->blockManager->set($block)
                           ->save($params);
    }

    public function testANewImageHasBeenAddedToImagesBlock()
    {
        $this->container->expects($this->exactly(3))
                        ->method('get')
                        ->will($this->onConsecutiveCalls($this->dispatcher,$this->factoryRepository, $this->kernel));

        $this->container->expects($this->exactly(1))
                        ->method('getParameter');

        $block = $this->initBlock();
        $params = array('AddFile' => "/new/path/to/image");
        $this->blockManager = new AlBlockManagerImagesBlockTester($this->container, $this->validator);
        $this->doSave($block, $params);
    }

    public function testAnImageHasBeenRemovedFromImagesBlock()
    {
        $this->initContainer();

        $this->container->expects($this->exactly(2))
                        ->method('getParameter');

        $block = $this->initBlock();
        $params = array('RemoveFile' => "/path/to/image");
        $this->blockManager = new AlBlockManagerImagesBlockTester($this->container, $this->validator);
        $this->doSave($block, $params);
    }

    private function initBlock($id = null, $htmlContent = null)
    {
        if (null === $id) $id = 2;
        if (null === $htmlContent) $htmlContent = '{
            "0" : {
                "image" : "/path/to/image"
            }
        }';

        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
                ->method('getId')
                ->will($this->returnValue($id));

        $block->expects($this->any())
                ->method('getHtmlContent')
                ->will($this->returnValue($htmlContent));

        return $block;
    }

    private function doSave($block, array $params)
    {
        $this->dispatcher->expects($this->exactly(2))
            ->method('dispatch');

        $this->blockRepository->expects($this->once())
            ->method('startTransaction');

        $this->blockRepository->expects($this->once())
            ->method('commit');

        $this->blockRepository->expects($this->never())
            ->method('rollback');

        $this->blockRepository->expects($this->once())
                ->method('save')
                ->will($this->returnValue(true));

         $this->blockRepository->expects($this->once())
                ->method('setRepositoryObject')
                ->with($block);

        $result = $this->blockManager->set($block)
                                     ->save($params);
        $this->assertEquals(true, $result);
    }
}

class AlBlockManagerImagesBlockTester extends AlBlockManagerImages
{
    public function getDefaultValue()
    {
        $defaultContent =
        '{
            "0" : {
                "image" : "path/to/image"
            }
        }';

        return array("HtmlContent" => $defaultContent);
    }
}