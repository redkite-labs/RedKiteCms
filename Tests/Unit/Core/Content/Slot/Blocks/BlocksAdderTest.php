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
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Blocks\BlocksAdder;
use RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\AlSlot;

/**
 * BlocksAdderTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlocksAdderTest extends AlContentManagerBase
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

        $this->blocksAdder = new BlocksAdder($this->blockRepository, $this->factory);
    }
    
    public function testSkipSlotsRepeatedAtSiteLevel()
    {
        $blocksManagerCollection = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Blocks\BlockManagersCollection')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $blocksManagerCollection->expects($this->never())
             ->method('getBlockManagerIndex')
        ;
        
        $blocksManagerCollection->expects($this->never())
             ->method('insertAt')
        ;
        
        $this->blockRepository->expects($this->once())
             ->method('retrieveContents')
             ->with(1, 1, 'foo')
             ->will($this->returnValue(array($this->createBlock())));
        ;
        
        $options = array(
            "idPage"                => 2,
            "idLanguage"            => 2,
            "type"                  => 'Text',
            "referenceBlockId"      => 2,
            "insertDirection"       => 'bottom',
            "skipSiteLevelBlocks"   => true,
            "forceSlotAttributes"   => false,
        );
        
        $slot = new AlSlot('foo', array("repeated" => 'site'));
        $this->blocksAdder->add($slot, $blocksManagerCollection, $options);        
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddFailsBecauseUnexpctedExceptionHadThrown()
    {
        $blocksManagerCollection = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Blocks\BlockManagersCollection')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $blocksManagerCollection->expects($this->once())
             ->method('getBlockManagerIndex')
             ->will($this->throwException(new \InvalidArgumentException))
        ;
        
        $this->blockRepository->expects($this->once())
             ->method('rollback')
        ;
        
        $options = array(
            "idPage"                => 2,
            "idLanguage"            => 2,
            "type"                  => 'Text',
            "referenceBlockId"      => 2,
            "insertDirection"       => 'bottom',
            "skipSiteLevelBlocks"   => false,
            "forceSlotAttributes"   => false,
        );
        
        $slot = new AlSlot('foo', array("repeated" => 'page'));
        $this->blocksAdder->add($slot, $blocksManagerCollection, $options);        
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddFailsWhenAdjustPositionThrowsAnException()
    {
        $slotParam = array(
            "slotName" => 'foo',
            "slotOptions" => array("repeated" => 'page'),
        );
        
        $options = array(
            "idPage"                => 2,
            "idLanguage"            => 2,
            "type"                  => 'Text',
            "referenceBlockId"      => 2,
            "insertDirection"       => 'bottom',
            "skipSiteLevelBlocks"   => false,
            "forceSlotAttributes"   => false,
        );
        
        $block = $this->createBlock();
        $block->expects($this->once())
               ->method('getContentPosition')
               ->will($this->throwException(new \InvalidArgumentException))
            ;
        
        $internalElements = array(
            "index" => 0,                    
            "insertAt" => 1,
            "blockManager" => $this->createBlockManager(),
            "parts" => array(
                "left" => array(
                ),
                "right" => array(
                    $this->createBlockManager($block),
                ),
            ),
        );
        
        $positions = array(
            array(
                'expectedPosition' => array(
                ),
                'skip' => true,
            ),
        );
        
        $blocksManagerCollection = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Blocks\BlockManagersCollection')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $blocksManagerCollection->expects($this->once())
             ->method('getBlockManagerIndex')
             ->with($options["referenceBlockId"])
             ->will($this->returnValue($internalElements["index"]))
        ;
        
        $blocksManagerCollection->expects($this->once())
             ->method('insertAt')
             ->with($internalElements["blockManager"], $internalElements["insertAt"])
             ->will($this->returnValue($internalElements["parts"]))
        ;
        
        $this->factory->expects($this->once())
             ->method('createBlockManager')
             ->with($options["type"])
             ->will($this->returnValue($internalElements["blockManager"]))
        ;
        
        $repositoryOptions = array(
            'expectedCommit' => 0,
            'expectedRollback' => 2,
        );
        
        $this->initRepository($positions, $repositoryOptions);
        
        $slot = new AlSlot($slotParam["slotName"], $slotParam["slotOptions"]);
        $this->blocksAdder->add($slot, $blocksManagerCollection, $options);
        
        $lastAdded = ($repositoryOptions["expectedRollback"] == 0) ? $internalElements["blockManager"] : null;
        $this->assertSame($lastAdded, $this->blocksAdder->lastAdded());
    }
    
    /**
     * @dataProvider addProvider
     */
    public function testAdd($slotParam, $options, $internalElements, $positions, $repositoryOptions = null)
    {
        $blocksManagerCollection = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Blocks\BlockManagersCollection')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $blocksManagerCollection->expects($this->once())
             ->method('getBlockManagerIndex')
             ->with($options["referenceBlockId"])
             ->will($this->returnValue($internalElements["index"]))
        ;
        
        $blocksManagerCollection->expects($this->once())
             ->method('insertAt')
             ->with($internalElements["blockManager"], $internalElements["insertAt"])
             ->will($this->returnValue($internalElements["parts"]))
        ;
        
        $this->factory->expects($this->once())
             ->method('createBlockManager')
             ->with($options["type"])
             ->will($this->returnValue($internalElements["blockManager"]))
        ;
        
        if (null === $repositoryOptions) {
            $repositoryOptions = array(
                'expectedCommit' => 2,
                'expectedRollback' => 0,
            );
        }
        
        $this->initRepository($positions, $repositoryOptions);
        
        $slot = new AlSlot($slotParam["slotName"], $slotParam["slotOptions"]);
        $this->blocksAdder->add($slot, $blocksManagerCollection, $options);
        
        $lastAdded = ($repositoryOptions["expectedRollback"] == 0) ? $internalElements["blockManager"] : null;
        $this->assertSame($lastAdded, $this->blocksAdder->lastAdded());
    }
    
    /**
     * @dataProvider addFromDefinitionProvider
     */
    public function testAddFromDefinition($slotParam, $options, $internalElements, $positions, $items = array(), $repositoryOptions = null)
    {
        $blocksManagerCollection = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Blocks\BlockManagersCollection')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $blocksManagerCollection->expects($this->once())
             ->method('getBlockManagerIndex')
             ->with($options["referenceBlockId"])
             ->will($this->returnValue($internalElements["index"]))
        ;
        
        $blocksManagerCollection->expects($this->once())
             ->method('insertAt')
             ->with($internalElements["blockManager"], $internalElements["insertAt"])
             ->will($this->returnValue($internalElements["parts"]))
        ;
        
        $this->factory->expects($this->at(0))
             ->method('createBlockManager')
             ->with($options["type"])
             ->will($this->returnValue($internalElements["blockManager"]))
        ;
        
        $c = 1;
        foreach ($items as $item) {
            $this->factory->expects($this->at($c))
                ->method('createBlockManager')
                ->with($item["type"])
                ->will($this->returnValue($item['blockManager']))
           ;
        }
        
        if (null === $repositoryOptions) {
            $repositoryOptions = array(
                'expectedCommit' => 2,
                'expectedRollback' => 0,
            );
        }
        
        $this->initRepository($positions, $repositoryOptions);
        
        $slot = new AlSlot($slotParam["slotName"], $slotParam["slotOptions"]);
        $this->blocksAdder->add($slot, $blocksManagerCollection, $options);
        
        $lastAdded = ($repositoryOptions["expectedRollback"] == 0) ? $internalElements["blockManager"] : null;
        $this->assertSame($lastAdded, $this->blocksAdder->lastAdded());
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testEditProviderFails()
    {
        $blockManager = $this->createBlockManager(null, array('Content' => 'foo'), true);
        $blockManager->expects($this->once())
             ->method('save')
             ->will($this->throwException(new \RuntimeException))
        ;
        
        $this->blockRepository->expects($this->never())
             ->method('commit')
        ;
        
        $this->blockRepository->expects($this->once())
             ->method('rollback')
        ;
        
        $this->blocksAdder->edit($blockManager, array('Content' => 'foo'));  
    }
    
    /**
     * @dataProvider editProvider
     */
    public function testEdit($blockManager, $repositoryOptions = null)
    {
        if (null === $repositoryOptions) {
            $repositoryOptions = array(
                'expectedCommit' => 1,
                'expectedRollback' => 0,
            );
        }
        
        $this->blockRepository->expects($this->exactly($repositoryOptions["expectedCommit"]))
             ->method('commit')
        ;
        
        $this->blockRepository->expects($this->exactly($repositoryOptions["expectedRollback"]))
             ->method('rollback')
        ;
        
        $this->blocksAdder->edit($blockManager, array('Content' => 'foo'));     
        
        $lastEdited = ($repositoryOptions["expectedRollback"] == 0) ? $blockManager : null;
        $this->assertSame($lastEdited, $this->blocksAdder->lastEdited());   
    }
    
    public function editProvider()
    {
        return array(
            array(
                $this->createBlockManager(null, array('Content' => 'foo'), true),
            ), 
            array(
                $this->createBlockManager(null, array('Content' => 'foo'), false),
                array(
                    'expectedCommit' => 0,
                    'expectedRollback' => 1,
                ),
            ), 
        );
    }
    
    public function addFromDefinitionProvider()
    {
        return array(
            
            array(
                array(
                    "slotName" => 'foo',
                    "slotOptions" => array(
                        "repeated" => 'page', 
                        'blockDefinition' => '{"data_src":"holder.js/700x250"}',
                    ),
                ),
                array(
                    "idPage"                => 2,
                    "idLanguage"            => 2,
                    "type"                  => 'Text',
                    "referenceBlockId"      => 2,
                    "insertDirection"       => 'bottom',
                    "skipSiteLevelBlocks"   => false,
                    "forceSlotAttributes"   => true,
                ),
                array(
                    "index" => 0,                    
                    "insertAt" => 1,
                    "blockManager" => $this->createBlockManager(null, array(
                                "PageId"                => 2,
                                "LanguageId"            => 2,
                                "SlotName"              => 'foo',
                                "Type"                  => 'Text',
                                "ContentPosition"       => 2,
                            ), true, array("Content" => "Default text",)),
                    "parts" => array(
                        "left" => array(
                        ),
                        "right" => array(
                            $this->createBlockManager($this->createBlock(1)),
                        ),
                    ),
                ),
                array(
                    array(
                        'expectedPosition' => array(
                            "ContentPosition"  => 2,
                        ),
                        'result' => true,
                    ),
                ),
            ),
            array(
                array(
                    "slotName" => 'foo',
                    "slotOptions" => array(
                        "repeated" => 'page', 
                        'blockDefinition' => '{"data_src":"holder.js/700x250"}',
                    ),
                ),
                array(
                    "idPage"                => 2,
                    "idLanguage"            => 2,
                    "type"                  => 'Text',
                    "referenceBlockId"      => 2,
                    "insertDirection"       => 'bottom',
                    "skipSiteLevelBlocks"   => false,
                    "forceSlotAttributes"   => true,
                ),
                array(
                    "index" => 0,                    
                    "insertAt" => 1,
                    "blockManager" => $this->createBlockManager(null, array(
                                "PageId"                => 2,
                                "LanguageId"            => 2,
                                "SlotName"              => 'foo',
                                "Type"                  => 'Text',
                                "ContentPosition"       => 2,
                                "Content"               => '{"data_src":"holder.js\/700x250"}',
                            ), true, array("Content" => '{"data_src": "holder.js/200x150"}',)),
                    "parts" => array(
                        "left" => array(
                        ),
                        "right" => array(
                            $this->createBlockManager($this->createBlock(1)),
                        ),
                    ),
                ),
                array(
                    array(
                        'expectedPosition' => array(
                            "ContentPosition"  => 2,
                        ),
                        'result' => true,
                    ),
                ),
            ),
            array(
                array(
                    "slotName" => 'foo',
                    "slotOptions" => array(
                        "repeated" => 'page', 
                        'blockDefinition' => '{"data_src":"holder.js/700x250", "items":{"0":{"blockType":"Link"}}}',
                    ),
                ),
                array(
                    "idPage"                => 2,
                    "idLanguage"            => 2,
                    "type"                  => 'Text',
                    "referenceBlockId"      => 2,
                    "insertDirection"       => 'bottom',
                    "skipSiteLevelBlocks"   => false,
                    "forceSlotAttributes"   => true,
                ),
                array(
                    "index" => 0,                    
                    "insertAt" => 1,
                    "blockManager" => $this->createBlockManager($this->createBlock(2), array(
                                "PageId"                => 2,
                                "LanguageId"            => 2,
                                "SlotName"              => 'foo',
                                "Type"                  => 'Text',
                                "ContentPosition"       => 2,
                                "Content"               => '{"data_src":"holder.js\/700x250","items":[{"blockType":"Link"}]}',
                            ), true, array("Content" => '{"data_src": "holder.js/200x150"}',)),
                    "parts" => array(
                        "left" => array(
                        ),
                        "right" => array(
                            $this->createBlockManager($this->createBlock(1)),
                        ),
                    ),
                ),
                array(
                    array(
                        'expectedPosition' => array(
                            "ContentPosition"  => 2,
                        ),
                        'result' => true,
                    ),
                ),
                array(
                    array(
                        "type" => "Link",
                        "blockManager" => $this->createBlockManager($this->createBlock(2), array(
                            "PageId"                => 2,
                            "LanguageId"            => 2,
                            "SlotName"              => 'foo',
                            "Type"                  => 'Link',
                            "ContentPosition"       => 2,
                        )),
                    ),    
                ),
            ),
        );
    }
    
    public function addProvider()
    {
        return array(
            array(
                array(
                    "slotName" => 'foo',
                    "slotOptions" => array("repeated" => 'page'),
                ),
                array(
                    "idPage"                => 2,
                    "idLanguage"            => 2,
                    "type"                  => 'Text',
                    "referenceBlockId"      => 2,
                    "insertDirection"       => 'bottom',
                    "skipSiteLevelBlocks"   => false,
                    "forceSlotAttributes"   => false,
                ),
                array(
                    "index" => 0,                    
                    "insertAt" => 1,
                    "blockManager" => $this->createBlockManager(null, array(
                                "PageId"                => 2,
                                "LanguageId"            => 2,
                                "SlotName"              => 'foo',
                                "Type"                  => 'Text',
                                "ContentPosition"       => 2,
                            ), true),
                    "parts" => array(
                        "left" => array(
                        ),
                        "right" => array(
                            $this->createBlockManager($this->createBlock(1)),
                        ),
                    ),
                ),
                array(
                    array(
                        'expectedPosition' => array(
                            "ContentPosition"  => 2,
                        ),
                        'result' => true,
                    ),
                ),
            ),
            array(
                array(
                    "slotName" => 'foo',
                    "slotOptions" => array("repeated" => 'page'),
                ),
                array(
                    "idPage"                => 2,
                    "idLanguage"            => 2,
                    "type"                  => 'Text',
                    "referenceBlockId"      => 2,
                    "insertDirection"       => 'bottom',
                    "skipSiteLevelBlocks"   => false,
                    "forceSlotAttributes"   => false,
                ),
                array(
                    "index" => 0,                    
                    "insertAt" => 1,
                    "blockManager" => $this->createBlockManager(null, array(
                                "PageId"                => 2,
                                "LanguageId"            => 2,
                                "SlotName"              => 'foo',
                                "Type"                  => 'Text',
                                "ContentPosition"       => 2,
                            ), true),
                    "parts" => array(
                        "left" => array(
                            $this->createBlockManager($this->createBlock(1)),
                        ),
                        "right" => array(
                            $this->createBlockManager($this->createBlock(2)),
                        ),
                    ),
                ),
                array(
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
                    "slotName" => 'foo',
                    "slotOptions" => array("repeated" => 'page'),
                ),
                array(
                    "idPage"                => 2,
                    "idLanguage"            => 2,
                    "type"                  => 'Text',
                    "referenceBlockId"      => 2,
                    "insertDirection"       => 'bottom',
                    "skipSiteLevelBlocks"   => false,
                    "forceSlotAttributes"   => false,
                ),
                array(
                    "index" => 1,                    
                    "insertAt" => 2,
                    "blockManager" => $this->createBlockManager(null, array(
                                "PageId"                => 2,
                                "LanguageId"            => 2,
                                "SlotName"              => 'foo',
                                "Type"                  => 'Text',
                                "ContentPosition"       => 3,
                            ), true),
                    "parts" => array(
                        "left" => array(
                            $this->createBlockManager($this->createBlock(1)),
                            $this->createBlockManager($this->createBlock(2)),
                        ),
                        "right" => array(
                        ),
                    ),
                ),
                array(
                ),
            ),    
            array(
                array(
                    "slotName" => 'foo',
                    "slotOptions" => array("repeated" => 'page'),
                ),
                array(
                    "idPage"                => 2,
                    "idLanguage"            => 2,
                    "type"                  => 'Text',
                    "referenceBlockId"      => 2,
                    "insertDirection"       => 'top',
                    "skipSiteLevelBlocks"   => false,
                    "forceSlotAttributes"   => false,
                ),
                array(
                    "index" => 0,                    
                    "insertAt" => 0,
                    "blockManager" => $this->createBlockManager(null, array(
                                "PageId"                => 2,
                                "LanguageId"            => 2,
                                "SlotName"              => 'foo',
                                "Type"                  => 'Text',
                                "ContentPosition"       => 1,
                            ), true),
                    "parts" => array(
                        "left" => array(
                        ),
                        "right" => array(
                            $this->createBlockManager($this->createBlock(1)),
                        ),
                    ),
                ),
                array(
                    array(
                        'expectedPosition' => array(
                            "ContentPosition"  => 2,
                        ),
                        'result' => true,
                    ),
                ),
            ),        
            array(
                array(
                    "slotName" => 'foo',
                    "slotOptions" => array("repeated" => 'page'),
                ),
                array(
                    "idPage"                => 2,
                    "idLanguage"            => 2,
                    "type"                  => 'Text',
                    "referenceBlockId"      => 2,
                    "insertDirection"       => 'top',
                    "skipSiteLevelBlocks"   => false,
                    "forceSlotAttributes"   => false,
                ),
                array(
                    "index" => 1,                    
                    "insertAt" => 1,
                    "blockManager" => $this->createBlockManager(null, array(
                                "PageId"                => 2,
                                "LanguageId"            => 2,
                                "SlotName"              => 'foo',
                                "Type"                  => 'Text',
                                "ContentPosition"       => 2,
                            ), true),
                    "parts" => array(
                        "left" => array(
                            $this->createBlockManager($this->createBlock(1)),
                        ),
                        "right" => array(
                            $this->createBlockManager($this->createBlock(2)),
                        ),
                    ),
                ),
                array(
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
                    "slotName" => 'foo',
                    "slotOptions" => array("repeated" => 'page'),
                ),
                array(
                    "idPage"                => 2,
                    "idLanguage"            => 2,
                    "type"                  => 'Text',
                    "referenceBlockId"      => 2,
                    "insertDirection"       => 'top',
                    "skipSiteLevelBlocks"   => false,
                    "forceSlotAttributes"   => false,
                ),
                array(
                    "index" => 0,                    
                    "insertAt" => 0,
                    "blockManager" => $this->createBlockManager(null, array(
                                "PageId"                => 2,
                                "LanguageId"            => 2,
                                "SlotName"              => 'foo',
                                "Type"                  => 'Text',
                                "ContentPosition"       => 1,
                            ), true),
                    "parts" => array(
                        "left" => array(
                        ),
                        "right" => array(
                            $this->createBlockManager($this->createBlock(1)),
                            $this->createBlockManager($this->createBlock(2)),
                        ),
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
                    "slotName" => 'foo',
                    "slotOptions" => array("repeated" => 'page', 'htmlContent' => 'Default value'),
                ),
                array(
                    "idPage"                => 2,
                    "idLanguage"            => 2,
                    "type"                  => 'Text',
                    "referenceBlockId"      => 2,
                    "insertDirection"       => 'bottom',
                    "skipSiteLevelBlocks"   => false,
                    "forceSlotAttributes"   => true,
                ),
                array(
                    "index" => 0,                    
                    "insertAt" => 1,
                    "blockManager" => $this->createBlockManager(null, array(
                                "PageId"                => 2,
                                "LanguageId"            => 2,
                                "SlotName"              => 'foo',
                                "Type"                  => 'Text',
                                "ContentPosition"       => 2,
                                "Content"               => 'Default value',
                            ), true),
                    "parts" => array(
                        "left" => array(
                            $this->createBlockManager($this->createBlock(1)),
                        ),
                        "right" => array(
                            $this->createBlockManager($this->createBlock(2)),
                        ),
                    ),
                ),
                array(
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
                    "slotName" => 'foo',
                    "slotOptions" => array("repeated" => 'language'),
                ),
                array(
                    "idPage"                => 1,
                    "idLanguage"            => 2,
                    "type"                  => 'Text',
                    "referenceBlockId"      => 2,
                    "insertDirection"       => 'bottom',
                    "skipSiteLevelBlocks"   => false,
                    "forceSlotAttributes"   => false,
                ),
                array(
                    "index" => 0,                    
                    "insertAt" => 1,
                    "blockManager" => $this->createBlockManager(null, array(
                                "PageId"                => 1,
                                "LanguageId"            => 2,
                                "SlotName"              => 'foo',
                                "Type"                  => 'Text',
                                "ContentPosition"       => 2,
                            ), true),
                    "parts" => array(
                        "left" => array(
                            $this->createBlockManager($this->createBlock(1)),
                        ),
                        "right" => array(
                            $this->createBlockManager($this->createBlock(2)),
                        ),
                    ),
                ),
                array(
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
                    "slotName" => 'foo',
                    "slotOptions" => array("repeated" => 'site'),
                ),
                array(
                    "idPage"                => 1,
                    "idLanguage"            => 1,
                    "type"                  => 'Text',
                    "referenceBlockId"      => 2,
                    "insertDirection"       => 'bottom',
                    "skipSiteLevelBlocks"   => false,
                    "forceSlotAttributes"   => false,
                ),
                array(
                    "index" => 0,                    
                    "insertAt" => 1,
                    "blockManager" => $this->createBlockManager(null, array(
                                "PageId"                => 1,
                                "LanguageId"            => 1,
                                "SlotName"              => 'foo',
                                "Type"                  => 'Text',
                                "ContentPosition"       => 2,
                            ), true),
                    "parts" => array(
                        "left" => array(
                            $this->createBlockManager($this->createBlock(1)),
                        ),
                        "right" => array(
                            $this->createBlockManager($this->createBlock(2)),
                        ),
                    ),
                ),
                array(
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
                    "slotName" => 'foo',
                    "slotOptions" => array("repeated" => 'page'),
                ),
                array(
                    "idPage"                => 2,
                    "idLanguage"            => 2,
                    "type"                  => 'Text',
                    "referenceBlockId"      => 2,
                    "insertDirection"       => 'bottom',
                    "skipSiteLevelBlocks"   => false,
                    "forceSlotAttributes"   => false,
                ),
                array(
                    "index" => 0,                    
                    "insertAt" => 1,
                    "blockManager" => $this->createBlockManager(null, array(
                                "PageId"                => 2,
                                "LanguageId"            => 2,
                                "SlotName"              => 'foo',
                                "Type"                  => 'Text',
                                "ContentPosition"       => 2,
                            ), false),
                    "parts" => array(
                        "left" => array(
                            $this->createBlockManager($this->createBlock(1)),
                        ),
                        "right" => array(
                            $this->createBlockManager($this->createBlock(2)),
                        ),
                    ),
                ),
                array(
                    array(
                        'expectedPosition' => array(
                            "ContentPosition"  => 3,
                        ),
                        'result' => true,
                    ),
                ),
                array(
                    "expectedCommit" => 1,
                    "expectedRollback" => 1,
                )
            ),
            array(
                array(
                    "slotName" => 'foo',
                    "slotOptions" => array("repeated" => 'page'),
                ),
                array(
                    "idPage"                => 2,
                    "idLanguage"            => 2,
                    "type"                  => 'Text',
                    "referenceBlockId"      => 2,
                    "insertDirection"       => 'bottom',
                    "skipSiteLevelBlocks"   => false,
                    "forceSlotAttributes"   => false,
                ),
                array(
                    "index" => 0,                    
                    "insertAt" => 1,
                    "blockManager" => $this->createBlockManager(null, array(
                                "PageId"                => 2,
                                "LanguageId"            => 2,
                                "SlotName"              => 'foo',
                                "Type"                  => 'Text',
                                "ContentPosition"       => 2,
                            ), true),
                    "parts" => array(
                        "left" => array(
                            $this->createBlockManager($this->createBlock(1)),
                        ),
                        "right" => array(
                            $this->createBlockManager($this->createBlock(2)),
                        ),
                    ),
                ),
                array(
                    array(
                        'expectedPosition' => array(
                            "ContentPosition"  => 3,
                        ),
                        'result' => false,
                    ),
                ),
                array(
                    "expectedCommit" => 0,
                    "expectedRollback" => 2,
                )
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
    
    private function createBlockManager($block = null, $values = null, $saveResult = true, $defaultValue = null)
    {
         $blockManager = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\ServiceBlock\AlBlockManagerService')
                 ->disableOriginalConstructor()
                 ->getMock()
         ;
         
         if (null !== $defaultValue) {
             $blockManager->expects($this->once())
                ->method('getDefaultValue')
                ->will($this->returnValue($defaultValue))
            ;
         }
         
         if (null !== $block) {
             $blockManager->expects($this->once())
                ->method('get')
                ->will($this->returnValue($block))
            ;
         }
         
         if (null !== $values) {
             $blockManager->expects($this->once())
                ->method('save')
                ->with($values)
                ->will($this->returnValue($saveResult))
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
            
            if (array_key_exists('skip', $position)) {
                continue;
            }
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
        /*
        $this->blockRepository->expects($this->exactly($repositoryOptions["expectedRollback"]))
             ->method('rollback')
        ;*/
    }
}