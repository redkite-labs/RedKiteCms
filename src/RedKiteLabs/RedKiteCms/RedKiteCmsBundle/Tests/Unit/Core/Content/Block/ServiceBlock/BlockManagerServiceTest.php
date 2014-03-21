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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\ServiceBlock;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\ServiceBlock\BlockManagerService;

/**
 * BlockManagerServiceBlockTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlockManagerServiceBlockTest extends TestCase
{
    public function testServiceBlockDefaultValueReturnsNull()
    {
        $eventsHandler = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\EventsHandler\EventsHandlerInterface');
        $factoryRepository = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface');

        $blockManager = new BlockManagerService($eventsHandler, $factoryRepository);
        $this->assertNull($blockManager->getDefaultValue());
    }
}
