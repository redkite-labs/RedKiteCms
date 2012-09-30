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

namespace AlphaLemon\Block\FileBundle\Tests\Unit\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block\Base\AlBlockManagerContainerBase;
use AlphaLemon\Block\FileBundle\Core\Block\AlBlockManagerFile;
use org\bovigo\vfs\vfsStream;

/**
 * AlBlockManagerFileTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerFileTest extends AlBlockManagerContainerBase
{
    public function testDefaultValue()
    {
        $value =
        '{
            "0" : {
                "file" : "Click to load a file",
                "opened" : "0"
            }
        }';

        $expectedValue = array(
            'Content' => $value,
        );

        $this->initContainer();
        $blockManager = new AlBlockManagerFile($this->container, $this->validator);
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }

    public function testGetHideInEditMode()
    {
        $this->initContainer();
        $blockManager = new AlBlockManagerFile($this->container, $this->validator);
        $this->assertTrue($blockManager->getHideInEditMode());
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\Exception\InvalidJsonFormatException
     * @expectedExceptionMessage The content format is wrong. You should remove that block and add it again.
     */
    public function testAnExceptionIsThrownWhenTheJsonIsMalformed()
    {
        $value =
        '{
            "0" : {
                "file" : "files/my-file",
                "opened" : "1",
            }
        }';

        $block = $this->initBlock($value);
        $this->initContainer();
        $blockManager = new AlBlockManagerFile($this->container, $this->validator);
        $blockManager->set($block);
        $blockManager->getHtml();
    }

    public function testGetHtmlWhenOpenedIsFalse()
    {
        $value =
        '{
            "0" : {
                "file" : "files/my-file",
                "opened" : "0"
            }
        }';

        $block = $this->initBlock($value);
        $this->initContainerWithKernel();
        $this->container->expects($this->exactly(2))
                        ->method('getParameter')
                        ->will($this->onConsecutiveCalls('AcmeWebsiteBundle', 'uploads/assets'));

        $blockManager = new AlBlockManagerFile($this->container, $this->validator);
        $blockManager->set($block);
        $this->assertEquals('<a href="/uploads/assets/files/my-file" />my-file</a>', $blockManager->getHtml());
    }

    public function testGetHtmlWhenOpenedIsTrue()
    {
        $value =
        '{
            "0" : {
                "file" : "files/my-file",
                "opened" : "1"
            }
        }';

        $block = $this->initBlock($value);
        $this->initContainerWithKernel();
        $this->container->expects($this->once())
                        ->method('getParameter')
                        ->will($this->returnValue('AcmeWebsiteBundle'));

        $blockManager = new AlBlockManagerFile($this->container, $this->validator);
        $blockManager->set($block);
        $this->assertEquals('{% set file = kernel_root_dir ~ \'/../web/bundles/acmewebsite/files/my-file\' %} {{ file_open(file) }}', $blockManager->getHtml());
    }

    public function testGetHtmlCmsActiveWhenOpenedIsFalse()
    {
        $value =
        '{
            "0" : {
                "file" : "files/my-file",
                "opened" : "0"
            }
        }';

        $block = $this->initBlock($value);
        $this->initContainer();
        $this->container->expects($this->once())
                        ->method('getParameter')
                        ->will($this->returnValue(vfsStream::url('uploads/assets')));

        $blockManager = new AlBlockManagerFile($this->container, $this->validator);
        $blockManager->set($block);
        $this->assertEquals('<a href="/vfs://uploads/assets/files/my-file" />my-file</a><script type="text/javascript">$(document).ready(function(){$(\'#block_\').data(\'block\', \'%3Ca%20href%3D%22%2Fvfs%3A%2F%2Fuploads%2Fassets%2Ffiles%2Fmy-file%22%20%2F%3Emy-file%3C%2Fa%3E\');});</script>', $blockManager->getHtmlCmsActive());
    }

    public function testGetHtmlCmsActiveWhenOpenedIsTrue()
    {
        $value =
        '{
            "0" : {
                "file" : "files/my-file",
                "opened" : "1"
            }
        }';

        $root = vfsStream::setup('root', null, array('assets' => array('files' => array('my-file' => '<p>some html content</p>'))));

        $block = $this->initBlock($value);
        $block->expects($this->once())
              ->method('getId')
              ->will($this->returnValue(2));
        $this->initContainer();
        $this->container->expects($this->once())
                        ->method('getParameter')
                        ->will($this->returnValue(vfsStream::url('root/assets')));

        $blockManager = new AlBlockManagerFile($this->container, $this->validator);
        $blockManager->set($block);
        $this->assertEquals('<p>some html content</p><script type="text/javascript">$(document).ready(function(){$(\'#block_2\').data(\'block\', \'%3Cp%3Esome%20html%20content%3C%2Fp%3E\');});</script>', $blockManager->getHtmlCmsActive());
    }

    private function initBlock($value)
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
              ->method('getContent')
              ->will($this->returnValue($value));

        return $block;
    }

    private function initContainerWithKernel()
    {
        $this->container->expects($this->exactly(3))
                        ->method('get')
                        ->will($this->onConsecutiveCalls($this->eventsHandler, $this->factoryRepository, $this->kernel));
    }
}
