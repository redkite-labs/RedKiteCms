<?php
/*
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

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\Theme\AlActiveTheme;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;

/**
 * AlBlockManagerFactoryItemTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlActiveThemeTest extends TestCase
{
    private $container;

    protected function setUp()
    {
        $this->root = vfsStream::setup('root');
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
    }

    public function testCurrentActiveThemeIsRetrieved()
    {
        $path = vfsStream::url('root/.active_theme');
        file_put_contents($path, 'BusinessWebsiteThemeBundle');
        $this->container->expects($this->any())
            ->method('getParameter')
            ->will($this->returnValue($path));
        $activeTheme = new AlActiveTheme($this->container);
        $this->assertEquals('BusinessWebsiteThemeBundle', $activeTheme->getActiveTheme());
    }

    public function testWhenActiveThemFileDoesNotExistTheFirstThemeIsChoosen()
    {
        $this->markTestSkipped(
            'Non works'
        );

        $path = vfsStream::url('root/.active_theme');

        $theme = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $theme->expects($this->once())
            ->method('getThemeName')
            ->will($this->returnValue($path, 'BusinessWebsiteThemeBundle'));

        $themes = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection');
        $themes->expects($this->once())
            ->method('getTheme')
            ->will($this->returnValue($theme));

        $this->container->expects($this->any())
            ->method('getParameter')
            ->will($this->onConsecutiveCalls($path, $themes));

        $activeTheme = new AlActiveTheme($this->container);
        $this->assertEquals('BusinessWebsiteThemeBundle', $activeTheme->getActiveTheme());
    }
}
