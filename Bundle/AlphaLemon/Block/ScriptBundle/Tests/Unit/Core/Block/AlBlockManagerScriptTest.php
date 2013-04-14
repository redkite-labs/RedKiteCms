<?php
/**
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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block;

use AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block\Base\AlBlockManagerContainerBase;
use AlphaLemon\Block\ScriptBundle\Core\Block\AlBlockManagerScript;

/**
 * AlBlockManagerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerScriptTest extends AlBlockManagerContainerBase
{
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->blockManager = new AlBlockManagerScript($this->container, $this->validator);
    }

    public function testDefaultValue()
    {
        $expectedValue = array(
            'Content' => '<p>This is a default script content</p>',
            'InternalJavascript' => '',
            'ExternalJavascript' => ''
        );
        $this->assertEquals($expectedValue, $this->blockManager->getDefaultValue());
    }

    public function testContentDisplaysTheContentWhenAnyJavascriptTagExists()
    {
        $htmlContent = 'A fancy javascript';
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $this->blockManager->set($block);        
        $result = $this->blockManager->getHtml();
        $this->assertArrayHasKey('RenderView', $result);
        $this->assertEquals('ScriptBundle:Content:script.html.twig', $result['RenderView']['view']);
    }

    public function testHideInEditMode()
    {
        $this->markTestSkipped(
            'Does not work correctly the very first time is runned by the full test suite.'
        );
        
        $this->assertTrue($this->blockManager->getHideInEditMode());
    }

    public function testReloadSuggested()
    {
        $this->markTestSkipped(
            'Does not work correctly the very first time is runned by the full test suite.'
        );
        
        $this->assertTrue($this->blockManager->getReloadSuggested());
    }
}
