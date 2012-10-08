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

namespace AlphaLemon\ThemeEngineBundle\Tests\Unit\Core\Generator;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\Generator\AlAppThemeGenerator;
use org\bovigo\vfs\vfsStream;

/**
 * AlAppThemeGeneratorTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlAppThemeGeneratorTest extends Base\AlAppGeneratorBase
{
    private $themeGenerator;

    protected function setUp()
    {
        parent::setUp();

        $this->themeGenerator = new AlAppThemeGenerator($this->fileSystem, vfsStream::url('root'));
    }

    public function testThemeIsGenerated()
    {
        $this->themeGenerator->generateExt('AlphaLemon\\Theme\\FakeThemeBundle', 'FakeThemeBundle', vfsStream::url('root/src'), 'xml', '', array('strict' => false));

        $expected = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
        $expected .= '<container xmlns="http://symfony.com/schema/dic/services"' . PHP_EOL;
        $expected .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $expected .= '        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= '    <services>' . PHP_EOL;
        $expected .= '        <service id="fake_theme.theme" class="%alpha_lemon_theme_engine.theme.class%">' . PHP_EOL;
        $expected .= '            <argument type="string">FakeTheme</argument>' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .= '    </services>' . PHP_EOL;
        $expected .= '</container>';

        $this->assertFileExists( vfsStream::url('root/src/AlphaLemon/Theme/FakeThemeBundle/Resources/views/Theme'));
        $themeFile = vfsStream::url('root/src/AlphaLemon/Theme/FakeThemeBundle/Resources/config/fake_theme.xml');
        $this->assertFileExists($themeFile);
        $this->assertEquals($expected, file_get_contents($themeFile));
    }

    public function testThemeIsGeneratedUsingStrictMode()
    {
        $this->themeGenerator->generateExt('AlphaLemon\\Theme\\FakeThemeBundle', 'FakeThemeBundle', vfsStream::url('root/src'), 'xml', '', array('strict' => true));

        $expected = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
        $expected .= '<container xmlns="http://symfony.com/schema/dic/services"' . PHP_EOL;
        $expected .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $expected .= '        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= '    <services>' . PHP_EOL;
        $expected .= '        <service id="fake.theme" class="%alpha_lemon_theme_engine.theme.class%">' . PHP_EOL;
        $expected .= '            <argument type="string">Fake</argument>' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .= '    </services>' . PHP_EOL;
        $expected .= '</container>';

        $themeFile = vfsStream::url('root/src/AlphaLemon/Theme/FakeThemeBundle/Resources/config/fake.xml');
        $this->assertFileExists($themeFile);
        $this->assertEquals($expected, file_get_contents($themeFile));
    }
}