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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\JsonBlock;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\JsonBlock\BlockManagerJsonBlockContainer;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Block\Base\BlockManagerContainerBase;

class BlockManagerJsonBlockContainerTester extends BlockManagerJsonBlockContainer
{
    public function getDefaultValue()
    {
        return "my value";
    }
}

/**
 * BlockManagerJsonBlockTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlockManagerJsonBlockContainerTest extends BlockManagerContainerBase
{
    public function testBlockManagerInitialization()
    {
        $this->initContainer();
        
        $blockManager = new BlockManagerJsonBlockContainerTester($this->container);
    }
}
