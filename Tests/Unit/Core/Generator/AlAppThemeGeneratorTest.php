<?php
/**
 * This file is part of the RedKiteLabsRedKiteCmsBundle and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Generator;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\Generator\AlAppThemeGenerator;
use org\bovigo\vfs\vfsStream;

/**
 * AlAppThemeGeneratorTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlAppThemeGeneratorTest extends Base\AlAppGeneratorBase
{
    private $themeGenerator;

    protected function setUp()
    {
        parent::setUp();

        $this->themeGenerator = new AlAppThemeGenerator($this->fileSystem, vfsStream::url('root'), vfsStream::url('root'));
    }

    public function testThemeIsGenerated()
    {
        $this->themeGenerator->generateExt('AlphaLemon\\Theme\\FakeThemeBundle', 'FakeThemeBundle', vfsStream::url('root/src'), 'xml', '', array());

        $expected = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
        $expected .= '<container xmlns="http://symfony.com/schema/dic/services"' . PHP_EOL;
        $expected .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $expected .= '        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= '    <services>' . PHP_EOL;
        $expected .= '        <service id="fake_theme.theme" class="%red_kite_labs_theme_engine.theme.class%">' . PHP_EOL;
        $expected .= '            <argument type="string">FakeTheme</argument>' . PHP_EOL;
        $expected .= '            <tag name="red_kite_labs_theme_engine.themes.theme" />' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .= '    </services>' . PHP_EOL;
        $expected .= '</container>';

        $this->assertFileExists( vfsStream::url('root/src/AlphaLemon/Theme/FakeThemeBundle/Resources/views/Theme'));
        $themeFile = vfsStream::url('root/src/AlphaLemon/Theme/FakeThemeBundle/Resources/config/fake_theme.xml');
        $this->assertFileExists($themeFile);
        $this->assertEquals($expected, file_get_contents($themeFile));
        
        $expected = '{' . PHP_EOL;
        $expected .= '    "autoload": {' . PHP_EOL;
        $expected .= '        "psr-0": { "AlphaLemon\\\\Theme\\\\FakeThemeBundle": ""' . PHP_EOL;
        $expected .= '        }' . PHP_EOL;
        $expected .= '    },' . PHP_EOL;
        $expected .= '    "target-dir" : "AlphaLemon/Theme/FakeThemeBundle",' . PHP_EOL;
        $expected .= '    "minimum-stability": "dev"' . PHP_EOL;
        $expected .= '}';
        
        $composer = vfsStream::url('root/src/AlphaLemon/Theme/FakeThemeBundle/composer.json');
        $this->assertFileExists($composer);
        $this->assertEquals($expected, file_get_contents($composer));
        
        $expected = '{' . PHP_EOL;
        $expected .= '    "bundles" : {' . PHP_EOL;
        $expected .= '        "AlphaLemon\\\\Theme\\\\FakeThemeBundle\\\\FakeThemeBundle" : {' . PHP_EOL;
        $expected .= '            "environments" : ["all"]' . PHP_EOL;
        $expected .= '        }' . PHP_EOL;
        $expected .= '    }' . PHP_EOL;
        $expected .= '}';
        
        $autoloader = vfsStream::url('root/src/AlphaLemon/Theme/FakeThemeBundle/autoload.json');
        $this->assertFileExists($autoloader);
        $this->assertEquals($expected, file_get_contents($autoloader));
    }
}