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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Generator\TemplateParser;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Generator\Base\AlGeneratorBase;
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
                '   blockDefinition:' . PHP_EOL .
                '     data_src: holder.js/1900x350' . PHP_EOL .
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
        
        
        $templatesInformation = $this->arrangeTemplates($information["templates"]);
        
        $expectedTemplates = array(
            "all_products.html.twig",
            "contacts.html.twig",
            "empty.html.twig",
            "home.html.twig",
            "product.html.twig",
            "two_columns.html.twig",
        );
        $templates = array_keys($templatesInformation);
        sort($templates);
        $this->assertEquals($expectedTemplates, $templates);
        
        $repeatedSlots = array(
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
        );
        
        $pageSlots = array(
            "page_title",
            "content",
        );
        $this->assertEquals(array_merge($pageSlots, $repeatedSlots), $templatesInformation["empty.html.twig"]);
        
        $pageSlots = array(
            "slider_box",
            "content_title_1",
            "content_body_1",
            "content_title_2",
            "content_body_2",
        );
        $this->assertEquals(array_merge($pageSlots, $repeatedSlots), $templatesInformation["home.html.twig"]);
        
        $pageSlots = array(
            "products_title",
            "products",
            "products_pagination",
        );
        $this->assertEquals(array_merge($pageSlots, $repeatedSlots), $templatesInformation["all_products.html.twig"]);
        
        $pageSlots = array(
            "left_column",
            "right_column",
            'page_title',
            'content',
        );
        $this->assertEquals(array_merge($pageSlots, $repeatedSlots), $templatesInformation["two_columns.html.twig"]);
        
        $pageSlots = array(
            "contacts_title",
            "contacts_message_title",
            "contacts_form",
            "offices_section_title",
            "offices",
        );        
        $this->assertEquals(array_merge($pageSlots, $repeatedSlots), $templatesInformation["contacts.html.twig"]);
        
        $pageSlots = array(
            "product_title",
                    "product_image",
                    "product_description",
                    "product_features",
                    "product_buy",
                    "product_contact",
                    "product_faq",
        );
        $this->assertEquals(array_merge($pageSlots, $repeatedSlots), $templatesInformation["product.html.twig"]);
        $this->assertCount(43, $information["slots"]);
    }
    
    private function arrangeTemplates($templatesInformation)
    {
        $templates = array();
        foreach($templatesInformation as $templateInfo) {
            $templates[$templateInfo["name"]] = $templateInfo["slots"];
        }
        
        return $templates;
    }

    protected function importDefaultTheme($overrideTemplate = false)
    {
        $baseThemeDir = __DIR__ . '/../../../../../../../../../src/RedKiteCms/Theme/BootbusinessThemeBundle/Resources/views';
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
    }
    
    private function checkSlots($information, $slots)
    {
        $this->assertTrue(array_key_exists('slots', $information));
        $this->assertCount($slots, $information['slots']);
    }
}