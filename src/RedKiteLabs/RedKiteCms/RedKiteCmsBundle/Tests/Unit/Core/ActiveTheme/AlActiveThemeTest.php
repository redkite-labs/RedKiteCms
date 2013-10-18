<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\ActiveTheme;

use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\ActiveTheme\AlActiveTheme;
use org\bovigo\vfs\vfsStream;

/**
 * AlActiveThemeTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlActiveThemeTest extends TestCase
{
    private $container;
    private $activeThemePath;

    protected function setUp()
    {
        $this->root = vfsStream::setup('root');
        $this->activeThemePath = vfsStream::url('root/.active_theme');

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->container->expects($this->any())
            ->method('getParameter')
            ->will($this->returnValue($this->activeThemePath));
    }

    public function testCurrentActiveThemeIsRetrieved()
    {
        file_put_contents($this->activeThemePath, 'BusinessWebsiteThemeBundle');
        $this->container->expects($this->any())
            ->method('getParameter')
            ->will($this->returnValue($this->activeThemePath));
        $activeTheme = new AlActiveTheme($this->container);
        $this->assertEquals('BusinessWebsiteThemeBundle', $activeTheme->getActiveTheme());
    }

    public function testWhenActiveThemFileDoesNotExistTheFirstThemeIsChoosen()
    {
        $theme = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface');
        $theme->expects($this->once())
            ->method('getThemeName')
            ->will($this->returnValue('BusinessWebsiteThemeBundle'));

        $themes = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection');
        $themes->expects($this->at(1))
             ->method('valid')
             ->will($this->returnValue(true));
        $themes->expects($this->at(2))
             ->method('current')
             ->will($this->returnValue($theme));

        $this->container->expects($this->any())
            ->method('get')
            ->will($this->returnValue($themes));

        $activeTheme = new AlActiveTheme($this->container);
        $this->assertEquals('BusinessWebsiteThemeBundle', $activeTheme->getActiveTheme());
    }

    public function testWriteActiveTheme()
    {
        $activeTheme = new AlActiveTheme($this->container);
        $activeTheme->writeActiveTheme('FakeThemeBundle');
        $bundle = file_get_contents(vfsStream::url('root/.active_theme'));
        $this->assertEquals('FakeThemeBundle', $bundle);
    }
}
