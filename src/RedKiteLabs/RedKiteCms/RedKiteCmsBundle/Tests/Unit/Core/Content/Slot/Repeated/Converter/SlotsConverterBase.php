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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Slot\Repeated\Converter;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;

/**
 * AlSlotsConverterToLanguageTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlSlotsConverterBase extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->pageContents = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocks')
                           ->disableOriginalConstructor()
                            ->getMock();

        $this->languageRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\AlLanguageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->pageRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\AlPageRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->blockRepository = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->factoryRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $this->factoryRepository->expects($this->any())
            ->method('createRepository')
            ->will($this->onConsecutiveCalls($this->languageRepository, $this->pageRepository, $this->blockRepository));

        $this->blockRepository->expects($this->any())
            ->method('getRepositoryObjectClassName')
            ->will($this->returnValue('\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlBlock'));

        $this->blockRepository->expects($this->any())
            ->method('setRepositoryObject')
            ->will($this->returnSelf());
    }
}
