<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Block;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactory;

/**
 * AlBlockManagerFactoryTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlBlockManagerFactoryTest extends TestCase
{
    private $factoryRepository;
    private $translator;
    private $blockManager;
    private $blockManagerFactory;
    private $eventsHandler;

    protected function setUp()
    {
        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $this->eventsHandler = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface');

        $this->blockManagerFactory = new AlBlockManagerFactory($this->eventsHandler, $this->factoryRepository, $this->translator);
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
        $blocks = $this->blockManagerFactory->getBlocks();
        $this->assertCount(2, $blocks);
        $this->assertTrue(array_key_exists('Default', $blocks));        
        $this->assertTrue(array_key_exists('Text', $blocks["Ungrouped"]));
    }
    
    /**
     * @dataProvider blocksProvider
     */
    public function testGetBlocks($isInternal, $expectedBlocks, array $attributes = null)
    {
        $this->initBlockManager($attributes);
        
        $blockManager = $this->createBlockManager();
        $blockManager->expects($this->once())
                     ->method('getIsInternalBlock')
                     ->will($this->returnValue($isInternal));

        $attributes = array(
            'id' => 'app_fake.block', 
            'description' => 'Script block',  
            'type' => 'Script', 
            'group' => 'redkitecms_internals',
        );
        $this->blockManagerFactory->addBlockManager($blockManager, $attributes);
        
        $blocks = $this->blockManagerFactory->getBlocks();
        $this->assertEquals($expectedBlocks, $blocks);
    }
    
    /**
     * @dataProvider availableBlocksProvider
     */
    public function testAvailableBlocks($isInternal, $expectedBlocks)
    {
        $this->initBlockManager();
        
        $blockManager = $this->createBlockManager();
        $blockManager->expects($this->once())
                     ->method('getIsInternalBlock')
                     ->will($this->returnValue($isInternal));

        $attributes = array('id' => 'app_fake_1.block', 'description' => 'Script block',  'type' => 'Script', 'group' => 'group_1');
        $this->blockManagerFactory->addBlockManager($blockManager, $attributes);
        
        $blocks = $this->blockManagerFactory->getAvailableBlocks();
        $this->assertCount(count($expectedBlocks), $blocks);
        $this->assertEquals($expectedBlocks, $blocks);
    }

    public function testNothigIsCreatedWhenAnyBlockHasBeenAddedToFactory()
    {
        $this->assertNull($this->blockManagerFactory->createBlockManager('Text'));
    }

    public function testNullIsReturnedWhenTheRequiredBlockDoesNotExist()
    {
        $attributes = array('id' => 'app_not_grouped.block', 'description' => 'Script block',  'type' => 'Script', 'group' => '');
        $this->blockManagerFactory->addBlockManager($this->createBlockManager(), $attributes);
        
        $this->assertNull($this->blockManagerFactory->createBlockManager('Fake'));
    }

    public function testFactoryCreateANewBlockManagerFromBlockType()
    {
        $this->initBlockManager();
        $this->setEventsHandler();
        
        $blockManager = $this->blockManagerFactory->createBlockManager('Text');
        $this->assertInstanceOf('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\AlBlockManager', $blockManager);
        $this->assertNotSame($this->blockManager, $blockManager);
    }

    public function testFactoryCreateANewBlockManagerFromAnAlBlockObject()
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlBlock');
        $block->expects($this->once())
              ->method('getType')
              ->will($this->returnValue('Text'));
        $this->initBlockManager();
        $this->setEventsHandler();
        $blockManager = $this->blockManagerFactory->createBlockManager($block);
        $this->assertInstanceOf('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\AlBlockManager', $blockManager);
        $this->assertNotSame($this->blockManager, $blockManager);
    }
    
    public function blocksProvider()
    {
        return array(
            array(
                false,
                array
                (
                    "Default" => array(                        
                        "Script" => array(
                            "description" => "Script block",
                            "filter" => "none",
                        ),
                    ),
                    "Ungrouped" => array(
                        "Text" => array(
                            "description" => "Fake block",
                            "filter" => "none",
                        ),
                    ),
                ),
            ),
            array(
                false,
                array
                (
                    "Default" => array(                        
                        "Script" => array(
                            "description" => "Script block",
                            "filter" => "none",
                        ),
                    ),
                    "Twitter Bootstrap" => array(
                        "Text" => array(
                            "description" => "Fake block",
                            "filter" => "none",
                        ),
                    ),
                ),
                array(
                    'id' => 'app_fake.block', 
                    'description' => 'Fake block',  
                    'type' => 'Text',
                    'group' => 'bootstrap,Twitter Bootstrap'
                )
            ),
            array(
                true,
                array
                (
                    "Default" => array(),
                    "Ungrouped" => array( 
                        "Text" => array(
                            "description" => "Fake block",
                            "filter" => "none",
                        ),
                    ),
                ),
            ),
        );
    }
    
    public function availableBlocksProvider()
    {
        return array(
            array(
                false,
                array
                (
                    "Text",
                    "Script",
                ),
            ),array(
                true,
                array
                (
                    "Text",
                ),
            ),
        );
    }

    private function initBlockManager(array $attributes = null)
    {
        if (null === $attributes) {
            $attributes = array('id' => 'app_fake.block', 'description' => 'Fake block',  'type' => 'Text');
        }
        
        $this->blockManager = $this->createBlockManager();
        $this->blockManager->expects($this->once())
                           ->method('setFactoryRepository')
                           ->with($this->factoryRepository);

        $this->blockManagerFactory->addBlockManager($this->blockManager, $attributes);
    }
    
    private function setEventsHandler()
    {
        $this->blockManager->expects($this->once())
                           ->method('setEventsHandler')
                           ->with($this->eventsHandler);
    }

    private function createBlockManager()
    {
        $blockManager = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\AlBlockManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        return $blockManager;
    }
}
