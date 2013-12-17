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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Slot;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Base\AlContentManagerBase;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Blocks\BlocksRemover;

/**
 * AlSlotsConverterFactoryTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlocksRemoverTest extends AlContentManagerBase
{
    protected function setUp()
    {
        parent::setUp();

        $this->factory = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface');

        $this->blockRepository = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;

        $this->blocksRemover = new BlocksRemover($this->blockRepository, $this->factory);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRemoveFailsBecauseUnexpctedExceptionHadThrown()
    {
        $idBlock = 2 ;
        
        $blocksManagerCollection = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Blocks\BlockManagersCollection')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $blocksManagerCollection->expects($this->once())
             ->method('removeAt')
             ->will($this->throwException(new \InvalidArgumentException))
        ;
        
        $blockManager = $this->createBlockManager();
        $blocksManagerCollection->expects($this->once())
             ->method('getBlockManagerAndIndex')
             ->with($idBlock)
             ->will($this->returnValue(array("manager" => $blockManager)))
        ;
        
        $this->blockRepository->expects($this->once())
             ->method('rollback')
        ;
        
        $this->blocksRemover->remove($idBlock, $blocksManagerCollection);        
    }
    
    /**
     * @dataProvider removeProvider
     */
    public function testRemove($blockManagerInfo, $parts, $positions, $repositoryOptions = null)
    {
        $idBlock = 2 ;
        
        $blocksManagerCollection = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Blocks\BlockManagersCollection')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $blocksManagerCollection->expects($this->once())
             ->method('removeAt')
             ->with($blockManagerInfo["index"])
             ->will($this->returnValue($parts))
        ;
        
        $blocksManagerCollection->expects($this->once())
             ->method('getBlockManagerAndIndex')
             ->with($idBlock)
             ->will($this->returnValue($blockManagerInfo))
        ;
        
        if (null === $repositoryOptions) {
            $repositoryOptions = array(
                'expectedCommit' => 2,
                'expectedRollback' => 0,
            );
        }
        
        $this->initRepository($positions, $repositoryOptions);
        $this->blocksRemover->remove($idBlock, $blocksManagerCollection);
    }
    
    /**
     * @dataProvider clearProvider
     */
    public function testClear($blockManagers, $repositoryOptions = null)
    {
        $blocksManagerCollection = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Blocks\BlockManagersCollection')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $blocksManagerCollection->expects($this->once())
             ->method('getBlockManagers')
             ->will($this->returnValue($blockManagers))
        ;
        
        $blocksManagerCollection->expects($this->once())
             ->method('count')
             ->will($this->returnValue(count($blockManagers)))
        ;
        
        if (null === $repositoryOptions) {
            $repositoryOptions = array(
                'expectedCommit' => 1,
                'expectedRollback' => 0,
            );
        }
        
        $blocksManagerCollection->expects($this->exactly($repositoryOptions["expectedCommit"]))
             ->method('clear')
        ;
        
        $this->blockRepository->expects($this->exactly($repositoryOptions["expectedCommit"]))
             ->method('commit')
        ;
        
        $this->blockRepository->expects($this->exactly($repositoryOptions["expectedRollback"]))
             ->method('rollback')
        ;
        
        $this->blocksRemover->clear($blocksManagerCollection);
    }
    
    public function clearProvider()
    {
        return array(
            array(
                array(
                    $this->createBlockManager(null, null, true),
                    $this->createBlockManager(null, null, true),
                ),
            ),
            array(
                array(
                    $this->createBlockManager(null, null, true),
                    $this->createBlockManager(null, null, false),
                ),
                array(
                    'expectedCommit' => 0,
                    'expectedRollback' => 1,
                ),
            ),
        );
    }
    
    public function removeProvider()
    {
        return array(
            array(
                array(  
                    "index" => 0, 
                    "manager" => $this->createBlockManager(null, null, true),
                ),
                array(
                    "left" => array(
                    ),
                    "right" => array(
                    ),
                ),
                array(),
            ),
            array(
                array(  
                    "index" => 0, 
                    "manager" => $this->createBlockManager(null, null, true),
                ),
                array(
                    "left" => array(
                    ),
                    "right" => array(
                        $this->createBlockManager($this->createBlock(2), true),
                    ),
                ),
                array(
                    array(
                        'expectedPosition' => array(
                            "ContentPosition"  => 1,
                        ),
                        'result' => true,
                    ),
                ),
            ),            
            array(
                array(  
                    "index" => 1, 
                    "manager" => $this->createBlockManager(null, null, true),
                ),
                array(
                    "left" => array(
                        $this->createBlockManager($this->createBlock(1), true),
                    ),
                    "right" => array(
                        $this->createBlockManager($this->createBlock(3), true),
                        $this->createBlockManager($this->createBlock(4), true),
                    ),
                ),
                array(
                    array(
                        'expectedPosition' => array(
                            "ContentPosition"  => 2,
                        ),
                        'result' => true,
                    ),
                    array(
                        'expectedPosition' => array(
                            "ContentPosition"  => 3,
                        ),
                        'result' => true,
                    ),
                ),
            ),
            array(
                array(  
                    "index" => 0, 
                    "manager" => $this->createBlockManager(null, null, false),
                ),
                array(
                    "left" => array(
                    ),
                    "right" => array(
                        $this->createBlockManager($this->createBlock(2), true),
                    ),
                ),
                array(
                    array(
                        'expectedPosition' => array(
                            "ContentPosition"  => 1,
                        ),
                        'result' => true,
                    ),
                ),
                array(
                    'expectedCommit' => 1,
                    'expectedRollback' => 1,
                ),
            ), 
            array(
                array(  
                    "index" => 0, 
                    "manager" => $this->createBlockManager(null, null, true),
                ),
                array(
                    "left" => array(
                    ),
                    "right" => array(
                        $this->createBlockManager($this->createBlock(2), true),
                    ),
                ),
                array(
                    array(
                        'expectedPosition' => array(
                            "ContentPosition"  => 1,
                        ),
                        'result' => false,
                    ),
                ),
                array(
                    'expectedCommit' => 0,
                    'expectedRollback' => 2,
                ),
            ), 
        );
    }
    
    private function createBlock($position = null)
    {
         $block = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Model\AlBlock');
         
         if (null !== $position) {
            $block->expects($this->once())
               ->method('getContentPosition')
               ->will($this->returnValue($position))
            ;
         }
         
         return $block;
    }
    
    private function createBlockManager($block = null, $saveResult = null, $deleteResult = null)
    {
         $blockManager = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\ServiceBlock\AlBlockManagerService')
                 ->disableOriginalConstructor()
                 ->getMock()
         ;
         
         if (null !== $block) {
             $blockManager->expects($this->once())
                ->method('get')
                ->will($this->returnValue($block))
            ;
         }
         
         if (null !== $saveResult) { 
             $blockManager->expects($this->once())
                ->method('save')
                ->will($this->returnValue($saveResult))
            ;
         }
         
         if (null !== $deleteResult) { 
             $blockManager->expects($this->once())
                ->method('delete')
                ->will($this->returnValue($deleteResult))
            ;
         }
         
         return $blockManager;
    }
    
    private function initRepository($positions, $repositoryOptions)
    {
        if (empty($positions)) {
            return;
        }
        
        $at = 2;
        foreach($positions as $position) {
            $this->blockRepository->expects($this->at($at))
                ->method('setRepositoryObject')
                ->will($this->returnSelf())
            ;
            $at++;
            
            $this->blockRepository->expects($this->at($at))
                 ->method('save')
                 ->with($position["expectedPosition"])
                 ->will($this->returnValue($position["result"]))
            ;
            
            $at++;
        }
        
        $this->blockRepository->expects($this->exactly($repositoryOptions["expectedCommit"]))
             ->method('commit')
        ;
        
        $this->blockRepository->expects($this->exactly($repositoryOptions["expectedRollback"]))
             ->method('rollback')
        ;
    }
}