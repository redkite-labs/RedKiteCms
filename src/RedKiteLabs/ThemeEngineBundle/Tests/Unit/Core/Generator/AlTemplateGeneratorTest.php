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
use AlphaLemon\ThemeEngineBundle\Core\Generator\AlTemplateGenerator;
use org\bovigo\vfs\vfsStream;

/**
 * AlTemplateParserTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlTemplateGeneratorTest extends Base\AlGeneratorBase
{
    private $templateGenerator;

    protected function setUp()
    {
        parent::setUp();

        $this->root = vfsStream::setup('root', null, array('template'));
        vfsStream::copyFromFileSystem(__DIR__ . '/../../../../Resources/skeleton', $this->root);

        $this->templateGenerator = new AlTemplateGenerator(vfsStream::url('root/app-theme'));
    }

    public function testTemplateConfigurationHasAnyAsset()
    {
        file_put_contents(vfsStream::url('root/home.html.twig'), '');

        $information = $this->parser->parse();
        $message = $this->templateGenerator->generateTemplate(vfsStream::url('root/template'), 'FakeThemeBundle', 'home', $information['home.html.twig']['assets']);

        $expected = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
        $expected .= '<container xmlns="http://symfony.com/schema/dic/services"' . PHP_EOL;
        $expected .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $expected .= '        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= '    <services>' . PHP_EOL;
        $expected .= '        <service id="fake.theme.template_assets.home" class="%alpha_lemon_theme_engine.template_assets.class%">' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '        <service id="fake.theme.template.home.slots" class="%alpha_lemon_theme_engine.template_slots.class%">' . PHP_EOL;
        $expected .= '            <tag name="fake.theme.template.home" />' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '        <service id="fake.theme.template.home" class="%alpha_lemon_theme_engine.template.class%">' . PHP_EOL;
        $expected .= '            <argument type="service" id="kernel" />' . PHP_EOL;
        $expected .= '            <argument type="service" id="fake.theme.template_assets.home" />' . PHP_EOL;
        $expected .= '            <argument type="service" id="fake.theme.template.home.slots" />' . PHP_EOL;
        $expected .= '            <tag name="fake.theme.template" />' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '            <call method="setThemeName">' . PHP_EOL;
        $expected .= '                <argument type="string">FakeThemeBundle</argument>' . PHP_EOL;
        $expected .= '            </call>' . PHP_EOL;
        $expected .= '            <call method="setTemplateName">' . PHP_EOL;
        $expected .= '                <argument type="string">home</argument>' . PHP_EOL;
        $expected .= '            </call>' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .= '    </services>' . PHP_EOL;
        $expected .= '</container>';

        $this->assertFileExists(vfsStream::url('root/template/home.xml'));
        $this->assertEquals($expected, file_get_contents(vfsStream::url('root/template/home.xml')));

        $expected = 'The template <info>home.xml</info> has been generated into <info>vfs://root/template</info>';
        $this->assertEquals($expected, $message);
    }

    public function testTemplateConfigurationHasAnyEnvironmentAsset()
    {
        $contents = '{# BEGIN-EXTERNAL-STYLESHEETS' . PHP_EOL;
        $contents .= '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css' . PHP_EOL;
        $contents .= '@BusinessWebsiteThemeBundle/Resources/public/css/layout.css' . PHP_EOL;
        $contents .= 'END-EXTERNAL-STYLESHEETS #}' . PHP_EOL;
        $contents .= '{# BEGIN-EXTERNAL-JAVASCRIPTS' . PHP_EOL;
        $contents .= '@AlphaLemonThemeEngineBundle/Resources/public/js/vendor/jquery/*' . PHP_EOL;
        $contents .= 'END-EXTERNAL-JAVASCRIPTS #}';
        file_put_contents(vfsStream::url('root/home.html.twig'), $contents);

        $information = $this->parser->parse();
        $message = $this->templateGenerator->generateTemplate(vfsStream::url('root/template'), 'FakeThemeBundle', 'home', $information['home.html.twig']['assets']);

        $expected = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
        $expected .= '<container xmlns="http://symfony.com/schema/dic/services"' . PHP_EOL;
        $expected .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $expected .= '        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= '    <parameters>' . PHP_EOL;
        $expected .= '        <parameter key="fake.home.external_stylesheets" type="collection">' . PHP_EOL;
        $expected .= '            <parameter>@BusinessWebsiteThemeBundle/Resources/public/css/reset.css</parameter>' . PHP_EOL;
        $expected .= '            <parameter>@BusinessWebsiteThemeBundle/Resources/public/css/layout.css</parameter>' . PHP_EOL;
        $expected .= '        </parameter>' . PHP_EOL;
        $expected .= '        <parameter key="fake.home.external_javascripts" type="collection">' . PHP_EOL;
        $expected .= '            <parameter>@AlphaLemonThemeEngineBundle/Resources/public/js/vendor/jquery/*</parameter>' . PHP_EOL;
        $expected .= '        </parameter>' . PHP_EOL;
        $expected .= '        <parameter key="fake.home.external_stylesheets.cms" type="collection">' . PHP_EOL;
        $expected .= '        </parameter>' . PHP_EOL;
        $expected .= '    </parameters>' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= '    <services>' . PHP_EOL;
        $expected .= '        <service id="fake.theme.template_assets.home" class="%alpha_lemon_theme_engine.template_assets.class%">' . PHP_EOL;
        $expected .= '            <call method="setExternalStylesheets">' . PHP_EOL;
        $expected .= '                <argument>%fake.home.external_stylesheets%</argument>' . PHP_EOL;
        $expected .= '            </call>' . PHP_EOL;
        $expected .= '            <call method="setExternalJavascripts">' . PHP_EOL;
        $expected .= '                <argument>%fake.home.external_javascripts%</argument>' . PHP_EOL;
        $expected .= '            </call>' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '        <service id="fake.theme.template.home.slots" class="%alpha_lemon_theme_engine.template_slots.class%">' . PHP_EOL;
        $expected .= '            <tag name="fake.theme.template.home" />' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '        <service id="fake.theme.template.home" class="%alpha_lemon_theme_engine.template.class%">' . PHP_EOL;
        $expected .= '            <argument type="service" id="kernel" />' . PHP_EOL;
        $expected .= '            <argument type="service" id="fake.theme.template_assets.home" />' . PHP_EOL;
        $expected .= '            <argument type="service" id="fake.theme.template.home.slots" />' . PHP_EOL;
        $expected .= '            <tag name="fake.theme.template" />' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '            <call method="setThemeName">' . PHP_EOL;
        $expected .= '                <argument type="string">FakeThemeBundle</argument>' . PHP_EOL;
        $expected .= '            </call>' . PHP_EOL;
        $expected .= '            <call method="setTemplateName">' . PHP_EOL;
        $expected .= '                <argument type="string">home</argument>' . PHP_EOL;
        $expected .= '            </call>' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .= '    </services>' . PHP_EOL;
        $expected .= '</container>';

        $this->assertFileExists(vfsStream::url('root/template/home.xml'));
        $this->assertEquals($expected, file_get_contents(vfsStream::url('root/template/home.xml')));

        $expected = 'The template <info>home.xml</info> has been generated into <info>vfs://root/template</info>';
        $this->assertEquals($expected, $message);
    }

    public function testTemplateConfigurationFileHasBeenGenerated()
    {
        $contents = '{# BEGIN-CMS-STYLESHEETS' . PHP_EOL;
        $contents .= '@BusinessWebsiteThemeBundle/Resources/public/css/cms_fix.css' . PHP_EOL;
        $contents .= 'END-CMS-STYLESHEETS #}' . PHP_EOL;
        $contents .= '{# BEGIN-CMS-JAVASCRIPTS' . PHP_EOL;
        $contents .= '@AlphaLemonThemeEngineBundle/Resources/public/js/vendor/jquery/*' . PHP_EOL;
        $contents .= 'END-CMS-JAVASCRIPTS #}';
        $contents .= '{# BEGIN-EXTERNAL-STYLESHEETS' . PHP_EOL;
        $contents .= '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css' . PHP_EOL;
        $contents .= '@BusinessWebsiteThemeBundle/Resources/public/css/layout.css' . PHP_EOL;
        $contents .= 'END-EXTERNAL-STYLESHEETS #}' . PHP_EOL;
        $contents .= '{# BEGIN-EXTERNAL-JAVASCRIPTS' . PHP_EOL;
        $contents .= '@AlphaLemonThemeEngineBundle/Resources/public/js/vendor/jquery/*' . PHP_EOL;
        $contents .= 'END-EXTERNAL-JAVASCRIPTS #}';
        file_put_contents(vfsStream::url('root/home.html.twig'), $contents);

        $information = $this->parser->parse();
        $message = $this->templateGenerator->generateTemplate(vfsStream::url('root/template'), 'FakeThemeBundle', 'home', $information['home.html.twig']['assets']);

        $expected = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
        $expected .= '<container xmlns="http://symfony.com/schema/dic/services"' . PHP_EOL;
        $expected .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $expected .= '        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= '    <parameters>' . PHP_EOL;
        $expected .= '        <parameter key="fake.home.external_stylesheets" type="collection">' . PHP_EOL;
        $expected .= '            <parameter>@BusinessWebsiteThemeBundle/Resources/public/css/reset.css</parameter>' . PHP_EOL;
        $expected .= '            <parameter>@BusinessWebsiteThemeBundle/Resources/public/css/layout.css</parameter>' . PHP_EOL;
        $expected .= '        </parameter>' . PHP_EOL;
        $expected .= '        <parameter key="fake.home.external_javascripts" type="collection">' . PHP_EOL;
        $expected .= '            <parameter>@AlphaLemonThemeEngineBundle/Resources/public/js/vendor/jquery/*</parameter>' . PHP_EOL;
        $expected .= '        </parameter>' . PHP_EOL;
        $expected .= '        <parameter key="fake.home.external_stylesheets.cms" type="collection">' . PHP_EOL;
        $expected .= '            <parameter>@BusinessWebsiteThemeBundle/Resources/public/css/cms_fix.css</parameter>' . PHP_EOL;
        $expected .= '        </parameter>' . PHP_EOL;
        $expected .= '        <parameter key="fake.home.external_javascripts.cms" type="collection">' . PHP_EOL;
        $expected .= '            <parameter>@AlphaLemonThemeEngineBundle/Resources/public/js/vendor/jquery/*</parameter>' . PHP_EOL;
        $expected .= '        </parameter>' . PHP_EOL;
        $expected .= '    </parameters>' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '    <services>' . PHP_EOL;
        $expected .= '        <service id="fake.theme.template_assets.home" class="%alpha_lemon_theme_engine.template_assets.class%">' . PHP_EOL;
        $expected .= '            <call method="setExternalStylesheets">' . PHP_EOL;
        $expected .= '                <argument>%fake.home.external_stylesheets%</argument>' . PHP_EOL;
        $expected .= '            </call>' . PHP_EOL;
        $expected .= '            <call method="setExternalJavascripts">' . PHP_EOL;
        $expected .= '                <argument>%fake.home.external_javascripts%</argument>' . PHP_EOL;
        $expected .= '            </call>' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '        <service id="fake.theme.template.home.slots" class="%alpha_lemon_theme_engine.template_slots.class%">' . PHP_EOL;
        $expected .= '            <tag name="fake.theme.template.home" />' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '        <service id="fake.theme.template.home" class="%alpha_lemon_theme_engine.template.class%">' . PHP_EOL;
        $expected .= '            <argument type="service" id="kernel" />' . PHP_EOL;
        $expected .= '            <argument type="service" id="fake.theme.template_assets.home" />' . PHP_EOL;
        $expected .= '            <argument type="service" id="fake.theme.template.home.slots" />' . PHP_EOL;
        $expected .= '            <tag name="fake.theme.template" />' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '            <call method="setThemeName">' . PHP_EOL;
        $expected .= '                <argument type="string">FakeThemeBundle</argument>' . PHP_EOL;
        $expected .= '            </call>' . PHP_EOL;
        $expected .= '            <call method="setTemplateName">' . PHP_EOL;
        $expected .= '                <argument type="string">home</argument>' . PHP_EOL;
        $expected .= '            </call>' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .= '    </services>' . PHP_EOL;
        $expected .= '</container>';
        
        $this->assertFileExists(vfsStream::url('root/template/home.xml'));
        $this->assertEquals($expected, file_get_contents(vfsStream::url('root/template/home.xml')));

        $expected = 'The template <info>home.xml</info> has been generated into <info>vfs://root/template</info>';
        $this->assertEquals($expected, $message);
    }

    public function testTemplateConfigurationFileHasBeenGeneratedFromTheRealTheme()
    {
        $this->importDefaultTheme();
        $information = $this->parser->parse();
        $message = $this->templateGenerator->generateTemplate(vfsStream::url('root/template'), 'FakeThemeBundle', 'home', $information['home.html.twig']['assets']);

        $this->assertFileExists(vfsStream::url('root/template/home.xml'));
        $expected = 'The template <info>home.xml</info> has been generated into <info>vfs://root/template</info>';
        $this->assertEquals($expected, $message);
    }
}