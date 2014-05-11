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
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\TemplateSection\ContentSection;


/**
 * TemplateSectionTwigTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class ContentSectionTest extends TestCase
{
    /**
     * @dataProvider contentsProvider
     */
    public function testGenerateContents($filter, $slots, $blocks, $credits, $expectedResult)
    {
        $urlManager = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\UrlManager\UrlManagerInterface');
        $viewRenderer = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ViewRenderer\ViewRendererInterface');
        
        $theme = $this->initTheme($slots, $filter);
        $pageBlocks = $this->initPageBlocks($blocks);
        $pageTree = $this->initPageTree($pageBlocks);
        $blocksManagerFactory = $this->initBlocksFactory($pageTree, $blocks, $viewRenderer);
        
        $contentSection = new ContentSection($urlManager, $viewRenderer, $blocksManagerFactory);
        $options = array(
            "uploadAssetsFullPath" => "",
            "uploadAssetsAbsolutePath" => "",
            "deployBundleAssetsPath" => "",
            "filter" => $filter,
            "credits" => $credits,
        );
        
        $this->assertEquals($expectedResult, $contentSection->generateSection($pageTree, $theme, $options));
    }
    
    public function contentsProvider()
    {
        return array(
            array(
                array(
                    "page",
                ),
                array(
                    "logo" => array(
                        "slot" => $this->createSlot("page"),
                        "filter" => "page",
                    ),
                ),
                array(
                    "logo" => array(
                        $this->createBlock("logo", "foo"),
                    ),
                ),
                "yes",
                PHP_EOL . '{#--------------  CONTENTS SECTION  --------------#}' . PHP_EOL .
                '{% block logo %}' . PHP_EOL .
                '    <!-- BEGIN LOGO BLOCK -->' . PHP_EOL .
                '    foo' . PHP_EOL .
                '    <!-- END LOGO BLOCK -->' . PHP_EOL .
                '{% endblock %}' . PHP_EOL .
                '' . PHP_EOL .
                '' . PHP_EOL .
                '{#--------------  METATAGS EXTRA SECTION  --------------#}' . PHP_EOL,
            ),
            array(
                array(
                    "page",
                ),
                array(
                    "logo" => array(
                        "slot" => null,
                        "filter" => "page",
                    ),
                ),
                array(
                    "logo" => array(
                        $this->createBlock("logo", "foo"),
                    ),
                ),
                "yes",
                PHP_EOL . '{#--------------  CONTENTS SECTION  --------------#}' . PHP_EOL .
                '{% block logo %}' . PHP_EOL .
                '    <!-- BEGIN LOGO BLOCK -->' . PHP_EOL .
                '    foo' . PHP_EOL .
                '    <!-- END LOGO BLOCK -->' . PHP_EOL .
                '{% endblock %}' . PHP_EOL .
                '' . PHP_EOL .
                '' . PHP_EOL .
                '{#--------------  METATAGS EXTRA SECTION  --------------#}' . PHP_EOL,
            ),
            array(
                array(
                    "page",
                ),
                array(
                    "logo" => array(
                        "slot" => $this->createSlot("page"),
                        "filter" => "page",
                    ),
                ),
                array(
                    "logo" => array(
                        $this->createBlock("logo", "foo", "extra metatags"),
                    ),
                ),
                "yes",
                PHP_EOL . '{#--------------  CONTENTS SECTION  --------------#}' . PHP_EOL .
                '{% block logo %}' . PHP_EOL .
                '    <!-- BEGIN LOGO BLOCK -->' . PHP_EOL .
                '    foo' . PHP_EOL .
                '    <!-- END LOGO BLOCK -->' . PHP_EOL .
                '{% endblock %}' . PHP_EOL .
                '' . PHP_EOL .
                '' . PHP_EOL .
                '{#--------------  METATAGS EXTRA SECTION  --------------#}' . PHP_EOL . 
                '{% block metatags %}' . PHP_EOL . 
                '{{ parent() }}' . PHP_EOL . 
                'extra metatags' . PHP_EOL . 
                '{% endblock %}' . PHP_EOL . PHP_EOL,
            ),
            array(
                array(
                    "page",
                ),
                array(
                    "logo" => array(
                        "slot" => $this->createSlot("page"),
                        "filter" => "page",
                    ),
                    "menu" =>  array(
                        "slot" => $this->createSlot("page"),
                        "filter" => "page",
                    ),
                ),
                array(
                    "logo" => array(
                        $this->createBlock("logo", "foo"),
                    ),
                    "menu" => array(
                        $this->createBlock("menu", "bar"),
                    ),
                ),
                "yes",
                PHP_EOL . '{#--------------  CONTENTS SECTION  --------------#}' . PHP_EOL .
                '{% block logo %}' . PHP_EOL .
                '    <!-- BEGIN LOGO BLOCK -->' . PHP_EOL .
                '    foo' . PHP_EOL .
                '    <!-- END LOGO BLOCK -->' . PHP_EOL .
                '{% endblock %}' . PHP_EOL . PHP_EOL .
                '{% block menu %}' . PHP_EOL .
                '    <!-- BEGIN MENU BLOCK -->' . PHP_EOL .
                '    bar' . PHP_EOL .
                '    <!-- END MENU BLOCK -->' . PHP_EOL .
                '{% endblock %}' . PHP_EOL .
                '' . PHP_EOL .
                '' . PHP_EOL .
                '{#--------------  METATAGS EXTRA SECTION  --------------#}' . PHP_EOL,
            ),
            array(
                array(
                    "page",
                ),
                array(
                    "logo" => array(
                        "slot" => $this->createSlot("page"),
                        "filter" => "page",
                    ),
                    "menu" =>  array(
                        "slot" => $this->createSlot("language"),
                        "filter" => "language",
                    ),
                ),
                array(
                    "logo" => array(
                        $this->createBlock("logo", "foo"),
                    ),
                ),
                "yes",
                PHP_EOL . '{#--------------  CONTENTS SECTION  --------------#}' . PHP_EOL .
                '{% block logo %}' . PHP_EOL .
                '    <!-- BEGIN LOGO BLOCK -->' . PHP_EOL .
                '    foo' . PHP_EOL .
                '    <!-- END LOGO BLOCK -->' . PHP_EOL .
                '{% endblock %}' . PHP_EOL .
                '' . PHP_EOL .
                '' . PHP_EOL .
                '{#--------------  METATAGS EXTRA SECTION  --------------#}' . PHP_EOL,
            ),
            array(
                array(
                    "page",
                ),
                array(
                    "logo" => array(
                        "slot" => $this->createSlot("page"),
                        "filter" => "page",
                    ),
                    "menu" =>  array(
                        "slot" => $this->createSlot("language", "page"),
                        "filter" => "page",
                    ),
                ),
                array(
                    "logo" => array(
                        $this->createBlock("logo", "foo"),
                    ),
                    "menu" => array(
                        $this->createBlock("menu", "bar"),
                    ),
                ),
                "yes",
                PHP_EOL . '{#--------------  CONTENTS SECTION  --------------#}' . PHP_EOL .
                '{% block logo %}' . PHP_EOL .
                '    <!-- BEGIN LOGO BLOCK -->' . PHP_EOL .
                '    foo' . PHP_EOL .
                '    <!-- END LOGO BLOCK -->' . PHP_EOL .
                '{% endblock %}' . PHP_EOL . PHP_EOL .
                '{% block menu %}' . PHP_EOL .
                '    <!-- BEGIN MENU BLOCK -->' . PHP_EOL .
                '    bar' . PHP_EOL .
                '    <!-- END MENU BLOCK -->' . PHP_EOL .
                '{% endblock %}' . PHP_EOL .
                '' . PHP_EOL .
                '' . PHP_EOL .
                '{#--------------  METATAGS EXTRA SECTION  --------------#}' . PHP_EOL,
            ),
            array(
                array(
                    "page",
                ),
                array(
                    "logo" => array(
                        "slot" => $this->createSlot("page"),
                        "filter" => "page",
                    ),
                    "menu" =>  array(
                        "slot" => $this->createSlot("language", "page"),
                        "filter" => "page",
                    ),
                ),
                array(
                    "logo" => array(
                        $this->createBlock("logo", "foo"),
                    ),
                    "menu" => array(
                        $this->createBlock("menu", "bar"),
                    ),
                ),
                "no",
                PHP_EOL . '{#--------------  CONTENTS SECTION  --------------#}' . PHP_EOL .
                '{% block logo %}' . PHP_EOL .
                '    <!-- BEGIN LOGO BLOCK -->' . PHP_EOL .
                '    foo' . PHP_EOL .
                '    <!-- END LOGO BLOCK -->' . PHP_EOL .
                '{% endblock %}' . PHP_EOL . PHP_EOL .
                '{% block menu %}' . PHP_EOL .
                '    <!-- BEGIN MENU BLOCK -->' . PHP_EOL .
                '    bar' . PHP_EOL .
                '    <!-- END MENU BLOCK -->' . PHP_EOL .
                '{% endblock %}' . PHP_EOL .
                '' . PHP_EOL .
                '' . PHP_EOL .
                '{#--------------  METATAGS EXTRA SECTION  --------------#}' . PHP_EOL .
                '{% block internal_header_stylesheets %}' . PHP_EOL .
                '  {{ parent() }}' . PHP_EOL .
                '' . PHP_EOL .
                '  <style>.al-credits{width:100%;background-color:#fff;text-align:center;padding:6px;border-top:1px solid #000;margin-top:1px;}.al-credits a{color:#333;}.al-credits a:hover{color:#C20000;}</style>' . PHP_EOL .
                '{% endblock %}' . PHP_EOL .
                '' . PHP_EOL .
                '{% block body %}' . PHP_EOL .
                '  {{ parent() }}' . PHP_EOL .
                '' . PHP_EOL .
                '  <div class="al-credits"><a href="http://redkite-labs.com">Powered by RedKiteCms</div>' . PHP_EOL .
                '{% endblock %}' . PHP_EOL,                
            ),
        );
    }
    
    private function initTheme($slots, $filter)
    {
        $theme = $this->getMockBuilder("RedKiteLabs\ThemeEngineBundle\Core\Theme\Theme")
                        ->disableOriginalConstructor()
                        ->getMock();
        $this->themeSlots = $this->getMock("RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\ThemeSlotsInterface");
        $this->themeSlots->expects($this->once())
            ->method('getSlots')
            ->will($this->returnValue(array_keys($slots)))
        ;
        
        $at = 1;
        foreach ($slots as $slotName => $slotArguments) {
            if ( ! in_array($slotArguments["filter"], $filter)) {
                continue;
            }
            
            $this->themeSlots->expects($this->at($at))
                ->method('getSlot')
                ->with($slotName)
                ->will($this->returnValue($slotArguments["slot"]))
            ;
            
            $at++;
        }
        
        $theme->expects($this->once())
            ->method('getThemeSlots')
            ->will($this->returnValue($this->themeSlots))
        ;
        
        return $theme;
    }
    
    private function initPageBlocks($blocks)
    {
        $pageBlocks = $this->getMock("RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\PageBlocks\PageBlocksInterface");
        $pageBlocks->expects($this->once())
            ->method('getBlocks')
            ->will($this->returnValue($blocks))
        ;
        
        return $pageBlocks;
    }
    
    private function initPageTree($pageBlocks)
    {
        $pageTree = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree')
                                ->disableOriginalConstructor()
                                ->setMethods(array('getPageBlocks'))
                                ->getMock();
        
        $pageTree->expects($this->once())
            ->method('getPageBlocks')
            ->will($this->returnValue($pageBlocks))
        ;
        
        return $pageTree;
    }
    
    private function initBlocksFactory($pageTree, $blocks, $viewRenderer)
    {
        $at = 0;
        $blocksManagerFactory = $this->getMock("RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManagerFactoryInterface");
        foreach($blocks as $slotName => $slotBlocks) {
            foreach($slotBlocks as $block) {
                $blockManager = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

                $blockManager->expects($this->once())
                    ->method('setPageTree')
                    ->with($pageTree)
                ;

                $blockManager->expects($this->once())
                    ->method('setEditorDisabled')
                    ->with(true)
                ;

                $viewRenderer->expects($this->at($at))
                    ->method('render')
                    ->will($this->returnValue($block->getHtml()))
                ;

                $blockManager->expects($this->once())
                    ->method('getMetaTags')
                    ->will($this->returnValue($block->getMetaTags()))
                ;

                $blocksManagerFactory->expects($this->at($at))
                    ->method('createBlockManager')
                    ->with($block)
                    ->will($this->returnValue($blockManager))
                ;

                $at++;
            }
        }
        
        
        return $blocksManagerFactory;
    }

    private function createSlot($repeated, $forceRepeatedDuringDeploying = null)
    {
        $slot = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\Slot')
                    ->disableOriginalConstructor()
                    ->getMock();
        
        $slot->expects($this->any())
            ->method('getRepeated')
            ->will($this->returnValue($repeated))
        ;

        $slot->expects($this->once())
            ->method('getForceRepeatedDuringDeploying')
            ->will($this->returnValue($forceRepeatedDuringDeploying))
        ;
        
        return $slot;
    }
    
    private function createBlock($slotName, $html, $metatags = null)
    {
        $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block', array('getSlotName', 'getHtml', 'getMetaTags', '__toString'));
        
        $block->expects($this->once())
            ->method('getSlotName')
            ->will($this->returnValue($slotName))
        ;
        
        // Following statemes are declared just for the test and not for the SUT
        $block->expects($this->once())
            ->method('getHtml')
            ->will($this->returnValue($html))
        ;
        
        $block->expects($this->once())
            ->method('getMetaTags')
            ->will($this->returnValue($metatags))
        ;
        
        return $block;
    }
}