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
use RedKiteLabs\RedKiteCmsBundle\Core\Generator\TemplateParser\AlTemplateParser;
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
            'app' => array(
                'Resources' => array(
                    'views' => array(
                        'MyThemeBundle' => array(
                        ),
                    ),
                ),
            ),
        ));
        $this->parser = new AlTemplateParser(vfsStream::url('root/Theme'), vfsStream::url('root/app'), 'MyThemeBundle');
    }
    
    public function testTemplatesWhichContainsAnySlotAreSkipped()
    {
        $contents = '<div id="logo">' . PHP_EOL;
        $contents .= '{% block logo %}' . PHP_EOL;
        $contents .= '{% endblock %}' . PHP_EOL;
        $contents .= '</div>';
        file_put_contents(vfsStream::url('root/Theme/home.html.twig'), $contents);
        $information = $this->parser->parse();
        $this->assertCount(0, $information);
    }

    /**
     * @expectedException \Symfony\Component\Yaml\Exception\ParseException
     */
    public function testAnExceptionIsThrownWhenInformationIsYmlMalformed()
    {
        $contents = '<div id="logo">' . PHP_EOL;
        $contents .= '{% block logo %}' . PHP_EOL;
        $contents .= '{# BEGIN-SLOT' . PHP_EOL;
        $contents .= '   name: logo' . PHP_EOL;
        $contents .= '     repeated: site' . PHP_EOL; // Malformed here
        $contents .= '   htmlContent: |' . PHP_EOL;
        $contents .= '       <img src="/uploads/assets/media/business-website-original-logo.png" title="Progress website logo" alt="Progress website logo" />' . PHP_EOL;
        $contents .= 'END-SLOT #}' . PHP_EOL;
        $contents .= '{{ renderSlot(\'logo\') }}' . PHP_EOL;
        $contents .= '{% endblock %}' . PHP_EOL;
        $contents .= '</div>';
        file_put_contents(vfsStream::url('root/Theme/home.twig.html'), $contents);
        $this->parser->parse();
    }

    public function testAnErrorKeyIsAddedWhenAnUnrecognizedAttributeIsDeclared()
    {
        $contents = '<div id="logo">' . PHP_EOL;
        $contents .= '{% block logo %}' . PHP_EOL;
        $contents .= '{# BEGIN-SLOT' . PHP_EOL;
        $contents .= '   name: logo' . PHP_EOL;
        $contents .= '   repeated: site' . PHP_EOL;
        $contents .= '   fake: script' . PHP_EOL;
        $contents .= '   htmlContent: |' . PHP_EOL;
        $contents .= '       <img src="/uploads/assets/media/business-website-original-logo.png" title="Progress website logo" alt="Progress website logo" />' . PHP_EOL;
        $contents .= 'END-SLOT #}' . PHP_EOL;
        $contents .= '{{ renderSlot(\'logo\') }}' . PHP_EOL;
        $contents .= '{% endblock %}' . PHP_EOL;
        $contents .= '</div>';
        file_put_contents(vfsStream::url('root/Theme/home.html.twig'), $contents);
        $information = $this->parser->parse();
        $slot = $information['home.html.twig']['slots']['logo'];
        $this->assertTrue(array_key_exists('generate_template', $information['home.html.twig']));
        $this->assertTrue(array_key_exists('repeated', $slot));
        $this->assertTrue(array_key_exists('htmlContent', $slot));
        $this->assertFalse(array_key_exists('fake', $slot));
        $this->assertTrue(array_key_exists('errors', $slot));
        $this->assertTrue(array_key_exists('fake', $slot['errors']));
    }
    
    public function testTheSlotIsSkippedWhenNameOptionDoesNotExist()
    {
        $contents = '<div id="logo">' . PHP_EOL;
        $contents .= '{% block logo %}' . PHP_EOL;
        $contents .= '{# BEGIN-SLOT' . PHP_EOL;
        $contents .= '   repeated: site' . PHP_EOL;
        $contents .= '   htmlContent: |' . PHP_EOL;
        $contents .= '       <img src="/uploads/assets/media/business-website-original-logo.png" title="Progress website logo" alt="Progress website logo" />' . PHP_EOL;
        $contents .= 'END-SLOT #}' . PHP_EOL;
        $contents .= '{{ renderSlot(\'logo\') }}' . PHP_EOL;
        $contents .= '{% endblock %}' . PHP_EOL;
        $contents .= '</div>';
        file_put_contents(vfsStream::url('root/Theme/home.html.twig'), $contents);
        $information = $this->parser->parse();
        $this->assertCount(0, $information);
    }

    public function testRealTheme()
    {
        $this->importDefaultTheme();
        $this->parser = new AlTemplateParser(vfsStream::url('root/Theme'), vfsStream::url('root/app'), 'BootbusinessThemeBundle');
        $information = $this->parser->parse();
        $this->assertTrue(array_key_exists('base.html.twig', $information));
        $this->assertTrue(array_key_exists('all_products.html.twig', $information));
        $this->assertTrue(array_key_exists('contacts.html.twig', $information));
        $this->assertTrue(array_key_exists('empty.html.twig', $information));
        $this->assertTrue(array_key_exists('home.html.twig', $information));
        $this->assertTrue(array_key_exists('product.html.twig', $information));
        $this->assertTrue(array_key_exists('two_columns.html.twig', $information));
        
        $this->checkTemplateInfo(
            $information['base.html.twig'], 
            false, 
            array(
                'assets' => 4,
                'external_stylesheets' => 0,
                'external_javascripts' => 0,
                'external_stylesheets_cms' => 0,
                'external_javascripts_cms' => 0,
            ), 
            19
        );
        
        $this->checkTemplateInfo(
            $information['home.html.twig'], 
            true, 
            array(
                'assets' => 4,
                'external_stylesheets' => 0,
                'external_javascripts' => 0,
                'external_stylesheets_cms' => 0,
                'external_javascripts_cms' => 0,
            ), 
            5
        );
        
        $this->checkTemplateInfo(
            $information['all_products.html.twig'], 
            true, 
            array(
                'assets' => 4,
                'external_stylesheets' => 0,
                'external_javascripts' => 0,
                'external_stylesheets_cms' => 0,
                'external_javascripts_cms' => 0,
            ), 
            3
        );
        
        $this->checkTemplateInfo(
            $information['contacts.html.twig'], 
            true, 
            array(
                'assets' => 4,
                'external_stylesheets' => 0,
                'external_javascripts' => 0,
                'external_stylesheets_cms' => 0,
                'external_javascripts_cms' => 0,
            ), 
            5
        );
        
        $this->checkTemplateInfo(
            $information['empty.html.twig'], 
            true, 
            array(
                'assets' => 4,
                'external_stylesheets' => 0,
                'external_javascripts' => 0,
                'external_stylesheets_cms' => 0,
                'external_javascripts_cms' => 0,
            ), 
            2
        );
        
        $this->checkTemplateInfo(
            $information['product.html.twig'], 
            true, 
            array(
                'assets' => 4,
                'external_stylesheets' => 0,
                'external_javascripts' => 0,
                'external_stylesheets_cms' => 0,
                'external_javascripts_cms' => 0,
            ), 
            7
        );
        
        $this->checkTemplateInfo(
            $information['two_columns.html.twig'], 
            true, 
            array(
                'assets' => 4,
                'external_stylesheets' => 0,
                'external_javascripts' => 0,
                'external_stylesheets_cms' => 0,
                'external_javascripts_cms' => 0,
            ), 
            2
        );
    }
    
    private function checkTemplateInfo($template, $generated, $assets, $slots)
    {
        $this->assertTrue(array_key_exists('assets', $template));
        $this->assertTrue(array_key_exists('generate_template', $template));
        $this->assertEquals($generated, $template['generate_template']);
        $templateAssets = $template['assets'];
        $this->assertCount($assets['assets'], $templateAssets);
        $this->assertCount($assets['external_stylesheets'], $templateAssets['external_stylesheets']);
        $this->assertCount($assets['external_javascripts'], $templateAssets['external_javascripts']);
        $this->assertCount($assets['external_stylesheets_cms'], $templateAssets['external_stylesheets_cms']);
        $this->assertCount($assets['external_javascripts_cms'], $templateAssets['external_javascripts_cms']);
        $this->assertTrue(array_key_exists('slots', $template));
        $this->assertCount($slots, $template['slots']);
    }
    
    public function testOverrideTemplate()
    {
        $this->importDefaultTheme(true);
        $information = $this->parser->parse();

        $template = $information['home.html.twig'];
        $this->assertCount(6, $template['slots']);
    }

    protected function importDefaultTheme($overrideTemplate = false)
    {
        $baseThemeDir = __DIR__ . '/../../../../../../../../bootbusiness-theme-bundle/RedKiteCms/Theme/BootbusinessThemeBundle/Resources/views/Theme';
        if ( ! is_dir($baseThemeDir)) { 
            $baseThemeDir = __DIR__ . '/../../../../../../../../../redkite-labs/bootbusiness-theme-bundle/RedKiteCms/Theme/BootbusinessThemeBundle/Resources/views/Theme';
            if ( ! is_dir($baseThemeDir)) {
                $this->markTestSkipped(
                    'BootbusinessThemeBundle is not available.'
                );
            }
        }
        
        vfsStream::copyFromFileSystem($baseThemeDir, $this->root->getChild('Theme'));
        
        if ($overrideTemplate) {
            $overridingTemplate = vfsStream::url('root/app/Resources/views/MyThemeBundle/home.html.twig');

            copy(vfsStream::url('root/Theme/home.html.twig'), $overridingTemplate);
            $contents = file_get_contents($overridingTemplate);
            $contents .= '{% block left_sidebar %}
                    {# BEGIN-SLOT
                        name: left_sidebar
                        htmlContent: |
                            <h1>Title 1</h1>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisici elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquid ex ea commodi consequat.</p>
                    END-SLOT #}
                    {{ renderSlot(\'left_sidebar\') }}
                {% endblock %}';
            file_put_contents($overridingTemplate, $contents);
        }
    }
}