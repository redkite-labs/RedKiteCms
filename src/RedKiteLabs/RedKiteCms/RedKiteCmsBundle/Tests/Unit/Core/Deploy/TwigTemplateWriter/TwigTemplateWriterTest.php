<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Deploy\TwigTemplateWriter;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\TwigTemplateWriter\TwigTemplateWriter;
use org\bovigo\vfs\vfsStream;


/**
 * TwigTemplateWriterTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class TwigTemplateWriterTest extends TestCase
{
    protected $metatagManager;
    protected $assetManager;
    protected $contentManager;


    protected function setUp()
    {
        parent::setUp();
        
        
    }
    
    /**
     * @dataProvider templateOptionsProvider
     */
    public function testGenerateTemplate($options, $expectedResult, $filename)
    {
        $times = 1;
        $theme = $this->getMock("RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface");
        if ($options["type"] == "Base") {
            $times = 0;
            
            $theme->expects($this->once())
                ->method('getThemeName')
                ->will($this->returnValue("BootbusinessThemeBundle"))
            ;
        }
        $metatagManager = $this->createMetatagManager($times);
        $assetManager = $this->createAssetManager($times);
        $contentManager = $this->createContentManager();
        
        $root = vfsStream::setup('root');
        $pageTree = $this->createPageTree((boolean) ! $times);
        
        $twigTemplateWrite = new TwigTemplateWriter($metatagManager, $assetManager, $contentManager);
        $twigTemplateWrite->generateTemplate($pageTree, $theme, $options)->writeTemplate(vfsStream::url('root'));
        $this->assertEquals($expectedResult, $twigTemplateWrite->getTwigTemplate());
        $this->assertEquals($expectedResult, file_get_contents(vfsStream::url($filename)));
    }

    public function templateOptionsProvider()
    {
        return array(
            array(
                array(
                    "type" => "Pages",
                    "deployBundle" => "AcmeWebSiteBundle",
                    "templatesDir" => "RedKite",
                ),
                "{% extends 'AcmeWebSiteBundle:RedKite:en/base/home.html.twig' %}" . PHP_EOL .
                "Metatags section generated" . PHP_EOL .
                "Assets section generated" . PHP_EOL .
                "Contents section generated",
                'root/en/index.html.twig',
            ),
            array(
                array(
                    "type" => "Base",
                ),
                "{% extends 'BootbusinessThemeBundle:Theme:home.html.twig' %}" . PHP_EOL .
                "Contents section generated",
                'root/base/home.html.twig',
            ),
        );
    }
    
    private function createPageTree($isBasePage)
    {
        $pageTree = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree')
                                ->disableOriginalConstructor()
                                ->setMethods(array('getAlLanguage', 'getAlPage', 'getTemplate'))
                                ->getMock();
        
        $language = $this->createLanguage();
        $pageTree->expects($this->once())
            ->method('getAlLanguage')
            ->will($this->returnValue($language))
        ;
        
        $page = $this->createPage();
        $pageTree->expects($this->once())
            ->method('getAlPage')
            ->will($this->returnValue($page))
        ;
        
        if ($isBasePage) {
            $template = $this->getMockBuilder('RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate')
                                ->disableOriginalConstructor()
                                ->getMock();
            $template->expects($this->exactly(2))
                ->method('getTemplateName')
                ->will($this->returnValue("home"))
            ;
            
            $pageTree->expects($this->once())
                ->method('getTemplate')
                ->will($this->returnValue($template))
            ;            
        } else {            
            $language->expects($this->atLeastOnce())
                ->method('getLanguageName')
                ->will($this->returnValue("en"))
            ;
            
            $page->expects($this->once())
                ->method('getPageName')
                ->will($this->returnValue("index"))
            ;
            
            $page->expects($this->once())
                ->method('getTemplateName')
                ->will($this->returnValue("home"))
            ;
        }
        
        return $pageTree;
    }
    
    private function createLanguage()
    {
        return $this->getMock("RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage");
    }
    
    private function createPage()
    {
        return $this->getMock("RedKiteLabs\RedKiteCmsBundle\Model\AlPage");
    }
    
    
    private function createMetatagManager($times = 1)
    {
        $metatagManager = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Deploy\TemplateSection\MetatagSection')
                                ->disableOriginalConstructor()
                                ->getMock();
        
        $metatagManager->expects($this->exactly($times))
            ->method('generateSection')
            ->will($this->returnValue("Metatags section generated" . PHP_EOL))
        ;
        
        return $metatagManager;
    }
    
    private function createAssetManager($times = 1)
    {
        $assetManager = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Deploy\TemplateSection\AssetSection')
                                ->disableOriginalConstructor()
                                ->getMock();       
        $assetManager->expects($this->exactly($times))
            ->method('generateSection')
            ->will($this->returnValue("Assets section generated" . PHP_EOL))
        ;
        
        return $assetManager;
    }
    
    private function createContentManager()
    {
        $contentManager = $this->getMockBuilder('RedKiteLabs\RedKiteCmsBundle\Core\Deploy\TemplateSection\ContentSection')
                                ->disableOriginalConstructor()
                                ->getMock();       
        $contentManager->expects($this->once())
            ->method('generateSection')
            ->will($this->returnValue("Contents section generated"))
        ;
        
        return $contentManager;
    }
}