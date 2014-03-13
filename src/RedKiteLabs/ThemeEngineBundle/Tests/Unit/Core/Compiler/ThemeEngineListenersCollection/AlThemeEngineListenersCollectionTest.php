<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
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

namespace RedKiteLabs\ThemeEngineBundle\Tests\Unit\Core\Rendering\Compiler\ThemeEngineListenersCollection;

use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;
use RedKiteLabs\ThemeEngineBundle\Core\Rendering\Compiler\ThemeEngineListenersCollection\AlThemeEngineListenersCollection;

/**
 * AlThemeEngineListenersCollectionTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
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