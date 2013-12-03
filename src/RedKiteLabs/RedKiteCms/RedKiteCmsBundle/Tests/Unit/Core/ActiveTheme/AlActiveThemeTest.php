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
    }

    public function testCurrentActiveThemeIsRetrieved()
    {
        file_put_contents($this->activeThemePath, 'BusinessWebsiteThemeBundle');
        $this->setActiveThemeFile();
        $activeTheme = new AlActiveTheme($this->container);
        $this->assertEquals('BusinessWebsiteThemeBundle', $activeTheme->getActiveTheme());
        
        // from class' cache
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

        $this->container->expects($this->at(1))
            ->method('get')
            ->will($this->returnValue($themes));
        
        $this->container->expects($this->at(2))
            ->method('getParameter')
            ->with('red_kite_cms.active_theme_file')
            ->will($this->returnValue($this->activeThemePath));

        $activeTheme = new AlActiveTheme($this->container);
        $this->assertEquals('BusinessWebsiteThemeBundle', $activeTheme->getActiveTheme());
    }

    public function testWriteActiveTheme()
    {
        $this->setActiveThemeFile();
        $activeTheme = new AlActiveTheme($this->container);
        $activeTheme->writeActiveTheme('FakeThemeBundle');
        $bundle = file_get_contents(vfsStream::url('root/.active_theme'));
        $this->assertEquals('FakeThemeBundle', $bundle);
    }
    
    /**
     * @dataProvider versionsProvider
     */
    public function testgetBootstrapVersion($themeDeclaresVersion, $themes, $expectedVersion)
    {
        file_put_contents($this->activeThemePath, 'BusinessWebsiteThemeBundle');
        
        $this->setActiveThemeFile();
        $this->container->expects($this->at(1))
            ->method('getParameter')
            ->with('red_kite_cms.bootstrap_version')
            ->will($this->returnValue('3.x'));
        
        $this->container->expects($this->at(2))
            ->method('hasParameter')
            ->with('red_kite_labs_theme_engine.bootstrap_themes')
            ->will($this->returnValue($themeDeclaresVersion));
        
        if (null !== $themes) {
            $this->container->expects($this->at(3))
            ->method('getParameter')
            ->with('red_kite_labs_theme_engine.bootstrap_themes')
            ->will($this->returnValue($themes));
        }
        
        $activeTheme = new AlActiveTheme($this->container);
        $this->assertEquals($expectedVersion, $activeTheme->getThemeBootstrapVersion());
        $this->assertEquals($expectedVersion, $activeTheme->getThemeBootstrapVersion());
    }
    
    public function versionsProvider()
    {
        return array(
            array(
                false,
                null,
                '3.x',
            ),
            array(
                true,
                array('FooBundle' => '2.x'),
                '3.x',
            ),
            array(
                true,
                array('BusinessWebsiteThemeBundle' => '2.x'),
                '2.x',
            ),
        );
    }
    
    private function setActiveThemeFile()
    {
        $this->container->expects($this->at(0))
            ->method('getParameter')
            ->with('red_kite_cms.active_theme_file')
            ->will($this->returnValue($this->activeThemePath));
    }
}