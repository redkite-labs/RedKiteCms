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
use RedKiteLabs\RedKiteCmsBundle\Core\Generator\AlSlotsGenerator;
use org\bovigo\vfs\vfsStream;

/**
 * AlTemplateParserTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlSlotsGeneratorTest extends Base\AlGeneratorBase
{
    private $slotsGenerator;

    protected function setUp()
    {
        parent::setUp();

        $this->root = vfsStream::setup('root', null, array(
            'slots',
            'app' => array(
                'Resources' => array(
                    'views' => array(
                        'MyThemeBundle' => array(
                        ),
                    ),
                ),
            ),
        ));
        
        $skeletonDir = __DIR__ . '/../../../../Resources/skeleton';
        if ( ! is_dir($skeletonDir)) {
            $skeletonDir = __DIR__ . '/Resources/skeleton';
            if ( ! is_dir($skeletonDir)) {
                $this->markTestSkipped(
                    'skeleton dir is not available.'
                );
            }
        }
        vfsStream::copyFromFileSystem($skeletonDir, $this->root);

        $this->slotsGenerator = new AlSlotsGenerator(vfsStream::url('root/app-theme'));
    }

    public function testAnySlotIsDefinedWhenTheAttributesSectionIsNotDefined()
    {
        $contents = '<div id="logo">' . PHP_EOL;
        $contents .= '{% block logo %}' . PHP_EOL;
        $contents .= '{{ renderSlot(\'logo\') }}' . PHP_EOL;
        $contents .= '{% endblock %}' . PHP_EOL;
        $contents .= '</div>';
        file_put_contents(vfsStream::url('root/home.html.twig'), $contents);

        $information = $this->parser->parse();
        $this->assertCount(0, $information);
    }
    public function testAnyOptionalSlotAttributeIsDefined()
    {
        $contents = '<div id="logo">' . PHP_EOL;
        $contents .= '{# BEGIN-SLOT' . PHP_EOL;
        $contents .= '   name: logo' . PHP_EOL;
        $contents .= 'END-SLOT #}' . PHP_EOL;
        $contents .= '{% block logo %}' . PHP_EOL;
        $contents .= '{{ renderSlot(\'logo\') }}' . PHP_EOL;
        $contents .= '{% endblock %}' . PHP_EOL;
        $contents .= '</div>';
        file_put_contents(vfsStream::url('root/home.html.twig'), $contents);

        $information = $this->parser->parse();
        $message = $this->slotsGenerator->generateSlots(vfsStream::url('root/slots'), 'FakeThemeBundle', 'home', $information['home.html.twig']['slots']);

        $this->assertFileExists(vfsStream::url('root/slots/home.xml'));
        $this->assertEquals($this->initOneSlotServicesFile(), file_get_contents(vfsStream::url('root/slots/home.xml')));

        $expected = 'The template\'s slots <info>home.xml</info> has been generated into <info>vfs://root/slots</info>';
        $this->assertEquals($expected, $message);
    }

    public function testDeclarationBlockHasSomeSpacesBeforeCarriageReturn()
    {
        $contents = '<div id="logo">' . PHP_EOL;
        $contents .= '{# BEGIN-SLOT    ' . PHP_EOL;
        $contents .= '   name: logo' . PHP_EOL;
        $contents .= 'END-SLOT #}' . PHP_EOL;
        $contents .= '{% block logo %}' . PHP_EOL;
        $contents .= '{{ renderSlot(\'logo\') }}' . PHP_EOL;
        $contents .= '{% endblock %}' . PHP_EOL;
        $contents .= '</div>';
        file_put_contents(vfsStream::url('root/home.html.twig'), $contents);

        $information = $this->parser->parse();
        $message = $this->slotsGenerator->generateSlots(vfsStream::url('root/slots'), 'FakeThemeBundle', 'home', $information['home.html.twig']['slots']);

        $this->assertFileExists(vfsStream::url('root/slots/home.xml'));
        $this->assertEquals($this->initOneSlotServicesFile(), file_get_contents(vfsStream::url('root/slots/home.xml')));

        $expected = 'The template\'s slots <info>home.xml</info> has been generated into <info>vfs://root/slots</info>';
        $this->assertEquals($expected, $message);
    }

    public function testDeclarationBlockHasSomeSpacesBefore()
    {
        $contents = '<div id="logo">' . PHP_EOL;
        $contents .= '  {# BEGIN-SLOT' . PHP_EOL;
        $contents .= '    name: logo' . PHP_EOL;
        $contents .= '  END-SLOT #}' . PHP_EOL;
        $contents .= '{% block logo %}' . PHP_EOL;
        $contents .= '{{ renderSlot(\'logo\') }}' . PHP_EOL;
        $contents .= '{% endblock %}' . PHP_EOL;
        $contents .= '</div>';
        file_put_contents(vfsStream::url('root/home.html.twig'), $contents);

        $information = $this->parser->parse();
        $message = $this->slotsGenerator->generateSlots(vfsStream::url('root/slots'), 'FakeThemeBundle', 'home', $information['home.html.twig']['slots']);

        $this->assertFileExists(vfsStream::url('root/slots/home.xml'));
        $this->assertEquals($this->initOneSlotServicesFile(), file_get_contents(vfsStream::url('root/slots/home.xml')));

        $expected = 'The template\'s slots <info>home.xml</info> has been generated into <info>vfs://root/slots</info>';
        $this->assertEquals($expected, $message);
    }

    public function testDeclarationBlockBeginAndEndAreNotWellAligned()
    {
        $contents = '<div id="logo">' . PHP_EOL;
        $contents .= '  {# BEGIN-SLOT' . PHP_EOL;
        $contents .= '    name: logo' . PHP_EOL;
        $contents .= 'END-SLOT #}' . PHP_EOL;
        $contents .= '{% block logo %}' . PHP_EOL;
        $contents .= '{{ renderSlot(\'logo\') }}' . PHP_EOL;
        $contents .= '{% endblock %}' . PHP_EOL;
        $contents .= '</div>';
        file_put_contents(vfsStream::url('root/home.html.twig'), $contents);

        $information = $this->parser->parse();
        $message = $this->slotsGenerator->generateSlots(vfsStream::url('root/slots'), 'FakeThemeBundle', 'home', $information['home.html.twig']['slots']);

        $this->assertFileExists(vfsStream::url('root/slots/home.xml'));
        $this->assertEquals($this->initOneSlotServicesFile(), file_get_contents(vfsStream::url('root/slots/home.xml')));

        $expected = 'The template\'s slots <info>home.xml</info> has been generated into <info>vfs://root/slots</info>';
        $this->assertEquals($expected, $message);
    }

    public function testAttributesAreAlignedWithDeclarationBlock()
    {
        $contents = '<div id="logo">' . PHP_EOL;
        $contents .= '  {# BEGIN-SLOT' . PHP_EOL;
        $contents .= '  name: logo' . PHP_EOL;
        $contents .= '  END-SLOT #}' . PHP_EOL;
        $contents .= '{% block logo %}' . PHP_EOL;
        $contents .= '{{ renderSlot(\'logo\') }}' . PHP_EOL;
        $contents .= '{% endblock %}' . PHP_EOL;
        $contents .= '</div>';
        file_put_contents(vfsStream::url('root/home.html.twig'), $contents);

        $information = $this->parser->parse();
        $message = $this->slotsGenerator->generateSlots(vfsStream::url('root/slots'), 'FakeThemeBundle', 'home', $information['home.html.twig']['slots']);

        $this->assertFileExists(vfsStream::url('root/slots/home.xml'));
        $this->assertEquals($this->initOneSlotServicesFile(), file_get_contents(vfsStream::url('root/slots/home.xml')));

        $expected = 'The template\'s slots <info>home.xml</info> has been generated into <info>vfs://root/slots</info>';
        $this->assertEquals($expected, $message);
    }

    public function testSlotIsNotParsedWhenBeginDeclarationBlockIsMalformed()
    {
        $contents = '<div id="logo">' . PHP_EOL;
        $contents .= '{# BEGIN-SLOT Fake' . PHP_EOL;
        $contents .= '   name: logo' . PHP_EOL;
        $contents .= 'END-SLOT #}' . PHP_EOL;
        $contents .= '{% block logo %}' . PHP_EOL;
        $contents .= '{{ renderSlot(\'logo\') }}' . PHP_EOL;
        $contents .= '{% endblock %}' . PHP_EOL;
        $contents .= '</div>';
        file_put_contents(vfsStream::url('root/home.html.twig'), $contents);

        $information = $this->parser->parse();
        $this->assertCount(0, $information);
    }

    public function testSlotIsNotParsedWhenEndDeclarationBlockIsMalformed()
    {
        $contents = '<div id="logo">' . PHP_EOL;
        $contents .= '{# BEGIN-SLOT' . PHP_EOL;
        $contents .= '   name: logo' . PHP_EOL;
        $contents .= 'Fake END-SLOT #}' . PHP_EOL;
        $contents .= '{% block logo %}' . PHP_EOL;
        $contents .= '{{ renderSlot(\'logo\') }}' . PHP_EOL;
        $contents .= '{% endblock %}' . PHP_EOL;
        $contents .= '</div>';
        file_put_contents(vfsStream::url('root/home.html.twig'), $contents);

        $information = $this->parser->parse();
        $this->assertCount(0, $information);
    }

    public function testSlotsConfigurationFileHasBeenGenerated()
    {
        $contents = '<div id="logo">' . PHP_EOL;
        $contents .= '{% block logo %}' . PHP_EOL;
        $contents .= '{# BEGIN-SLOT' . PHP_EOL;
        $contents .= '   name: logo' . PHP_EOL;
        $contents .= '   repeated: site' . PHP_EOL;
        $contents .= '   fake: site' . PHP_EOL;
        $contents .= '   blockType: script' . PHP_EOL;
        $contents .= '   htmlContent: |' . PHP_EOL;
        $contents .= '       <img src="/uploads/assets/media/business-website-original-logo.png" title="Progress website logo" alt="Progress website logo" />' . PHP_EOL;
        $contents .= 'END-SLOT #}' . PHP_EOL;
        $contents .= '{{ renderSlot(\'logo\') }}' . PHP_EOL;
        $contents .= '{% endblock %}' . PHP_EOL;
        $contents .= '</div>';
        file_put_contents(vfsStream::url('root/home.html.twig'), $contents);

        $information = $this->parser->parse();
        $message = $this->slotsGenerator->generateSlots(vfsStream::url('root/slots'), 'FakeThemeBundle', 'home', $information['home.html.twig']['slots']);

        $expected = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
        $expected .= '<container xmlns="http://symfony.com/schema/dic/services"' . PHP_EOL;
        $expected .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $expected .= '        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= '    <services>' . PHP_EOL;
        $expected .= '        <service id="fake_theme.theme.template.home.slots.logo" class="%red_kite_labs_theme_engine.slot.class%" public="false">' . PHP_EOL;
        $expected .= '            <argument type="string">logo</argument>' . PHP_EOL;
        $expected .= '            <argument type="collection" >' . PHP_EOL;
        $expected .= '                <argument key="repeated">site</argument>' . PHP_EOL;
        $expected .= '                <argument key="blockType">script</argument>' . PHP_EOL;
        $expected .= '                <argument key="htmlContent">' . PHP_EOL;
        $expected .= '                    <![CDATA[<img src="/uploads/assets/media/business-website-original-logo.png" title="Progress website logo" alt="Progress website logo" />]]>' . PHP_EOL;
        $expected .= '                </argument>' . PHP_EOL;
        $expected .= '            </argument>' . PHP_EOL;
        $expected .= '            <tag name="fake_theme.theme.template.home.slots" />' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .= '    </services>' . PHP_EOL;
        $expected .= '</container>';

        $this->assertFileExists(vfsStream::url('root/slots/home.xml'));
        $this->assertEquals($expected, file_get_contents(vfsStream::url('root/slots/home.xml')));

        $expected = '<error>The argument site assigned to the logo slot is not recognized</error>The template\'s slots <info>home.xml</info> has been generated into <info>vfs://root/slots</info>';
        $this->assertEquals($expected, $message);
    }

    public function testSlotsConfigurationFileHasBeenGeneratedFromTheRealTheme()
    {
        $this->importDefaultTheme();
        $information = $this->parser->parse();
        $message = $this->slotsGenerator->generateSlots(vfsStream::url('root/slots'), 'FakeThemeBundle', 'home', $information['home.html.twig']['slots']);

        $this->assertFileExists(vfsStream::url('root/slots/home.xml'));
        $expected = 'The template\'s slots <info>home.xml</info> has been generated into <info>vfs://root/slots</info>';
        $this->assertEquals($expected, $message);
    }

    private function initEmptyServicesFile()
    {
        $expected = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
        $expected .= '<container xmlns="http://symfony.com/schema/dic/services"' . PHP_EOL;
        $expected .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $expected .= '        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= '    <services>' . PHP_EOL;
        $expected .= '    </services>' . PHP_EOL;
        $expected .= '</container>';

        return $expected;
    }

    private function initOneSlotServicesFile()
    {
        $expected = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
        $expected .= '<container xmlns="http://symfony.com/schema/dic/services"' . PHP_EOL;
        $expected .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $expected .= '        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= '    <services>' . PHP_EOL;
        $expected .= '        <service id="fake_theme.theme.template.home.slots.logo" class="%red_kite_labs_theme_engine.slot.class%" public="false">' . PHP_EOL;
        $expected .= '            <argument type="string">logo</argument>' . PHP_EOL;
        $expected .= '            <tag name="fake_theme.theme.template.home.slots" />' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .= '    </services>' . PHP_EOL;
        $expected .= '</container>';

        return $expected;
    }
}