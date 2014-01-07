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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Generator\TemplateParser;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Generator\Base\AlGeneratorBase;
use org\bovigo\vfs\vfsStream;

/**
 * AlTemplateParserTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlTemplateParserTest extends AlGeneratorBase
{
    protected $root;
    protected $parser;

    protected function setUp()
    {
        parent::setUp();

        $this->root = vfsStream::setup('root', null, array(
            'Theme' => array(),
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
    }
    
    /**
     * @dataProvider templatesAndSlotsProvider
     */
    public function testParseTemplatesAndSlots($templateContents, $slotContents, $expectedSlots = array())
    {
        $contents = '<div id="logo">' . PHP_EOL;
        $contents .= '    {{ block(\'logo\') }}' . PHP_EOL;
        $contents .= '</div>';
        file_put_contents(vfsStream::url('root/Theme/home.html.twig'), $templateContents);
        
        file_put_contents(vfsStream::url('root/Slots/slots.html.twig'), $slotContents);
        $information = $this->parser->parse(vfsStream::url('root/Theme'), vfsStream::url('root/app'), 'MyThemeBundle');
        $this->assertCount(1, $information["templates"]);
        $this->assertEquals('home.html.twig', $information["templates"][0]["name"]);
        $this->assertCount(count($expectedSlots), $information["templates"][0]["slots"]);
        $this->assertEquals($expectedSlots, $information["templates"][0]["slots"]);
    }
    
    public function templatesAndSlotsProvider()
    {
        return array(
            array(
                '<div id="logo">' . PHP_EOL .
                '    {{ block(\'logo\') }}' . PHP_EOL .
                '</div>',
                '{% block logo %}' . PHP_EOL .
                '{# BEGIN-SLOT' . PHP_EOL .
                '   name: logo' . PHP_EOL .
                'END-SLOT #}' . PHP_EOL .
                '{{ renderSlot(\'logo\') }}' . PHP_EOL .
                '{% endblock %}' . PHP_EOL,
                array(
                    'logo',
                ),
            ),
            array(
                '<div id="logo">' . PHP_EOL .
                '    {{ block(\'logo\') }}' . PHP_EOL .
                '</div>',
                '{% block menu %}' . PHP_EOL .
                '{# BEGIN-SLOT' . PHP_EOL .
                '   name: menu' . PHP_EOL .
                'END-SLOT #}' . PHP_EOL .
                '{{ renderSlot(\'menu\') }}' . PHP_EOL .
                '{% endblock %}' . PHP_EOL,
            ),
            array(
                '<div id="logo">' . PHP_EOL .
                '    {{ block(\'logo\') }}' . PHP_EOL .
                '</div>' . PHP_EOL . 
                '<div id="logo">' . PHP_EOL .
                '    {{ block(\'menu\') }}' . PHP_EOL .
                '</div>' . PHP_EOL . 
                '<div id="content">' . PHP_EOL .
                '    {{ block(\'content\') }}' . PHP_EOL .
                '</div>',
                '{% block logo %}' . PHP_EOL .
                '{# BEGIN-SLOT' . PHP_EOL .
                '   name: logo' . PHP_EOL .
                'END-SLOT #}' . PHP_EOL .
                '{{ renderSlot(\'logo\') }}' . PHP_EOL .
                '{% endblock %}' . PHP_EOL . PHP_EOL .
                '{% block content %}' . PHP_EOL .
                '{# BEGIN-SLOT' . PHP_EOL .
                '   name: content' . PHP_EOL .
                'END-SLOT #}' . PHP_EOL .
                '{{ renderSlot(\'content\') }}' . PHP_EOL .
                '{% endblock %}',
                array(
                    'logo',
                    'content',
                ),
            ),
        );
    }
    
    /**
     * @dataProvider slotsFileContentsProvider
     */
    public function testParsableSlotsFormat($slotContents)
    {
        $contents = '<div id="logo">' . PHP_EOL;
        $contents .= '{{ block("logo") }}' . PHP_EOL;
        $contents .= '</div>';
        file_put_contents(vfsStream::url('root/Theme/home.twig.html'), $contents);
        
        file_put_contents(vfsStream::url('root/Slots/slots.twig.html'), $slotContents);
        $information = $this->parser->parse(vfsStream::url('root/Theme'), vfsStream::url('root/app'), 'MyThemeBundle');
        $this->assertCount(1, $information["slots"]);
    }
    
    public function slotsFileContentsProvider()
    {
        return array(
            array(
                '{% block logo %}' . PHP_EOL .
                '{# BEGIN-SLOT' . PHP_EOL .
                '   name: logo' . PHP_EOL .
                'END-SLOT #}' . PHP_EOL .
                '{{ renderSlot(\'logo\') }}' . PHP_EOL .
                '{% endblock %}' . PHP_EOL,
            ),
            array(
                '{% block logo %}' . PHP_EOL .
                '{# BEGIN-SLOT      ' . PHP_EOL .
                '   name: logo' . PHP_EOL .
                'END-SLOT #}' . PHP_EOL .
                '{{ renderSlot(\'logo\') }}' . PHP_EOL .
                '{% endblock %}' . PHP_EOL,
            ),
            array(
                '{% block logo %}' . PHP_EOL .
                '       {# BEGIN-SLOT' . PHP_EOL .
                '   name: logo' . PHP_EOL .
                'END-SLOT #}' . PHP_EOL .
                '{{ renderSlot(\'logo\') }}' . PHP_EOL .
                '{% endblock %}' . PHP_EOL,
            ),
            array(
                '{% block logo %}' . PHP_EOL .
                '   {# BEGIN-SLOT' . PHP_EOL .
                '   name: logo' . PHP_EOL .
                '   END-SLOT #}' . PHP_EOL .
                '{{ renderSlot(\'logo\') }}' . PHP_EOL .
                '{% endblock %}' . PHP_EOL,
            ),
        );
    }

    /**
     * @dataProvider slotsFileMalformedContentsProvider
     */
    public function testNotParsableSlotsFormat($slotContents)
    {
        $contents = '<div id="logo">' . PHP_EOL;
        $contents .= '{{ block("logo") }}' . PHP_EOL;
        $contents .= '</div>';
        file_put_contents(vfsStream::url('root/Theme/home.twig.html'), $contents);        
        file_put_contents(vfsStream::url('root/Slots/slots.twig.html'), $slotContents);
        $information = $this->parser->parse(vfsStream::url('root/Theme'), vfsStream::url('root/app'), 'MyThemeBundle');
        $this->assertCount(0, $information["slots"]);
    }
    
    public function slotsFileMalformedContentsProvider()
    {
        return array(
            array(
                '{% block logo %}' . PHP_EOL .
                '{# BEGIN-SLOT Fake' . PHP_EOL .
                '   name: logo' . PHP_EOL .
                'END-SLOT #}' . PHP_EOL .
                '{{ renderSlot(\'logo\') }}' . PHP_EOL .
                '{% endblock %}' . PHP_EOL,
            ),
            array(
                '{% block logo %}' . PHP_EOL .
                '{# BEGIN-SLOT' . PHP_EOL .
                '   name: logo' . PHP_EOL .
                'Fake END-SLOT #}' . PHP_EOL .
                '{{ renderSlot(\'logo\') }}' . PHP_EOL .
                '{% endblock %}' . PHP_EOL,
            ),
            array(
                '{% block logo %}' . PHP_EOL .
                '{# BEGIN-SLOT' . PHP_EOL .
                '   name: logo' . PHP_EOL .
                '       repeated: site' . PHP_EOL .
                '   htmlContent: <p>foo</p>' . PHP_EOL .
                'Fake END-SLOT #}' . PHP_EOL .
                '{{ renderSlot(\'logo\') }}' . PHP_EOL .
                '{% endblock %}' . PHP_EOL,
            ),
            array( // mandatory name option is missing
                '{% block logo %}' . PHP_EOL .
                '{# BEGIN-SLOT' . PHP_EOL .
                '   repeated: site' . PHP_EOL .
                '   htmlContent: <p>foo</p>' . PHP_EOL .
                'Fake END-SLOT #}' . PHP_EOL .
                '{{ renderSlot(\'logo\') }}' . PHP_EOL .
                '{% endblock %}' . PHP_EOL,
            ),
        );
    }

    public function testAnErrorKeyIsAddedWhenAnUnrecognizedAttributeIsDeclared()
    {
        $contents = '<div id="logo">' . PHP_EOL;
        $contents .= '{{ block("logo") }}' . PHP_EOL;
        $contents .= '</div>';
        file_put_contents(vfsStream::url('root/Theme/home.twig.html'), $contents);
        
        $contents = '{# BEGIN-SLOT' . PHP_EOL;
        $contents .= '   name: logo' . PHP_EOL;
        $contents .= '   repeated: site' . PHP_EOL;
        $contents .= '   fake: script' . PHP_EOL;
        $contents .= '   htmlContent: |' . PHP_EOL;
        $contents .= '       <img src="/uploads/assets/media/business-website-original-logo.png" title="Progress website logo" alt="Progress website logo" />' . PHP_EOL;
        $contents .= 'END-SLOT #}' . PHP_EOL;
        $contents .= '{{ renderSlot(\'logo\') }}' . PHP_EOL;
        $contents .= '{% endblock %}' . PHP_EOL;
        file_put_contents(vfsStream::url('root/Slots/slots.twig.html'), $contents);
        $information = $this->parser->parse(vfsStream::url('root/Theme'), vfsStream::url('root/app'), 'MyThemeBundle');
        $slot = $information['slots']['logo'];
        $this->assertTrue(array_key_exists('repeated', $slot));
        $this->assertTrue(array_key_exists('htmlContent', $slot));
        $this->assertFalse(array_key_exists('fake', $slot));
        $this->assertTrue(array_key_exists('errors', $slot));
        $this->assertTrue(array_key_exists('fake', $slot['errors']));
    }

    public function testRealTheme()
    {
        $this->importDefaultTheme();
        $information = $this->parser->parse(vfsStream::url('root/Theme'), vfsStream::url('root/app'), 'BootbusinessThemeBundle');
        
        $expectedTemplates = array(
            array(
                "name" => "empty.html.twig",
                "slots" => array(
                    "page_title",
                    "content",
                    'navbar',
                    'footer_title_1',
                    'footer_body_1',
                    'footer_title_2',
                    'footer_body_2',
                    'footer_title_3',
                    'footer_body_3',
                    'footer_title_4',
                    'footer_body_4',
                    'footer_title_5',
                    'footer_body_5',
                    'footer_title_6',
                    'footer_social_1',
                    'footer_social_2',
                    'footer_social_3',
                    'contacts_title_1',
                    'contacts_body_1',
                    'coopyright',
                    'redkitecms_love',
                ),
            ),
            array(
                "name" => "product.html.twig",
                "slots" => array(
                    "product_title",
                    "product_image",
                    "product_description",
                    "product_features",
                    "product_buy",
                    "product_contact",
                    "product_faq",
                    'navbar',
                    'footer_title_1',
                    'footer_body_1',
                    'footer_title_2',
                    'footer_body_2',
                    'footer_title_3',
                    'footer_body_3',
                    'footer_title_4',
                    'footer_body_4',
                    'footer_title_5',
                    'footer_body_5',
                    'footer_title_6',
                    'footer_social_1',
                    'footer_social_2',
                    'footer_social_3',
                    'contacts_title_1',
                    'contacts_body_1',
                    'coopyright',
                    'redkitecms_love',
                ),
            ),
            array(
                "name" => "contacts.html.twig",
                "slots" => array(
                    "contacts_title",
                    "contacts_message_title",
                    "contacts_form",
                    "offices_section_title",
                    "offices",
                    'navbar',
                    'footer_title_1',
                    'footer_body_1',
                    'footer_title_2',
                    'footer_body_2',
                    'footer_title_3',
                    'footer_body_3',
                    'footer_title_4',
                    'footer_body_4',
                    'footer_title_5',
                    'footer_body_5',
                    'footer_title_6',
                    'footer_social_1',
                    'footer_social_2',
                    'footer_social_3',
                    'contacts_title_1',
                    'contacts_body_1',
                    'coopyright',
                    'redkitecms_love',
                ),
            ),
            array(
                "name" => "two_columns.html.twig",
                "slots" => array(
                    "left_column",
                    "right_column",
                    'page_title',
                    'content',
                    'navbar',
                    'footer_title_1',
                    'footer_body_1',
                    'footer_title_2',
                    'footer_body_2',
                    'footer_title_3',
                    'footer_body_3',
                    'footer_title_4',
                    'footer_body_4',
                    'footer_title_5',
                    'footer_body_5',
                    'footer_title_6',
                    'footer_social_1',
                    'footer_social_2',
                    'footer_social_3',
                    'contacts_title_1',
                    'contacts_body_1',
                    'coopyright',
                    'redkitecms_love',
                ),
            ),
            array(
                "name" => "all_products.html.twig",
                "slots" => array(
                    "products_title",
                    "products",
                    "products_pagination",
                    'navbar',
                    'footer_title_1',
                    'footer_body_1',
                    'footer_title_2',
                    'footer_body_2',
                    'footer_title_3',
                    'footer_body_3',
                    'footer_title_4',
                    'footer_body_4',
                    'footer_title_5',
                    'footer_body_5',
                    'footer_title_6',
                    'footer_social_1',
                    'footer_social_2',
                    'footer_social_3',
                    'contacts_title_1',
                    'contacts_body_1',
                    'coopyright',
                    'redkitecms_love',
                ),
            ),
            array(
                "name" => "home.html.twig",
                "slots" => array(
                    "slider_box",
                    "content_title_1",
                    "content_body_1",
                    "content_title_2",
                    "content_body_2",
                    'navbar',
                    'footer_title_1',
                    'footer_body_1',
                    'footer_title_2',
                    'footer_body_2',
                    'footer_title_3',
                    'footer_body_3',
                    'footer_title_4',
                    'footer_body_4',
                    'footer_title_5',
                    'footer_body_5',
                    'footer_title_6',
                    'footer_social_1',
                    'footer_social_2',
                    'footer_social_3',
                    'contacts_title_1',
                    'contacts_body_1',
                    'coopyright',
                    'redkitecms_love',
                ),
            ),
        );
        
        $this->assertEquals($expectedTemplates, $information["templates"]);
        $this->assertCount(43, $information["slots"]);
    }
    
    public function testOverrideTemplate()
    {
        $this->markTestIncomplete('This test must be updated');
        
        $this->importDefaultTheme(true);
        $information = $this->parser->parse(vfsStream::url('root/Theme'), vfsStream::url('root/app'), 'MyThemeBundle');

        $template = $information['templates'];print_R($template);
        $this->assertCount(25, $template['slots']);
    }

    protected function importDefaultTheme($overrideTemplate = false)
    {
        $baseThemeDir = __DIR__ . '/../../../../../vendor/redkite-labs/bootbusiness-theme-bundle/RedKiteCms/Theme/BootbusinessThemeBundle/Resources/views';
        if ( ! is_dir($baseThemeDir)) { 
            $baseThemeDir = __DIR__ . '/../../../../../../../../../redkite-labs/bootbusiness-theme-bundle/RedKiteCms/Theme/BootbusinessThemeBundle/Resources/views';
            if ( ! is_dir($baseThemeDir)) {
                $this->markTestSkipped(
                    'BootbusinessThemeBundle is not available.'
                );
            }
        }
        
        vfsStream::copyFromFileSystem($baseThemeDir . '/Theme', $this->root->getChild('Theme'));
        vfsStream::copyFromFileSystem($baseThemeDir . '/Slots', $this->root->getChild('Slots'));
        
        if ($overrideTemplate) {
            $overridingTemplate = vfsStream::url('root/app/Resources/views/MyThemeBundle/home.html.twig');

            copy(vfsStream::url('root/Theme/home.html.twig'), $overridingTemplate);
            $contents = file_get_contents($overridingTemplate);
            /*$contents .= '{% block left_sidebar %}
                    {# BEGIN-SLOT
                        name: left_sidebar
                        htmlContent: |
                            <h1>Title 1</h1>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisici elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquid ex ea commodi consequat.</p>
                    END-SLOT #}
                    {{ renderSlot(\'left_sidebar\') }}
                {% endblock %}';*/
            $contents .= '{% block left_sidebar %}' . PHP_EOL .
                '  {{ block(\'left_sidebar\') }}' . PHP_EOL .
                '{% endblock %}';
            file_put_contents($overridingTemplate, $contents);
        }
    }
    
    private function checkSlots($information, $slots)
    {
        $this->assertTrue(array_key_exists('slots', $information));
        $this->assertCount($slots, $information['slots']);
    }
}