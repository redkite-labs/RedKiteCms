<?php
/**
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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Event\Content\Base;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\PageTree\Base\BasePageTreeEvent;

class PageTreeEventTester extends BasePageTreeEvent
{
}

/**
 * PageTreeEventTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class PageTreeEventTest extends TestCase
{
    private $pageTree;

    public function testPageTreeProperty()
    {
        $this->pageTree = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->event = new PageTreeEventTester($this->pageTree);
        $pageTree = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->event->setPageTree($pageTree);
        $this->assertSame($pageTree, $this->event->getPageTree());        
        $this->assertNotSame($this->pageTree, $this->event->getPageTree());
    }
}

