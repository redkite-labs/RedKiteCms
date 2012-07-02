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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryItem;

/**
 * AlBlockManagerFactoryItemTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerFactoryItemTest extends TestCase
{
    private $blockManager;

    protected function setUp()
    {
        $this->blockManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterExpectedException
     */
    public function testFactoryItemObjectThrowsAnExceptionWhenAnyOfTheExpectedAttributesAreNotGiven()
    {
        $factoryItem = new AlBlockManagerFactoryItem($this->blockManager, array('fake' => 'value'));
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterExpectedException
     */
    public function testFactoryItemObjectThrowsAnExceptionWhenAtLeastOneOfTheExpectedAttributesAreNotGiven()
    {
        $factoryItem = new AlBlockManagerFactoryItem($this->blockManager, array('id' => 'app_fake.block'));
    }

    public function testFactoryItemObjectHasBeenSetted()
    {
        $factoryItem = new AlBlockManagerFactoryItem($this->blockManager, array('id' => 'app_fake.block', 'description' => 'Fake block'));

        $this->assertEquals($this->blockManager, $factoryItem->getBlockManager());
        $this->assertEquals('app_fake.block', $factoryItem->getId());
        $this->assertEquals('Fake block', $factoryItem->getDescription());
        $this->assertEquals('none', $factoryItem->getGroup());
    }


    public function testFactoryItemObjectHasBeenSettedWithGroupOption()
    {
        $factoryItem = new AlBlockManagerFactoryItem($this->blockManager, array('id' => 'app_fake.block', 'description' => 'Fake block', 'group' => 'My awesome group'));

        $this->assertEquals($this->blockManager, $factoryItem->getBlockManager());
        $this->assertEquals('app_fake.block', $factoryItem->getId());
        $this->assertEquals('Fake block', $factoryItem->getDescription());
        $this->assertEquals('My awesome group', $factoryItem->getGroup());
    }
}