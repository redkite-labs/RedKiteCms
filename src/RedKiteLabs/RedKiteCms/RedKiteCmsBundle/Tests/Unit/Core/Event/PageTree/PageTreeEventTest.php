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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Event\Content\Base;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\PageTree\Base\BasePageTreeEvent;

class PageTreeEventTester extends BasePageTreeEvent
{
}

/**
 * PageTreeEventTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class PageTreeEventTest extends TestCase
{
    private $pageTree;

    public function testPageTreeProperty()
    {
        $this->pageTree = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\AlPageTree')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->event = new PageTreeEventTester($this->pageTree);
        $pageTree = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\AlPageTree')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->event->setPageTree($pageTree);
        $this->assertSame($pageTree, $this->event->getPageTree());        
        $this->assertNotSame($this->pageTree, $this->event->getPageTree());
    }
}

