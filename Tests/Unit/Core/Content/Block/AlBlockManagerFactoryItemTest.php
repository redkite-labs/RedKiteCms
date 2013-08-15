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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Block;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManager;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryItem;

/**
 * AlBlockManagerFactoryItemTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlBlockManagerFactoryItemTest extends TestCase
{
    private $blockManager;

    protected function setUp()
    {
        $this->blockManager = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     */
    public function testFactoryItemObjectThrowsAnExceptionWhenAnyOfTheExpectedAttributesAreNotGiven()
    {
        $factoryItem = new AlBlockManagerFactoryItem($this->blockManager, array('fake' => 'value'));
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     */
    public function testFactoryItemObjectThrowsAnExceptionWhenAtLeastOneOfTheExpectedAttributesAreNotGiven()
    {
        $factoryItem = new AlBlockManagerFactoryItem($this->blockManager, array('id' => 'app_fake.block'));
    }

    public function testFactoryItemObjectHasBeenSet()
    {
        $factoryItem = new AlBlockManagerFactoryItem($this->blockManager, array('id' => 'app_fake.block', 'description' => 'Fake block', 'type' => 'Text'));

        $this->assertEquals($this->blockManager, $factoryItem->getBlockManager());
        $this->assertEquals('app_fake.block', $factoryItem->getId());
        $this->assertEquals('Fake block', $factoryItem->getDescription());
        $this->assertEquals('Text', $factoryItem->getType());
        $this->assertEquals('none', $factoryItem->getGroup());
    }

    public function testFactoryItemObjectHasBeenSetWithGroupOption()
    {
        $factoryItem = new AlBlockManagerFactoryItem($this->blockManager, array('id' => 'app_fake.block', 'description' => 'Fake block', 'type' => 'Text', 'group' => 'My awesome group'));

        $this->assertEquals($this->blockManager, $factoryItem->getBlockManager());
        $this->assertEquals('app_fake.block', $factoryItem->getId());
        $this->assertEquals('Fake block', $factoryItem->getDescription());
        $this->assertEquals('Text', $factoryItem->getType());
        $this->assertEquals('My awesome group', $factoryItem->getGroup());
    }
}
