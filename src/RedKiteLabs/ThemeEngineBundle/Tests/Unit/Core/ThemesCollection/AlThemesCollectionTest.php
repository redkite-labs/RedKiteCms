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

namespace AlphaLemon\ThemeEngineBundle\Tests\Unit\Core\Asset;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection;

/**
 * AlThemesCollectionTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlThemesCollectionTest extends TestCase
{
    private $themesCollection;

    protected function setUp()
    {
        $this->themesCollection = new AlThemesCollection();
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
        $theme = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Theme\AlTheme')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $theme->expects($this->once())
            ->method('getThemeName')
            ->will($this->returnValue('FakeBundle'));

        return $theme;
    }
}