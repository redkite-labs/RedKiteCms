<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Deploy\TemplateSection;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\TemplateSection\TemplateSectionTwig;

class TemplateSectionTwigTester extends TemplateSectionTwig
{
    protected $imagesSourcePath = "uploads/assets/media";
    protected $imagesTargetPath = "bundles/acmewebsite/media";
                
    public function doWriteComment($comment)
    {
        return $this->writeComment($comment);
    }
    
    public function doWriteBlock($blockName, $blockContent, $parent)
    {
        return $this->writeBlock($blockName, $blockContent, $parent);
    }
    
    public function doWriteInlineBlock($blockName, $blockContent, $parent)
    {
        return $this->writeInlineBlock($blockName, $blockContent, $parent);
    }
    
    public function doWriteAssetic($sectionName, $assetsSection, $sectionContent, $filter, $output)
    {
        return $this->writeAssetic($sectionName, $assetsSection, $sectionContent, $filter, $output);
    }
    
    public function doWriteContent($slotName, $content)
    {
        return $this->writeContent($slotName, $content);
    }
    
    public function doIdentateContent($content)
    {
        return $this->identateContent($content);
    }
    
    public function doRewriteImagesPathForProduction($content)
    {
        return $this->rewriteImagesPathForProduction($content);
    }
    
    public function doRewriteLinksForProduction($content)
    {
        return $this->rewriteLinksForProduction($content);
    }
}

