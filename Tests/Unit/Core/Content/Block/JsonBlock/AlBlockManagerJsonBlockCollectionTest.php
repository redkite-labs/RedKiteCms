<?php
/*
 * This file is part of the BootstrapThumbnailBlockBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave intact this copyright 
 * notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 * 
 * @license    MIT LICENSE
 * 
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\JsonBlock;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\Base\AlBlockManagerContainerBase;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockCollection;

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
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlBlockManagerJsonBlockCollectionTest extends AlBlockManagerContainerBase
{  
    public function testManageJsonCollection()
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
        
        $values = array(
            array(
                'ToDelete' => '0',
            ),
            array(
                'ToDelete' => '0',
            ),
        );
        
        $repository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->container->expects($this->at(1))
                      ->method('get')
                      ->will($this->returnValue($repository));
                
        $blockManager = new AlBlockManagerJsonBlockCollectionTester($this->container, $this->validator);
        if (array_key_exists("Content", $values)) {
            $block = $this->initBlock($value);        
            $blockManager->set($block);            
        }
        $result = $blockManager->manageCollectionTester($values);
        
        $this->assertEquals($values, $result);
    }
    
    /**
     * @dataProvider addItemProvider
     */
    public function testAddItem($values, $blocks, $expectedResult)
    {
        $value = '
        {
            "0" : {
                "type": "LinkBlock"
            },
            "1" : {
                "type": "BootstrapNavbarBlock"
            },
            "2" : {
                "type": "LinkBlock"
            }
        }';
        
        $repository = $this->setUpRepository($blocks, $expectedResult);        
        $this->container->expects($this->at(1))
                      ->method('get')
                      ->will($this->returnValue($repository));
        
        $block = $this->setUpBaseBlock($value); 
        $blockManager = new AlBlockManagerJsonBlockCollectionTester($this->container, $this->validator);               
        $blockManager->set($block);
        $result = $blockManager->manageCollectionTester($values);
        
        $this->assertEquals($expectedResult, $result);
    }
    
    /**
     * @dataProvider deleteItemProvider
     */
    public function testDeleteItem($values, $blocks, $expectedResult)
    {
        $value = '
        {
            "0" : {
                "type": "LinkBlock"
            },
            "1" : {
                "type": "BootstrapNavbarBlock"
            },
            "2" : {
                "type": "LinkBlock"
            }
        }';
        
        $repository = $this->setUpRepository($blocks, $expectedResult); 
        $this->container->expects($this->at(1))
                      ->method('get')
                      ->will($this->returnValue($repository));
        
        $block = $this->setUpBaseBlock($value); 
        $blockManager = new AlBlockManagerJsonBlockCollectionTester($this->container, $this->validator);
        $blockManager->set($block);
        $result = $blockManager->manageCollectionTester($values);
        
        $this->assertEquals($expectedResult, $result);
    }
    
    public function addItemProvider()
    {
        return array(
            array(
                array(
                    'Content' => '{"operation": "add", "item": "-1", "value": { "type": "TestBlock" }}',
                ),
                array(
                    $this->initBlock('2-0', '2-1', null, true),            
                    $this->initBlock('2-1', '2-2', null, true),            
                    $this->initBlock('2-2', '2-3', null, true),
                ),
                array(
                    'Content' => '[{"type":"TestBlock"},{"type":"LinkBlock"},{"type":"BootstrapNavbarBlock"},{"type":"LinkBlock"}]',
                ),
            ),
            array(
                array(
                    'Content' => '{"operation": "add", "item": "0", "value": { "type": "TestBlock" }}',
                ),
                array(
                    $this->initBlock('2-0', null, null, true),            
                    $this->initBlock('2-1', '2-2', null, true),            
                    $this->initBlock('2-2', '2-3', null, true),
                ),
                array(
                    'Content' => '[{"type":"LinkBlock"},{"type":"TestBlock"},{"type":"BootstrapNavbarBlock"},{"type":"LinkBlock"}]',
                ),
            ),
            array(
                array(
                    'Content' => '{"operation": "add", "item": "1", "value": { "type": "TestBlock" }}',
                ),
                array(
                    $this->initBlock('2-0', null, null, true),            
                    $this->initBlock('2-1', null, null, true),           
                    $this->initBlock('2-2', '2-3', null, true),
                ),
                array(
                    'Content' => '[{"type":"LinkBlock"},{"type":"BootstrapNavbarBlock"},{"type":"TestBlock"},{"type":"LinkBlock"}]',
                ),
            ),
            array(
                array(
                    'Content' => '{"operation": "add", "item": "2", "value": { "type": "TestBlock" }}',
                ),
                array(
                    $this->initBlock('2-0', null, null, true),            
                    $this->initBlock('2-1', null, null, true),           
                    $this->initBlock('2-2', null, null, true),
                ),
                array(
                    'Content' => '[{"type":"LinkBlock"},{"type":"BootstrapNavbarBlock"},{"type":"LinkBlock"},{"type":"TestBlock"}]',
                ),
            ),
            array(
                array(
                    'Content' => '{"operation": "add", "item": "0", "value": { "type": "TestBlock" }}',
                ),
                array(
                    $this->initBlock('2-0', null, null, true),            
                    $this->initBlock('2-1', '2-2', null, true),            
                    $this->initBlock('2-2', '2-3', null, false),
                ),
                false,
            ),
        );
    }
    
    public function deleteItemProvider()
    {
        return array(            
            array(
                array(
                    'Content' => '{"operation": "remove", "item": "0"}',
                ),
                array(        
                    $this->initBlock('2-0', null, true, true),  
                    $this->initBlock('2-1', '2-0', null, true),            
                    $this->initBlock('2-2', '2-1', null, true),
                ),
                array(
                    'Content' => '[{"type":"BootstrapNavbarBlock"},{"type":"LinkBlock"}]',
                ),
            ),            
            array(
                array(
                    'Content' => '{"operation": "remove", "item": "1"}',
                ),
                array(        
                    $this->initBlock('2-0', null, null, true),  
                    $this->initBlock('2-1', null, true, true),            
                    $this->initBlock('2-2', '2-1', null, true),
                ),
                array(
                    'Content' => '[{"type":"LinkBlock"},{"type":"LinkBlock"}]',
                ),
            ),            
            array(
                array(
                    'Content' => '{"operation": "remove", "item": "2"}',
                ),
                array(        
                    $this->initBlock('2-0', null, null, true),  
                    $this->initBlock('2-1', null, null, true),            
                    $this->initBlock('2-2', null, true, true),
                ),
                array(
                    'Content' => '[{"type":"LinkBlock"},{"type":"BootstrapNavbarBlock"}]',
                ),
            ),
            array(
                array(
                    'Content' => '{"operation": "remove", "item": "0"}',
                ),
                array(        
                    $this->initBlock('2-0', null, true, false),  
                    $this->initBlock('2-1', '2-0', null, true),            
                    $this->initBlock('2-2', '2-1', null, true),
                ),
                false,
            ),  
            array(
                array(
                    'Content' => '{"operation": "remove", "item": "0"}',
                ),
                array(        
                    $this->initBlock('2-0', null, true, true),  
                    $this->initBlock('2-1', '2-0', null, false),            
                    $this->initBlock('2-2', '2-1', null, true),
                ),
                false,
            ), 
        );
    }
    
    protected function initBlock($slotName, $newSlotName = null, $toDetete = null, $result = null)
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
        
        $block->expects($this->any())
              ->method('getSlotName')
              ->will($this->returnValue($slotName))
        ;
        
        if (null !== $newSlotName) {
            $block->expects($this->once())
                  ->method('setSlotName')
                  ->with($newSlotName)
            ;
        }
        
        if (null !== $toDetete) {
            $block->expects($this->once())
                  ->method('setToDelete')
            ;
        }
        
        if (null !== $result) {
            $block->expects($this->once())
                  ->method('save')
                  ->will($this->returnValue($result))
            ;
        }

        return $block;
    }
    
    
    private function setUpBaseBlock($value)
    {
        $block = $this->initBlock('nav-menu');
        $block->expects($this->once())
                  ->method('getId')
                  ->will($this->returnValue(2));
        
        $block->expects($this->once())
                  ->method('getContent')
                  ->will($this->returnValue($value));
                  
        return $block;
    }
    
    private function setUpRepository($blocks, $expectedResult)
    {
        $blocksRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel');
        $blocksRepository->expects($this->once())
              ->method('retrieveContentsBySlotName')
              ->will($this->returnValue($blocks))
        ;
        
        $blocksRepository->expects($this->once())
              ->method('startTransaction')
        ;
        
        if (is_array($expectedResult)) {
            $blocksRepository->expects($this->once())
                  ->method('commit')
            ;
        }
        
        if (is_bool($expectedResult)) {
            $blocksRepository->expects($this->once())
                  ->method('rollback')
            ;
        }

        $repository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $repository->expects($this->any())
              ->method('createRepository')
              ->with('Block')
              ->will($this->returnValue($blocksRepository))
        ;
        
        return $repository;
    }
}
