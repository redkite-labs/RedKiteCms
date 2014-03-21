<?php
/**
 * This file is part of the RedKiteLabsRedKiteCmsBundle and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Generator;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Generator\TemplateGenerator;
use org\bovigo\vfs\vfsStream;

/**
 * TemplateParserTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class TemplateGeneratorTest extends Base\GeneratorBase
{
    private $templateGenerator;

    protected function setUp()
    {
        parent::setUp();

        $this->root = vfsStream::setup('root', null, array(
            'template',
            'Slots' => array(),
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

        $this->templateGenerator = new TemplateGenerator(vfsStream::url('root/app-theme'));
    }
    /*
    public function testTemplateIsNotGeneratedWhenAnySlotIsDefined()
    {
        file_put_contents(vfsStream::url('root/home.html.twig'), '');

        $information = $this->parser->parse(vfsStream::url('root'), vfsStream::url('root/app'), 'MyThemeBundle');
        $this->assertCount(0, $information);
    }*/
    
    public function testTemplateConfigurationHasAnyAsset()
    {
        file_put_contents(vfsStream::url('root/home.html.twig'), $this->fetchBaseContents());

        $information = $this->parser->parse(vfsStream::url('root/Slots'), vfsStream::url('root/app'), 'MyThemeBundle');
        $message = $this->templateGenerator->generateTemplate(vfsStream::url('root/template'), 'FakeThemeBundle', 'home', array('logo', 'menu', 'footer'));

        $expected = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
        $expected .= '<container xmlns="http://symfony.com/schema/dic/services"' . PHP_EOL;
        $expected .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $expected .= '        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= '    <parameters>' . PHP_EOL;
        $expected .= '        <parameter key="fake_theme.home.slots" type="collection">' . PHP_EOL;
        $expected .= '            <parameter>logo</parameter>' . PHP_EOL;
        $expected .= '            <parameter>menu</parameter>' . PHP_EOL;
        $expected .= '            <parameter>footer</parameter>' . PHP_EOL;
        $expected .= '        </parameter>' . PHP_EOL;
        $expected .= '    </parameters>' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= '    <services>' . PHP_EOL;
        $expected .= '        <service id="fake_theme.theme.template_assets.home" class="%red_kite_labs_theme_engine.template_assets.class%" public="false">' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '        <service id="fake_theme.theme.template.home" class="%red_kite_labs_theme_engine.template.class%" public="false">' . PHP_EOL;
        $expected .= '            <argument type="service" id="kernel" />' . PHP_EOL;
        $expected .= '            <argument type="service" id="fake_theme.theme.template_assets.home" />' . PHP_EOL;
        $expected .= '            <tag name="fake_theme.theme.template" />' . PHP_EOL;
        $expected .=  PHP_EOL;
        $expected .= '            <call method="setThemeName">' . PHP_EOL;
        $expected .= '                <argument type="string">FakeThemeBundle</argument>' . PHP_EOL;
        $expected .= '            </call>' . PHP_EOL;
        $expected .= '            <call method="setTemplateName">' . PHP_EOL;
        $expected .= '                <argument type="string">home</argument>' . PHP_EOL;
        $expected .= '            </call>' . PHP_EOL;
        $expected .= '            <call method="setSlots">' . PHP_EOL;
        $expected .= '                <argument>%fake_theme.home.slots%</argument>' . PHP_EOL;
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
        $information = $this->parser->parse(vfsStream::url('root/Slots'), vfsStream::url('root/app'), 'MyThemeBundle');
        $message = $this->templateGenerator->generateTemplate(vfsStream::url('root/template'), 'FakeThemeBundle', 'home', array());
        
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