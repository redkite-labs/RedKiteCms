<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\TemplateSection\MetatagSection;


/**
 * TemplateSectionTwigTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class MetatagsSectionTest extends TestCase
{
    public function testMetatags()
    {
        $urlManager = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\UrlManager\UrlManagerInterface');
        $theme = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Theme\Theme')
                        ->disableOriginalConstructor()
                        ->getMock();
        $themeSlots = $this->getMock('RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\ThemeSlotsInterface');
        
        $theme->expects($this->once())
            ->method('getThemeSlots')
            ->will($this->returnValue($themeSlots))
        ;
        
        $pageTree = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree')
                                ->disableOriginalConstructor()
                                ->setMethods(array('getMetaTitle', 'getMetaDescription', 'getMetaKeywords'))
                                ->getMock();
        
        $pageTree->expects($this->once())
            ->method('getMetaTitle')
            ->will($this->returnValue('Website title'))
        ;
        
        $pageTree->expects($this->once())
            ->method('getMetaDescription')
            ->will($this->returnValue('Website description'))
        ;
        
        $pageTree->expects($this->once())
            ->method('getMetaKeywords')
            ->will($this->returnValue('website,keywords'))
        ;
        
        $metatagsSection = new MetatagSection($urlManager);
        $options = array(
            "uploadAssetsFullPath" => "",
            "uploadAssetsAbsolutePath" => "",
            "deployBundleAssetsPath" => "",
        );
        
        $expectedResult = PHP_EOL . "{#--------------  METATAGS SECTION  --------------#}" . PHP_EOL;
        $expectedResult .= "{% block title %} Website title {% endblock %}" . PHP_EOL;
        $expectedResult .= "{% block description %} Website description {% endblock %}" . PHP_EOL;
        $expectedResult .= "{% block keywords %} website,keywords {% endblock %}" . PHP_EOL;

        
        $this->assertEquals($expectedResult, $metatagsSection->generateSection($pageTree, $theme, $options));
    }
}