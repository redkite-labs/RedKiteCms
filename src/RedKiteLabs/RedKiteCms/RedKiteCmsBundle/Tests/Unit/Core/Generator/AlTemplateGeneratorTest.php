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

use RedKiteLabs\RedKiteCmsBundle\Core\Generator\AlTemplateGenerator;
use org\bovigo\vfs\vfsStream;

/**
 * AlTemplateParserTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlTemplateGeneratorTest extends Base\AlGeneratorBase
{
    private $templateGenerator;

    protected function setUp()
    {
        parent::setUp();

        $this->root = vfsStream::setup('root', null, array(
            'template',
            'app' => array(
                'Resources' => array(
                    'views' => array(
                        'MyThemeBundle' => array(
                        ),
                    ),
                ),
            ),
        ));
        vfsStream::copyFromFileSystem(__DIR__ . '/../../../../Resources/skeleton', $this->root);

        $this->templateGenerator = new AlTemplateGenerator(vfsStream::url('root/app-theme'));
    }
    
    public function testTemplateIsNotGeneratedWhenAnySlotIsDefined()
    {
        file_put_contents(vfsStream::url('root/home.html.twig'), '');

        $information = $this->parser->parse();
        $this->assertCount(0, $information);
    }
    
    public function testTemplateConfigurationHasAnyAsset()
    {
        file_put_contents(vfsStream::url('root/home.html.twig'), $this->fetchBaseContents());

        $information = $this->parser->parse();
        $message = $this->templateGenerator->generateTemplate(vfsStream::url('root/template'), 'FakeThemeBundle', 'home', $information['home.html.twig']['assets']);

        $expected = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
        $expected .= '<container xmlns="http://symfony.com/schema/dic/services"' . PHP_EOL;
        $expected .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $expected .= '        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= '    <services>' . PHP_EOL;
        $expected .= '        <service id="fake_theme.theme.template_assets.home" class="%red_kite_labs_theme_engine.template_assets.class%" public="false">' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '        <service id="fake_theme.theme.template.home.slots" class="%red_kite_labs_theme_engine.template_slots.class%" public="false">' . PHP_EOL;
        $expected .= '            <tag name="fake_theme.theme.template.home" />' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '        <service id="fake_theme.theme.template.home" class="%red_kite_labs_theme_engine.template.class%" public="false">' . PHP_EOL;
        $expected .= '            <argument type="service" id="kernel" />' . PHP_EOL;
        $expected .= '            <argument type="service" id="fake_theme.theme.template_assets.home" />' . PHP_EOL;
        $expected .= '            <argument type="service" id="fake_theme.theme.template.home.slots" />' . PHP_EOL;
        $expected .= '            <tag name="fake_theme.theme.template" />' . PHP_EOL;
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
        $contents .= '@RedKiteLabsRedKiteCmsBundle/Resources/public/js/vendor/jquery/*' . PHP_EOL;
        $contents .= 'END-EXTERNAL-JAVASCRIPTS #}' . PHP_EOL;        
        $contents .= $this->fetchBaseContents();
        
        file_put_contents(vfsStream::url('root/home.html.twig'), $contents);

        $information = $this->parser->parse();
        $message = $this->templateGenerator->generateTemplate(vfsStream::url('root/template'), 'FakeThemeBundle', 'home', $information['home.html.twig']['assets']);

        $expected = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
        $expected .= '<container xmlns="http://symfony.com/schema/dic/services"' . PHP_EOL;
        $expected .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $expected .= '        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= '    <parameters>' . PHP_EOL;
        $expected .= '        <parameter key="fake_theme.home.external_stylesheets" type="collection">' . PHP_EOL;
        $expected .= '            <parameter>@BusinessWebsiteThemeBundle/Resources/public/css/reset.css</parameter>' . PHP_EOL;
        $expected .= '            <parameter>@BusinessWebsiteThemeBundle/Resources/public/css/layout.css</parameter>' . PHP_EOL;
        $expected .= '        </parameter>' . PHP_EOL;
        $expected .= '        <parameter key="fake_theme.home.external_javascripts" type="collection">' . PHP_EOL;
        $expected .= '            <parameter>@RedKiteLabsRedKiteCmsBundle/Resources/public/js/vendor/jquery/*</parameter>' . PHP_EOL;
        $expected .= '        </parameter>' . PHP_EOL;
        $expected .= '        <parameter key="fake_theme.home.external_stylesheets.cms" type="collection">' . PHP_EOL;
        $expected .= '        </parameter>' . PHP_EOL;
        $expected .= '    </parameters>' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= '    <services>' . PHP_EOL;
        $expected .= '        <service id="fake_theme.theme.template_assets.home" class="%red_kite_labs_theme_engine.template_assets.class%" public="false">' . PHP_EOL;
        $expected .= '            <call method="setExternalStylesheets">' . PHP_EOL;
        $expected .= '                <argument>%fake_theme.home.external_stylesheets%</argument>' . PHP_EOL;
        $expected .= '            </call>' . PHP_EOL;
        $expected .= '            <call method="setExternalJavascripts">' . PHP_EOL;
        $expected .= '                <argument>%fake_theme.home.external_javascripts%</argument>' . PHP_EOL;
        $expected .= '            </call>' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '        <service id="fake_theme.theme.template.home.slots" class="%red_kite_labs_theme_engine.template_slots.class%" public="false">' . PHP_EOL;
        $expected .= '            <tag name="fake_theme.theme.template.home" />' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '        <service id="fake_theme.theme.template.home" class="%red_kite_labs_theme_engine.template.class%" public="false">' . PHP_EOL;
        $expected .= '            <argument type="service" id="kernel" />' . PHP_EOL;
        $expected .= '            <argument type="service" id="fake_theme.theme.template_assets.home" />' . PHP_EOL;
        $expected .= '            <argument type="service" id="fake_theme.theme.template.home.slots" />' . PHP_EOL;
        $expected .= '            <tag name="fake_theme.theme.template" />' . PHP_EOL;
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
        $contents .= '@RedKiteLabsRedKiteCmsBundle/Resources/public/js/vendor/jquery/*' . PHP_EOL;
        $contents .= 'END-CMS-JAVASCRIPTS #}';
        $contents .= '{# BEGIN-EXTERNAL-STYLESHEETS' . PHP_EOL;
        $contents .= '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css' . PHP_EOL;
        $contents .= '@BusinessWebsiteThemeBundle/Resources/public/css/layout.css' . PHP_EOL;
        $contents .= 'END-EXTERNAL-STYLESHEETS #}' . PHP_EOL;
        $contents .= '{# BEGIN-EXTERNAL-JAVASCRIPTS' . PHP_EOL;
        $contents .= '@RedKiteLabsRedKiteCmsBundle/Resources/public/js/vendor/jquery/*' . PHP_EOL;
        $contents .= 'END-EXTERNAL-JAVASCRIPTS #}' . PHP_EOL;        
        $contents .= $this->fetchBaseContents();
        
        file_put_contents(vfsStream::url('root/home.html.twig'), $contents);

        $information = $this->parser->parse();
        $message = $this->templateGenerator->generateTemplate(vfsStream::url('root/template'), 'FakeThemeBundle', 'home', $information['home.html.twig']['assets']);

        $expected = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
        $expected .= '<container xmlns="http://symfony.com/schema/dic/services"' . PHP_EOL;
        $expected .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $expected .= '        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= '    <parameters>' . PHP_EOL;
        $expected .= '        <parameter key="fake_theme.home.external_stylesheets" type="collection">' . PHP_EOL;
        $expected .= '            <parameter>@BusinessWebsiteThemeBundle/Resources/public/css/reset.css</parameter>' . PHP_EOL;
        $expected .= '            <parameter>@BusinessWebsiteThemeBundle/Resources/public/css/layout.css</parameter>' . PHP_EOL;
        $expected .= '        </parameter>' . PHP_EOL;
        $expected .= '        <parameter key="fake_theme.home.external_javascripts" type="collection">' . PHP_EOL;
        $expected .= '            <parameter>@RedKiteLabsRedKiteCmsBundle/Resources/public/js/vendor/jquery/*</parameter>' . PHP_EOL;
        $expected .= '        </parameter>' . PHP_EOL;
        $expected .= '        <parameter key="fake_theme.home.external_stylesheets.cms" type="collection">' . PHP_EOL;
        $expected .= '            <parameter>@BusinessWebsiteThemeBundle/Resources/public/css/cms_fix.css</parameter>' . PHP_EOL;
        $expected .= '        </parameter>' . PHP_EOL;
        $expected .= '        <parameter key="fake_theme.home.external_javascripts.cms" type="collection">' . PHP_EOL;
        $expected .= '            <parameter>@RedKiteLabsRedKiteCmsBundle/Resources/public/js/vendor/jquery/*</parameter>' . PHP_EOL;
        $expected .= '        </parameter>' . PHP_EOL;
        $expected .= '    </parameters>' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '    <services>' . PHP_EOL;
        $expected .= '        <service id="fake_theme.theme.template_assets.home" class="%red_kite_labs_theme_engine.template_assets.class%" public="false">' . PHP_EOL;
        $expected .= '            <call method="setExternalStylesheets">' . PHP_EOL;
        $expected .= '                <argument>%fake_theme.home.external_stylesheets%</argument>' . PHP_EOL;
        $expected .= '            </call>' . PHP_EOL;
        $expected .= '            <call method="setExternalJavascripts">' . PHP_EOL;
        $expected .= '                <argument>%fake_theme.home.external_javascripts%</argument>' . PHP_EOL;
        $expected .= '            </call>' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '        <service id="fake_theme.theme.template.home.slots" class="%red_kite_labs_theme_engine.template_slots.class%" public="false">' . PHP_EOL;
        $expected .= '            <tag name="fake_theme.theme.template.home" />' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '        <service id="fake_theme.theme.template.home" class="%red_kite_labs_theme_engine.template.class%" public="false">' . PHP_EOL;
        $expected .= '            <argument type="service" id="kernel" />' . PHP_EOL;
        $expected .= '            <argument type="service" id="fake_theme.theme.template_assets.home" />' . PHP_EOL;
        $expected .= '            <argument type="service" id="fake_theme.theme.template.home.slots" />' . PHP_EOL;
        $expected .= '            <tag name="fake_theme.theme.template" />' . PHP_EOL;
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
    
    private function fetchBaseContents()
    {
        $contents = '<div class="grid_4 alpha header_box">' . PHP_EOL;
        $contents .= '    {% block header_box_1 %}' . PHP_EOL;
        $contents .= '        {# BEGIN-SLOT' . PHP_EOL;
        $contents .= '            name: header_box_1' . PHP_EOL;
        $contents .= '            repeated: language' . PHP_EOL;
        $contents .= '            htmlContent: |' . PHP_EOL;
        $contents .= '                Lorem ipsum' . PHP_EOL;
        $contents .= '        END-SLOT #}' . PHP_EOL;
        $contents .= '        {{ renderSlot(\'header_box_1\') }}' . PHP_EOL;
        $contents .= '    {% endblock %} ' . PHP_EOL;
        $contents .= '</div>';
        
        return $contents;
    }
}