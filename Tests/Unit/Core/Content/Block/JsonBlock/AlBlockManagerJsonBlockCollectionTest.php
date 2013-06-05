<?php
/*
 * This file is part of the BootstrapThumbnailBlockBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave intact this copyright 
 * notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 * 
 * @license    MIT LICENSE
 * 
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block\JsonBlock;

use AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block\Base\AlBlockManagerContainerBase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockCollection;

class AlBlockManagerJsonBlockCollectionTester extends AlBlockManagerJsonBlockCollection
{
    public function getDefaultValue()
    {
        return array();
    }
    
    public function manageCollectionTester($values)
    {
        return $this->manageCollection($values);
    }
}

/**
 * AlBlockManagerJsonBlockCollectionTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerJsonBlockCollectionTest extends AlBlockManagerContainerBase
{  
    /**
     * @dataProvider jsonCollectionProvider
     */
    public function testManageJsonCollection($values, $expectedResult)
    {
        $value = '
        {
            "0" : {
                "type": "BootstrapThumbnailBlock"
            },
            "1" : {
                "type": "BootstrapThumbnailBlock"
            }
        }';
        
        if (array_key_exists("Content", $values)) {
            $valuesArray = json_decode($values["Content"], true);        
            if ($valuesArray['operation'] == 'remove') {
                $blocksRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\BlockRepositoryInterface');
                $blocksRepository->expects($this->once())
                      ->method('deleteIncludedBlocks');

                $repository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
                $repository->expects($this->once())
                      ->method('createRepository')
                      ->will($this->returnValue($blocksRepository));

                $this->container->expects($this->at(2))
                      ->method('get')
                      ->will($this->returnValue($repository));
            }
        }
        
        $blockManager = new AlBlockManagerJsonBlockCollectionTester($this->container, $this->validator);
        if (array_key_exists("Content", $values)) {
            $block = $this->initBlock($value);        
            $blockManager->set($block);            
        }
        $result = $blockManager->manageCollectionTester($values);
        
        $this->assertEquals($expectedResult, $result);
    }
    
    public function jsonCollectionProvider()
    {
        return array(
            array(
                array(
                    'ToDelete' => '0',
                ),
                array(
                    'ToDelete' => '0',
                ),
            ),
            array(
                array(
                    'Content' => '{"operation": "add", "value": { "type": "BootbusinessProductThumbnailBlock" }}',
                ),
                array(
                    'Content' => '[{"type":"BootstrapThumbnailBlock"},{"type":"BootstrapThumbnailBlock"},{"type":"BootbusinessProductThumbnailBlock"}]',
                ),
            ),
            array(
                array(
                    'Content' => '{"operation": "remove", "item": "1", "slotName": "12-1"}',
                ),
                array(
                    'Content' => '[{"type":"BootstrapThumbnailBlock"}]',
                ),
            ),
        );
    }
    
    protected function initBlock($value)
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
              ->method('getContent')
              ->will($this->returnValue($value));

        return $block;
    }
}
