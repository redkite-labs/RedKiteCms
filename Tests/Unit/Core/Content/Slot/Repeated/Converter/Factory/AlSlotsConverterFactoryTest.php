<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Slot\Repeated\Converter\Factory;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\Factory\AlSlotsConverterFactory;

/**
 * AlSlotsConverterFactoryTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlSlotsConverterFactoryTest extends TestCase
{
    private $factoryRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->pageContents = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocks')
                           ->disableOriginalConstructor()
                            ->getMock();

        $this->blockRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ClassNotFoundException
     */
    public function testCreateConverterThrowsAnExceptionWhenTheConvertedClassCannotBeInstantiated()
    {
        $slot = new AlSlot('test', array('repeated' => 'page'));
        $slotsConverterFactory = new AlSlotsConverterFactory($this->pageContents, $this->factoryRepository);

        $slotsConverterFactory->createConverter($slot, 'fake');
    }

    public function testConverterHasBeenInstantiated()
    {
        $this->pageContents->expects($this->any())
            ->method('getSlotBlocks')
            ->will($this->returnValue(array()));

        $slot = new AlSlot('test', array('repeated' => 'page'));
        $slotsConverterFactory = new AlSlotsConverterFactory($this->pageContents, $this->factoryRepository);

        $slotsConverterFactory->createConverter($slot, 'site');
    }
}
