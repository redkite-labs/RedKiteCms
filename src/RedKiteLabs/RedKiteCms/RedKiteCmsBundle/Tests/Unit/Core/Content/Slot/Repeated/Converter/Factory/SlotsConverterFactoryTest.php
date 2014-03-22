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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Slot\Repeated\Converter\Factory;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\Slot;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\Factory\SlotsConverterFactory;

/**
 * SlotsConverterFactoryTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class SlotsConverterFactoryTest extends TestCase
{
    private $factoryRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->pageContents = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\PageBlocks\PageBlocks')
                           ->disableOriginalConstructor()
                            ->getMock();

        $this->blockRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\BlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\ClassNotFoundException
     */
    public function testCreateConverterThrowsAnExceptionWhenTheConvertedClassCannotBeInstantiated()
    {
        $slot = new Slot('test', array('repeated' => 'page'));
        $slotsConverterFactory = new SlotsConverterFactory($this->pageContents, $this->factoryRepository);

        $slotsConverterFactory->createConverter($slot, 'fake');
    }

    public function testConverterHasBeenInstantiated()
    {
        $this->pageContents->expects($this->any())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array()));

        $slot = new Slot('test', array('repeated' => 'page'));
        $slotsConverterFactory = new SlotsConverterFactory($this->pageContents, $this->factoryRepository);

        $slotsConverterFactory->createConverter($slot, 'site');
    }
}
