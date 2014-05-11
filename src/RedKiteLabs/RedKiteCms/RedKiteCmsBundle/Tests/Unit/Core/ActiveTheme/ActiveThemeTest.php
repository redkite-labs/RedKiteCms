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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\ActiveTheme;

use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ActiveTheme\ActiveTheme;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * ActiveThemeTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ActiveThemeTest extends TestCase
{
    private $container;
    private $activeThemePath;

    protected function setUp()
    {
        $this->root = vfsStream::setup('root');
        $this->activeThemePath = vfsStream::url('root/.active_theme');

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->themes = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\ThemesCollection');
        $this->container->expects($this->at(0))
            ->method('get')
            ->with('red_kite_labs_theme_engine.themes')
            ->will($this->returnValue($this->themes));
    }

    public function testCurrentActiveThemeIsRetrieved()
    {
        $theme = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\Theme\ThemeInterface');
        $this->initThemesCollection($theme, 2);


        $kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $bundle = $this->getMock('Symfony\Component\HttpKernel\Bundle\BundleInterface');

        $this->container->expects($this->at(2))
            ->method('get')
            ->with('kernel')
            ->will($this->returnValue($kernel))
        ;

        $kernel->expects($this->exactly(2))
            ->method('getBundle')
            ->with('BusinessWebsiteThemeBundle')
            ->will($this->returnValue($bundle))
        ;

        file_put_contents($this->activeThemePath, $this->writeActiveThemeFile('BusinessWebsiteThemeBundle'));
        $this->setActiveThemeFile();
        $activeTheme = new ActiveTheme($this->container);
        $this->assertSame($theme, $activeTheme->getActiveThemeBackend());
        $this->assertSame($theme, $activeTheme->getActiveThemeFrontend());
        $this->assertSame($bundle, $activeTheme->getActiveThemeBackendBundle());
        $this->assertSame($bundle, $activeTheme->getActiveThemeFrontendBundle());
    }

    public function testWhenActiveThemeFileDoesNotExistTheFirstThemeIsChosen()
    {
        $theme = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\Theme\ThemeInterface');
        $theme->expects($this->once())
            ->method('getThemeName')
            ->will($this->returnValue('ModernBusinessThemeBundle'))
        ;
        $this->initThemesCollection($theme);
        
        $this->themes->expects($this->at(1))
             ->method('valid')
             ->will($this->returnValue(true));
        
        $this->themes->expects($this->at(2))
             ->method('current')
             ->will($this->returnValue($theme));
        
        $this->setActiveThemeFile(2);

        $activeTheme = new ActiveTheme($this->container);
        $this->assertSame($theme, $activeTheme->getActiveThemeBackend());
    }

    public function testWriteActiveTheme(/*$backendThemeName, $frontendThemeName*/)
    {
        $backendThemeName = "ModernBusinessThemeBundle";
        $frontendThemeName = "ModernBusinessThemeBundle";
        $this->setActiveThemeFile(0);
        $activeTheme = new ActiveTheme($this->container);
        $activeTheme->writeActiveTheme($backendThemeName, $frontendThemeName);
        $activeThemesFileContents = file_get_contents(vfsStream::url('root/.active_theme'));
        $this->assertEquals($this->writeActiveThemeFile($backendThemeName, $frontendThemeName), $activeThemesFileContents);

        $backendThemeName = "ModernBusinessThemeBundle";
        $frontendThemeName = "BootbusinessThemeBundle";
        $activeTheme->writeActiveTheme($backendThemeName, $frontendThemeName);
        $activeThemesFileContents = file_get_contents(vfsStream::url('root/.active_theme'));
        $this->assertEquals($this->writeActiveThemeFile($backendThemeName, $frontendThemeName), $activeThemesFileContents);

        $backendThemeName = "AwesomeThemeBundle";
        $activeTheme->writeActiveTheme($backendThemeName, $frontendThemeName);
        $activeThemesFileContents = file_get_contents(vfsStream::url('root/.active_theme'));
        $this->assertEquals($this->writeActiveThemeFile($backendThemeName, "BootbusinessThemeBundle"), $activeThemesFileContents);

        $frontendThemeName = "AwesomeThemeBundle";
        $activeTheme->writeActiveTheme($backendThemeName, $frontendThemeName);
        $activeThemesFileContents = file_get_contents(vfsStream::url('root/.active_theme'));
        $this->assertEquals($this->writeActiveThemeFile("AwesomeThemeBundle", $frontendThemeName), $activeThemesFileContents);
    }
    
    /**
     * @dataProvider versionsProvider
     */
    public function testGetBootstrapVersion($themeDeclaresVersion, $themes, $expectedVersion)
    {
        file_put_contents($this->activeThemePath, 'BusinessWebsiteThemeBundle');
        
        $theme = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\Theme\ThemeInterface');
        $this->initThemesCollection($theme);
        $this->setActiveThemeFile(1);
        
        $theme->expects($this->any())
            ->method('getThemeName')
            ->will($this->returnValue('BusinessWebsiteThemeBundle'))
        ;
        
        $this->container->expects($this->at(2))
            ->method('getParameter')
            ->with('red_kite_cms.bootstrap_version')
            ->will($this->returnValue('3.x'))
        ;
        
        $this->container->expects($this->at(3))
            ->method('hasParameter')
            ->with('red_kite_labs_theme_engine.bootstrap_themes')
            ->will($this->returnValue($themeDeclaresVersion))
        ;
        
        if (null !== $themes) {
            $this->container->expects($this->at(4))
                ->method('getParameter')
                ->with('red_kite_labs_theme_engine.bootstrap_themes')
                ->will($this->returnValue($themes))
            ;
        }
        
        $activeTheme = new ActiveTheme($this->container);
        $this->assertEquals($expectedVersion, $activeTheme->getThemeBootstrapVersion());
        $this->assertEquals($expectedVersion, $activeTheme->getThemeBootstrapVersion());
    }
/*
    public function testGetActiveThemeBackendBundle()
    {
        $theme = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\Theme\ThemeInterface');
        $this->initThemesCollection($theme, 2);

        $kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $bundle = $this->getMock('Symfony\Component\HttpKernel\Bundle\BundleInterface');

        $this->container->expects($this->at(2))
            ->method('get')
            ->with('kernel')
            ->will($this->returnValue($kernel))
        ;

        $kernel->expects($this->once())
            ->method('getBundle')
            ->with('BusinessWebsiteThemeBundle')
            ->will($this->returnValue($bundle))
        ;

        $activeTheme = new ActiveTheme($this->container);
        $this->assertSame($bundle, $activeTheme->getActiveThemeBackendBundle());
    }*/
    
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
        $this->container->expects($this->at(1))
            ->method('getParameter')
            ->with('red_kite_cms.active_theme_file')
            ->will($this->returnValue($this->activeThemePath));
    }
    
    private function initThemesCollection($theme, $getThemeCall = 1)
    {
        $this->themes->expects($this->exactly($getThemeCall))
             ->method('getTheme')
             ->will($this->returnValue($theme));
    }

    private function writeActiveThemeFile($backendTheme, $frontendTheme = null)
    {
        if (null === $frontendTheme) {
            $frontendTheme = $backendTheme;
        }
        $activeThemeFileContents = sprintf("backend: %s\n", $backendTheme);
        $activeThemeFileContents .= sprintf("frontend: %s\n", $frontendTheme);

        return $activeThemeFileContents;
    }
}