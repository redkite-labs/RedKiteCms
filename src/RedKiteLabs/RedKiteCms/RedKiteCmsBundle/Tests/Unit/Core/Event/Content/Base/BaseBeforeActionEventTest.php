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
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Base\BaseBeforeActionEvent;

class BaseBeforeActionEventTester extends BaseBeforeActionEvent
{
}

/**
 * BaseBeforeActionEventTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class BaseBeforeActionEventTest extends TestCase
{
    private $blockManager;
    private $values = array('foo' => 'bar');

    protected function setUp()
    {
        parent::setUp();

        $this->blockManager = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Content\AlContentManagerInterface');

        $this->event = new BaseBeforeActionEventTester($this->blockManager, $this->values);
    }

    public function testValues()
    {
        $values = array('some' => 'value');
        $this->assertEquals($this->values, $this->event->getValues());
        $this->event->setValues($values);
        $this->assertEquals($values, $this->event->getValues());
    }

    public function testAbort()
    {
        $this->assertFalse($this->event->isAborted());
        $this->event->abort();
        $this->assertTrue($this->event->isAborted());
    }
}

