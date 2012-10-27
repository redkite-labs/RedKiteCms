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
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Base\BaseActionEvent;

class BaseActionEventTester extends BaseActionEvent
{
}

/**
 * BaseActionEventTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class BaseActionEventTest extends TestCase
{
    private $blockManager;

    protected function setUp()
    {
        parent::setUp();

        $this->blockManager = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\AlContentManagerInterface');

        $this->event = new BaseActionEventTester($this->blockManager);
    }

    public function testContentManager()
    {
        $this->assertSame($this->blockManager, $this->event->getContentManager());
        $blockManager = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\AlContentManagerInterface');
        $this->event->setContentManager($blockManager);
        $this->assertSame($blockManager, $this->event->getContentManager());
    }
}

