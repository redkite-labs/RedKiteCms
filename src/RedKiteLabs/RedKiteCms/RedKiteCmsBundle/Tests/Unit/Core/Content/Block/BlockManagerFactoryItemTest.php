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
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManager;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManagerFactoryItem;

/**
 * BlockManagerFactoryItemTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlockManagerFactoryItemTest extends TestCase
{
    private $blockManager;

    protected function setUp()
    {
        $this->blockManager = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     */
    public function testFactoryItemObjectThrowsAnExceptionWhenAnyOfTheExpectedAttributesAreNotGiven()
    {
        $factoryItem = new BlockManagerFactoryItem($this->blockManager, array('fake' => 'value'));
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     */
    public function testFactoryItemObjectThrowsAnExceptionWhenAtLeastOneOfTheExpectedAttributesAreNotGiven()
    {
        $factoryItem = new BlockManagerFactoryItem($this->blockManager, array('id' => 'app_fake.block'));
    }

    public function testFactoryItemObjectHasBeenSet()
    {
        $factoryItem = new BlockManagerFactoryItem($this->blockManager, array('id' => 'app_fake.block', 'description' => 'Fake block', 'type' => 'Text'));

        $this->assertEquals($this->blockManager, $factoryItem->getBlockManager());
        $this->assertEquals('app_fake.block', $factoryItem->getId());
        $this->assertEquals('Fake block', $factoryItem->getDescription());
        $this->assertEquals('Text', $factoryItem->getType());
        $this->assertEquals('none', $factoryItem->getGroup());
    }

    public function testFactoryItemObjectHasBeenSetWithGroupOption()
    {
        $factoryItem = new BlockManagerFactoryItem($this->blockManager, array('id' => 'app_fake.block', 'description' => 'Fake block', 'type' => 'Text', 'group' => 'My awesome group'));

        $this->assertEquals($this->blockManager, $factoryItem->getBlockManager());
        $this->assertEquals('app_fake.block', $factoryItem->getId());
        $this->assertEquals('Fake block', $factoryItem->getDescription());
        $this->assertEquals('Text', $factoryItem->getType());
        $this->assertEquals('My awesome group', $factoryItem->getGroup());
    }
}
