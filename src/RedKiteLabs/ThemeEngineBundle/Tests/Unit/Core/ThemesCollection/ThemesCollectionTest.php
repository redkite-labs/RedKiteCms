<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\ThemeEngineBundle\Tests\Unit\Core\Asset;

use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;
use RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\ThemesCollection;

/**
 * ThemesCollectionTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ThemesCollectionTest extends TestCase
{
    private $themesCollection;

    protected function setUp()
    {
        $this->themesCollection = new ThemesCollection();
    }

    public function testAddATheme()
    {
        $theme = $this->setUpTheme();
        $this->themesCollection->addTheme($theme);

        $this->assertEquals(1, count($this->themesCollection));
        $this->assertEquals($theme, $this->themesCollection->current());
        $this->assertEquals('fakebundle', $this->themesCollection->key());
        $this->assertTrue($this->themesCollection->valid());
    }

    public function testRetrivingATemplateFromAnInvalidKey()
    {
        $theme = $this->setUpTheme();
        $this->themesCollection->addTheme($theme);

        $this->assertNull($this->themesCollection->getTheme('fake'));
    }

    public function testRetrivingATemplateFromAValidKey()
    {
        $theme = $this->setUpTheme();
        $this->themesCollection->addTheme($theme);

        $this->assertEquals($theme, $this->themesCollection->getTheme('fakebundle'));
    }

    public function testKeyIsNormalized()
    {
        $theme = $this->setUpTheme();
        $this->themesCollection->addTheme($theme);

        $this->assertEquals($theme, $this->themesCollection->getTheme('FakeBundle'));
    }

    private function setUpTheme()
    {
        $theme = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Theme\Theme')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $theme->expects($this->once())
            ->method('getThemeName')
            ->will($this->returnValue('FakeBundle'));

        return $theme;
    }
}