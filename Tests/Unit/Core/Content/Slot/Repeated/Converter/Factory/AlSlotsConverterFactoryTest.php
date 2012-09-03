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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Slot\Repeated\Converter\Factory;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\Factory\AlSlotsConverterFactory;

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

        $this->pageContents = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks')
                           ->disableOriginalConstructor()
                            ->getMock();

        $this->blockRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ClassNotFoundException
     */
    public function testCreateConverterThrowsAnExceptionWhenTheConvertedClassCannotBeInstantiated()
    {
        $slot = new AlSlot('test', array('repeated' => 'page'));
        $slotsConverterFactory = new AlSlotsConverterFactory($this->pageContents, $this->factoryRepository);

        $slotsConverterFactory->createConverter($slot, 'fake');
    }

    public function testCreateConverterReturnsNullGivingTheSameRepeatedStatus()
    {
        $slot = new AlSlot('test', array('repeated' => 'page'));
        $slotsConverterFactory = new AlSlotsConverterFactory($this->pageContents, $this->factoryRepository);

        $this->assertNull($slotsConverterFactory->createConverter($slot, 'page'));
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