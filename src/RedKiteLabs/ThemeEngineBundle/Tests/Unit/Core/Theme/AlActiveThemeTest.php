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
use AlphaLemon\ThemeEngineBundle\Core\Theme\AlActiveTheme;
use org\bovigo\vfs\vfsStream;

/**
 * AlActiveThemeTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
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
        $theme = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\Theme\AlThemeInterface');
        $theme->expects($this->once())
            ->method('getThemeName')
            ->will($this->returnValue('BusinessWebsiteThemeBundle'));

        $themes = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection');
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
