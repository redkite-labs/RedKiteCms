<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\PageTree;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\ThemesCollectionWrapper\AlThemesCollectionWrapper;

/**
 * AlThemesCollectionWrapperTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlThemesCollectionWrapperTest extends TestCase
{
    private $themesCollectionWrapper;
    private $themesCollection;
    private $templateManager;

    protected function setUp()
    {
        parent::setUp();

        $this->themesCollection = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->templateManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->themesCollectionWrapper = new AlThemesCollectionWrapper($this->themesCollection, $this->templateManager);
    }

    public function testFetchAThemeFromTheThemesCollection()
    {
        $theme = $this->setUpTheme();

        $this->assertEquals($theme, $this->themesCollectionWrapper->getTheme('fakeTheme'));
    }

    public function testFetchATemplateFromTheThemesCollection()
    {
        $template = $this->setUpTemplate();

        $this->assertEquals($template, $this->themesCollectionWrapper->getTemplate('fakeTheme', 'fakeTemplate'));
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\ThemesCollectionWrapper\Exception\NonExistentTemplateException
     */
    public function testFetchAThemeFromTheThemesCollection1()
    {
        $this->setUpTheme();

        $this->themesCollectionWrapper->assignTemplate('fakeTheme', 'fakeTemplate');
    }

    public function testAssignATemplateToTheTemplateManager()
    {
        $template = $this->setUpTemplate();

        $this->templateManager->expects($this->once())
            ->method('setTemplate')
            ->with($template);

        $this->assertEquals($this->templateManager, $this->themesCollectionWrapper->assignTemplate('fakeTheme', 'fakeTemplate'));
    }

    private function setUpTheme()
    {
        $theme = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\Theme\AlThemeInterface');
        $this->themesCollection->expects($this->once())
            ->method('getTheme')
            ->will($this->returnValue($theme));

        return $theme;
    }

    private function setUpTemplate()
    {
        $theme = $this->setUpTheme();
        $template = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate')
                                ->disableOriginalConstructor()
                                ->getMock();
        $theme->expects($this->once())
            ->method('getTemplate')
            ->will($this->returnValue($template));

        return $template;
    }
}
