<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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
        $params = array('AddFile' => "image.jpg");
        $this->blockManager = new AlBlockManagerImagesBlockTester($this->container, $this->validator);
        $this->blockManager->set($block)
                           ->save($params);
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\RuntimeException
     */
    public function testAnExceptionIsThrownWhenTheImageAlreadyExists()
    {
        $this->initContainer();
        $this->container->expects($this->once())
            ->method('getParameter')
            ->with('red_kite_cms.upload_assets_dir')
            ->will($this->returnValue('upload/folder'));

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->container->expects($this->at(3))
                        ->method('get')
                        ->with('request')
                        ->will($this->returnValue($request));
        
        $block = $this->initBlock();
        $params = array('AddFile' => "image.jpg");
        $this->blockManager = new AlBlockManagerImagesBlockTester($this->container, $this->validator);
        $this->blockManager
            ->set($block)
            ->save($params)
        ;
    }
    
    public function testNoImageToAddOrRemove()
    {
        $this->initContainer();
        
        $block = $this->initBlock();
        $params = array('Content' => 'foo', 'ToDelete' => '1');
        $this->blockManager = new AlBlockManagerImagesBlockTester($this->container, $this->validator);
        $this->doSave($block, $params);
        
        return $this->blockManager;
    }

    public function testANewImageHasBeenAddedToImagesBlock()
    {
        $this->initContainer();
        $this->container->expects($this->once())
            ->method('getParameter')
            ->with('red_kite_cms.upload_assets_dir')
            ->will($this->returnValue('upload/folder'));

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $this->container->expects($this->at(3))
                        ->method('get')
                        ->with('request')
                        ->will($this->returnValue($request));
        
        $block = $this->initBlock();
        $params = array('AddFile' => "flower.jpg");
        $this->blockManager = new AlBlockManagerImagesBlockTester($this->container, $this->validator);
        $this->doSave($block, $params);
        
        return $this->blockManager;
    }

    public function testAnImageHasBeenRemovedFromImagesBlock()
    {
        $this->initContainer();

        $block = $this->initBlock();
        $params = array('RemoveFile' => "/upload/folder/image.jpg");
        $this->blockManager = new AlBlockManagerImagesBlockTester($this->container, $this->validator);
        $this->doSave($block, $params);
    }

    private function initBlock($id = null, $htmlContent = null)
    {
        if (null === $id) $id = 2;
        if (null === $htmlContent) $htmlContent = '{
            "0" : {
                "src" : "/upload/folder/image.jpg"
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
}

class AlBlockManagerImagesBlockTester extends AlBlockManagerImages
{
    public function getDefaultValue()
    {
        $defaultContent =
        '{
            "0" : {
                "image" : "/upload/folder/image.jpg"
            }
        }';

        return array("Content" => $defaultContent);
    }
}