/**
 * TemplateSectionTwigTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class TemplateSectionTwigTest extends TestCase
{
    protected function setUp()
    {
        $this->urlManager = $this->getMock("RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\UrlManager\AlUrlManagerInterface");
        
        $this->templateSectionTwig = new TemplateSectionTwigTester($this->urlManager);
    }

    public function testWriteComment()
    {     
        $this->assertEquals(PHP_EOL . "{#--------------  JUST A COMMENT  --------------#}" . PHP_EOL, $this->templateSectionTwig->doWriteComment('just a comment'));
    }

    /**
     * @dataProvider writeBlocksProvider
     */
    public function testWriteBlock($blockName, $blockContent, $parent, $expectedResult)
    {     
        $this->assertEquals($expectedResult, $this->templateSectionTwig->doWriteBlock($blockName, $blockContent, $parent));
    }
    
    /**
     * @dataProvider writeInlineBlocksProvider
     */
    public function testWriteInlineBlock($blockName, $blockContent, $parent, $expectedResult)
    {     
        $this->assertEquals($expectedResult, $this->templateSectionTwig->doWriteInlineBlock($blockName, $blockContent, $parent));
    }
    
    /**
     * @dataProvider writeAsseticProvider
     */
    public function testWriteAssetic($sectionName, $assetsSection, $sectionContent, $filter, $output, $expectedResult)
    {     
        $this->assertEquals($expectedResult, $this->templateSectionTwig->doWriteAssetic($sectionName, $assetsSection, $sectionContent, $filter, $output));
    }
    
    public function testWriteContent()
    {     
        $expectedResult = '    <!-- BEGIN LOGO BLOCK -->' . PHP_EOL;
        $expectedResult .= '    foo bar' . PHP_EOL;
        $expectedResult .= '    <!-- END LOGO BLOCK -->';

        $this->assertEquals($expectedResult, $this->templateSectionTwig->doWriteContent('logo', "foo bar"));
    }
    
    public function testIdentateContent()
    {     
        $content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit." . PHP_EOL;
        $content .= "      Praesent vitae ultricies augue. Donec convallis molestie enim dignissim interdum." . PHP_EOL;
        $content .= "  Donec non semper neque. Sed porta turpis vel hendrerit pretium.";
        $expectedResult  = "    Lorem ipsum dolor sit amet, consectetur adipiscing elit." . PHP_EOL;
        $expectedResult .= "    Praesent vitae ultricies augue. Donec convallis molestie enim dignissim interdum." . PHP_EOL;
        $expectedResult .= "    Donec non semper neque. Sed porta turpis vel hendrerit pretium.";

        $this->assertEquals($expectedResult, $this->templateSectionTwig->doIdentateContent($content));
    }
    
    public function testRewriteImagesPathForProduction()
    {     
        $content = 'Lorem ipsum dolor sit amet.<img alt="an image" src="uploads/assets/media/image.png" title="An image" />, consectetur adipiscing elit.';
        $expectedResult  = 'Lorem ipsum dolor sit amet.<img alt="an image" src="bundles/acmewebsite/media/image.png" title="An image" />, consectetur adipiscing elit.';

        $this->assertEquals($expectedResult, $this->templateSectionTwig->doRewriteImagesPathForProduction($content));
    }
    
    /**
     * @dataProvider linksProvider
     */
    public function testLinksForProduction($content, $link, $convertedLink, $expectedResult)
    { 
        if (null !== $link) {
            $this->urlManager->expects($this->once())
                ->method('fromUrl')
                ->with($link)
                ->will($this->returnSelf())
            ;

            $this->urlManager->expects($this->once())
                ->method('getProductionRoute')
                ->will($this->returnValue($convertedLink))
            ;
        }
        
        $this->assertEquals($expectedResult, $this->templateSectionTwig->doRewriteLinksForProduction($content));
    }
    
    public function linksProvider()
    {
        return array(
            array(
                'Lorem ipsum dolor sit amet.<a href="an-awesome-website-page" />, consectetur adipiscing elit.',
                'an-awesome-website-page',
                'an-awesome-website-page',
                'Lorem ipsum dolor sit amet.<a href="{{ path(\'an-awesome-website-page\') }}" />, consectetur adipiscing elit.',
            ),
            array(
                'Lorem ipsum dolor sit amet.<a href="http://example.com" />, consectetur adipiscing elit.',
                'http://example.com',
                null,
                'Lorem ipsum dolor sit amet.<a href="http://example.com" />, consectetur adipiscing elit.',
            ),
            array(
                'Lorem ipsum dolor sit amet.<a href="route:\'_symfony2-handmade-route\'" />, consectetur adipiscing elit.',
                null,
                null,
                'Lorem ipsum dolor sit amet.<a href="{{ path(\'_symfony2-handmade-route\') }}" />, consectetur adipiscing elit.',
            ),
            array(
                'Lorem ipsum dolor sit amet.<a href ="an-awesome-website-page" />, consectetur adipiscing elit.',
                'an-awesome-website-page',
                'an-awesome-website-page',
                'Lorem ipsum dolor sit amet.<a href ="{{ path(\'an-awesome-website-page\') }}" />, consectetur adipiscing elit.',
            ),
            array(
                'Lorem ipsum dolor sit amet.<a href = "an-awesome-website-page" />, consectetur adipiscing elit.',
                'an-awesome-website-page',
                'an-awesome-website-page',
                'Lorem ipsum dolor sit amet.<a href = "{{ path(\'an-awesome-website-page\') }}" />, consectetur adipiscing elit.',
            ),
        );
    }
    
    public function writeAsseticProvider()
    {
        return array(
            array(
                'stylesheets',
                'file1.css file2.css',
                '<link href="{{ asset_url }}" rel="stylesheet" type="text/css" media="all" />',
                'cssrewrite',
                null,
                '  {% stylesheets file1.css file2.css filter="cssrewrite" %}' . PHP_EOL .
                '    <link href="{{ asset_url }}" rel="stylesheet" type="text/css" media="all" />' . PHP_EOL .
                '  {% endstylesheets %}'
            ),
            array(
                'stylesheets',
                'file1.css file2.css',
                '<link href="{{ asset_url }}" rel="stylesheet" type="text/css" media="all" />',
                'cssrewrite',
                'style.css',
                '  {% stylesheets file1.css file2.css filter="cssrewrite" output="style.css" %}' . PHP_EOL .
                '    <link href="{{ asset_url }}" rel="stylesheet" type="text/css" media="all" />' . PHP_EOL .
                '  {% endstylesheets %}'
            ),
        );
    }
    
    public function writeBlocksProvider()
    {
        return array(
            array(
                'logo',
                "",
                false,
                "",
            ),
            array(
                'logo',
                "",
                true,
                "",
            ),
            array(
                'logo',
                "foo/bar",
                false,
                '{% block logo %}' . PHP_EOL .
                'foo/bar' . PHP_EOL .
                '{% endblock %}' . PHP_EOL . PHP_EOL,
            ),
            array(
                'logo',
                "foo/bar",
                true,
                '{% block logo %}' . PHP_EOL .
                '{{ parent() }}' . PHP_EOL .
                'foo/bar' . PHP_EOL .
                '{% endblock %}' . PHP_EOL . PHP_EOL,
            ),
        );
    }
    
    public function writeInlineBlocksProvider()
    {
        return array(
            array(
                'logo',
                "",
                false,
                "",
            ),
            array(
                'logo',
                "",
                true,
                "",
            ),
            array(
                'logo',
                "foo/bar",
                false,
                '{% block logo %} foo/bar {% endblock %}' . PHP_EOL,
            ),
            array(
                'logo',
                "foo/bar",
                true,
                '{% block logo %} {{ parent() }} foo/bar {% endblock %}' . PHP_EOL,
            ),
        );
    }
}