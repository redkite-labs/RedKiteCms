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

namespace AlphaLemon\ThemeEngineBundle\Tests\Unit\Core\PageTree;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\PageTree\AlPageTree;

/**
 * AlPageTreeTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlPageTreeTest extends TestCase
{
    private $container;
    private $template;
    private $pageBlocks;

    protected function setUp()
    {
        parent::setUp();

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->template = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate')
                                    ->disableOriginalConstructor()
                                    ->setMethods(array('getExternalStylesheets'))
                                    ->getMock();
        /*  
        $this->template->expects($this->once())
            ->method('getThemeName')
            ->will($this->returnValue('BusinessWebsiteThemeBundle'));

        $this->template->expects($this->once())
            ->method('getTemplateName')
            ->will($this->returnValue('Home'));*/

        $this->pageBlocks = $this->getMockBuilder('AlphaLemon\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocks')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->pageTree = new AlPageTree($this->container, $this->pageBlocks);
    }

    public function testBlockIsNotAddedWhenGivenValuesDoesNotContainAnyValidOptionParam()
    {return;
        $assetsCollection = $this->getMock('AlphaLemon\ThemeEngineBundle\Core\Asset\AlAssetsCollectionInterface');

        $this->template->expects($this->once())
            ->method('getExternalStylesheets')
            ->will($this->returnValue($assetsCollection));

        $this->pageBlocks->expects($this->once())
            ->method('getBlocks')
            ->will($this->returnValue(array()));

        print_R($this->pageTree->getExternalStylesheets());
    }

}