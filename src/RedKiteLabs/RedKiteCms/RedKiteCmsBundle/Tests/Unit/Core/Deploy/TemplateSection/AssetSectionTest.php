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
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\TemplateSection\AssetSection;


/**
 * TemplateSectionTwigTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AssetSectionTest extends TestCase
{
    /**
     * @dataProvider assetsProvider
     */
    public function testAssets($expectedResult, $externalStylesheets, $internalStylesheets = "", $externalJavascripts = "", $internalJavascripts = "")
    {
        $urlManager = $this->getMock("RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\UrlManager\UrlManagerInterface");
        $theme = $this->getMockBuilder("RedKiteLabs\ThemeEngineBundle\Core\Theme\Theme")
                        ->disableOriginalConstructor()
                        ->getMock();
        $themeSlots = $this->getMock("RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\ThemeSlotsInterface");
        
        $theme->expects($this->once())
            ->method('getThemeSlots')
            ->will($this->returnValue($themeSlots))
        ;
        
        $pageTree = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree')
                                ->disableOriginalConstructor()
                                ->setMethods(array('getExternalStylesheets', 'getInternalStylesheets', 'getExternalJavascripts', 'getInternalJavascripts'))
                                ->getMock();
        
        $pageTree->expects($this->once())
            ->method('getExternalStylesheets')
            ->will($this->returnValue($externalStylesheets))
        ;
        
        $pageTree->expects($this->once())
            ->method('getInternalStylesheets')
            ->will($this->returnValue($internalStylesheets))
        ;
        
        $pageTree->expects($this->once())
            ->method('getExternalJavascripts')
            ->will($this->returnValue($externalJavascripts))
        ;
        
        $pageTree->expects($this->once())
            ->method('getInternalJavascripts')
            ->will($this->returnValue($internalJavascripts))
        ;
        
        $metatagsSection = new AssetSection($urlManager);
        $options = array(
            "uploadAssetsFullPath" => "",
            "uploadAssetsAbsolutePath" => ""
        );
        
        $this->assertEquals($expectedResult, $metatagsSection->generateSection($pageTree, $theme, $options));
    }
    
    public function assetsProvider()
    {
        return array(
            array(
                 PHP_EOL . '{#--------------  ASSETS SECTION  --------------#}' . PHP_EOL,
                array(),
            ),
            array(
                 PHP_EOL . '{#--------------  ASSETS SECTION  --------------#}' . PHP_EOL .
                '{% block external_stylesheets %}' . PHP_EOL .
                '{{ parent() }}' . PHP_EOL .
                '  {% stylesheets "asset.css" filter="cssrewrite" %}' . PHP_EOL .
                '    <link href="{{ asset_url }}" rel="stylesheet" type="text/css" media="all" />' . PHP_EOL .
                '  {% endstylesheets %}' . PHP_EOL .
                '{% endblock %}' . PHP_EOL . PHP_EOL,
                array('asset.css'),
            ),
            array(
                 PHP_EOL . '{#--------------  ASSETS SECTION  --------------#}' . PHP_EOL .
                '{% block external_stylesheets %}' . PHP_EOL .
                '{{ parent() }}' . PHP_EOL .
                '  {% stylesheets "asset.css" filter="cssrewrite" %}' . PHP_EOL .
                '    <link href="{{ asset_url }}" rel="stylesheet" type="text/css" media="all" />' . PHP_EOL .
                '  {% endstylesheets %}' . PHP_EOL .
                '{% endblock %}' . PHP_EOL . PHP_EOL .                
                '{% block external_javascripts %}' . PHP_EOL .
                '{{ parent() }}' . PHP_EOL .
                '  {% javascripts "stylesheets.js" %}' . PHP_EOL .
                '    <script src="{{ asset_url }}"></script>' . PHP_EOL .
                '  {% endjavascripts %}' . PHP_EOL .
                '{% endblock %}' . PHP_EOL . PHP_EOL .
                '{% block internal_header_stylesheets %}' . PHP_EOL .
                '{{ parent() }}' . PHP_EOL .
                '<style>.foo{bar}</style>' . PHP_EOL .
                '{% endblock %}' . PHP_EOL . PHP_EOL .
                '{% block internal_header_javascripts %}' . PHP_EOL .
                '{{ parent() }}' . PHP_EOL .
                '<script>$(document).ready(function () {foo(bar){}});</script>' . PHP_EOL .
                '{% endblock %}' . PHP_EOL . PHP_EOL,
                array('asset.css'),
                '.foo{bar}',
                array('stylesheets.js'),                
                'foo(bar){}',
            ),
        );
    }
}