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

namespace AlphaLemon\ThemeEngineBundle\Tests\Unit\Core\Rendering\Compiler\ThemeEngineListenersCollection;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\Rendering\Compiler\ThemeEngineListenersCollection\AlThemeEngineListenersCollection;

/**
 * AlThemeEngineListenersCollectionTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlThemeEngineListenersCollectionTest extends TestCase
{
    private $listenersCollection;

    protected function setUp()
    {
        $this->listenersCollection = new AlThemeEngineListenersCollection();
    }

    public function testAddAListener()
    {
        $this->listenersCollection->addListenerId('alpha_lemon_demo.demo_listener');

        $this->assertEquals(1, count($this->listenersCollection));
        $this->assertEquals('alpha_lemon_demo.demo_listener', $this->listenersCollection->current());
        $this->assertEquals('alpha_lemon_demo.demo_listener', $this->listenersCollection->key());
        $this->assertTrue($this->listenersCollection->valid());
    }

    public function testRetrivingATemplateFromAnInvalidKey()
    {
        $this->listenersCollection->addListenerId('alpha_lemon_demo.demo_listener');

        $this->assertNull($this->listenersCollection->getListenerId('fake'));
    }

    public function testRetrivingATemplateFromAValidKey()
    {
        $this->listenersCollection->addListenerId('alpha_lemon_demo.demo_listener');

        $this->assertNotNull($this->listenersCollection->getListenerId('alpha_lemon_demo.demo_listener'));
    }
}