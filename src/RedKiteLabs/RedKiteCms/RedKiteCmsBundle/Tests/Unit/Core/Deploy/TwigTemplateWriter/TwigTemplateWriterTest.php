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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Deploy\TwigTemplateWriter;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\TwigTemplateWriter\TwigTemplateWriter;
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
    
    /**
     * @dataProvider templateOptionsProvider
     */
    public function testGenerateTemplate($options, $expectedResult, $filename, $isPublished)
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
        $metatagManager = $this->createMetatagManager($times, $isPublished);
        $assetManager = $this->createAssetManager($times, $isPublished);
        $contentManager = $this->createContentManager($isPublished);
        
        $root = vfsStream::setup('root');
        $pageTree = $this->createPageTree((boolean) ! $times, $isPublished);
        
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
                true,
            ),
            array(
                array(
                    "type" => "Pages",
                    "deployBundle" => "AcmeWebSiteBundle",
                    "templatesDir" => "RedKite",
                ),
                "{% extends 'RedKiteLabsThemeEngineBundle:Frontend:unpublished.html.twig' %}",
                'root/en/index.html.twig',
                false,
            ),
            array(
                array(
                    "type" => "Base",
                ),
                "{% extends 'BootbusinessThemeBundle:Theme:home.html.twig' %}" . PHP_EOL .
                "Contents section generated",
                'root/base/home.html.twig',
                null,
            ),
        );
    }
    
    private function createPageTree($isBasePage, $isPublished)
    {
        $pageTree = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\AlPageTree')
                                ->disableOriginalConstructor()
                                ->setMethods(array('getAlLanguage', 'getAlPage', 'getTemplate'))
                                ->getMock();
        
        $language = $this->createLanguage();
        $pageTree->expects($this->once())
            ->method('getAlLanguage')
            ->will($this->returnValue($language))
        ;
        
        $page = $this->createPage($isPublished);
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
            
            if (false === $isPublished) {
                $page->expects($this->never())
                    ->method('getTemplateName')
                ;
            } else {
                $page->expects($this->once())
                    ->method('getTemplateName')
                    ->will($this->returnValue("home"))
                ;
            }
        }
        
        return $pageTree;
    }
    
    private function createLanguage()
    {
        return $this->getMock("RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlLanguage");
    }
    
    private function createPage($isPublished)
    {
        $page = $this->getMock("RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlPage");
        
        if (null !== $isPublished) {
            $page->expects($this->once())
                ->method('getIsPublished')
                ->will($this->returnValue($isPublished))
            ;
        }
        
        return $page;
    }
    
    
    private function createMetatagManager($times = 1, $isPublished = true)
    {
        $metatagManager = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\TemplateSection\MetatagSection')
                                ->disableOriginalConstructor()
                                ->getMock();
        
        if (false === $isPublished) {
            $metatagManager->expects($this->never())
                ->method('generateSection')
            ;
        } else {
            $metatagManager->expects($this->exactly($times))
                ->method('generateSection')
                ->will($this->returnValue("Metatags section generated" . PHP_EOL))
            ;
        }
        
        return $metatagManager;
    }
    
    private function createAssetManager($times = 1, $isPublished = true)
    {
        $assetManager = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\TemplateSection\AssetSection')
                                ->disableOriginalConstructor()
                                ->getMock();       
        if (false === $isPublished) {
            $assetManager->expects($this->never())
                ->method('generateSection')
            ;
        } else {
            $assetManager->expects($this->exactly($times))
                ->method('generateSection')
                ->will($this->returnValue("Assets section generated" . PHP_EOL))
            ;
        }
        
        return $assetManager;
    }
    
    private function createContentManager($isPublished)
    {
        $contentManager = $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\TemplateSection\ContentSection')
                                ->disableOriginalConstructor()
                                ->getMock();       
        
        if (false === $isPublished) {
            $contentManager->expects($this->never())
                ->method('generateSection')
            ;
        } else {
            $contentManager->expects($this->once())
                ->method('generateSection')
                ->will($this->returnValue("Contents section generated"))
            ;
        }
        
        return $contentManager;
    }
}