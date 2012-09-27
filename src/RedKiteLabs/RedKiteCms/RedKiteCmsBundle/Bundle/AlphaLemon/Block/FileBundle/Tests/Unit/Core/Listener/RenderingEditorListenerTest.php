<?php
/*
 * This file is part of the BusinessDropCapBundle and it is distributed
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

namespace AlphaLemon\Block\FileBundle\Tests\Unit\Core\Listener;

use AlphaLemon\Block\BusinessDropCapBundle\Tests\TestCase;
use AlphaLemon\Block\FileBundle\Core\Listener\RenderingEditorListener;


class TestFileEditoristener extends RenderingEditorListener
{
    protected $configureParams = null;

    public function configure()
    {
        return parent::configure();
    }
    
    public function formatContent($content)
    {
        return parent::formatContent($content);
    }
}

/**
 * RenderingEditorListenerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class RenderingEditorListenerTest extends TestCase
{
    public function testConfigure()
    {
        $expectedResult = array(
            'blockClass' => '\AlphaLemon\Block\FileBundle\Core\Block\AlBlockManagerFile',
            'formClass' => '\AlphaLemon\Block\FileBundle\Core\Form\AlFileType',
        );
        $listener = new TestFileEditoristener();
        $this->assertEquals($expectedResult, $listener->configure());
    }
    
    public function testFormatContent()
    {
        $listener = new TestFileEditoristener();
        $content = $listener->formatContent(array('opened' => '0'));
        $this->assertFalse($content['opened']);
        $content = $listener->formatContent(array('opened' => '1'));
        $this->assertTrue($content['opened']);
    }
}