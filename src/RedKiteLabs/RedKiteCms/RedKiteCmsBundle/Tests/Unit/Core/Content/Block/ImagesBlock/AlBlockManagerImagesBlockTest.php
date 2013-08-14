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
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\ImagesBlock;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\Base\AlBlockManagerContainerBase;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\ImagesBlock\AlBlockManagerImages;

/**
 * AlBlockManagerJsonBlockTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlBlockManagerImagesBlockTest extends AlBlockManagerContainerBase
{
    protected $blockManager;

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\Exception\InvalidJsonFormatException
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
        $this->initContainer();
        $this->container->expects($this->once())
                        ->method('getParameter');

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->container->expects($this->at(2))
                        ->method('get')
                        ->with('request')
                        ->will($this->returnValue($request));
        
        $block = $this->initBlock();
        $params = array('AddFile' => "/new/path/to/image");
        $this->blockManager = new AlBlockManagerImagesBlockTester($this->container, $this->validator);
        $this->doSave($block, $params);
    }

    public function testAnImageHasBeenRemovedFromImagesBlock()
    {
        $this->initContainer();

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

        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->once())
                ->method('getId')
                ->will($this->returnValue($id));

        $block->expects($this->any())
                ->method('getContent')
                ->will($this->returnValue($htmlContent));

        return $block;
    }
/*
    private function doSave($block, array $params)
    {
        $event = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockDeletingEvent');
        $this->setUpEventsHandler($event, 2);

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
    }*/
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

        return array("Content" => $defaultContent);
    }
}
