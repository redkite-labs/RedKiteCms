<?php

namespace AlphaLemon\ThemeEngineBundle\Tests\Unit\Twig;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Twig\FileExtension;
use org\bovigo\vfs\vfsStream;

/**
 * AlphaLemonCmsExtensionTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class FileExtensionTest extends TestCase
{
    private $fileExtension;

    protected function setUp()
    {
        $this->root = vfsStream::setup('root', null, array('foo' => 'bar'));
        
        $this->fileExtension = new FileExtension();
    }

    public function testName()
    {
        $this->assertEquals("file", $this->fileExtension->getName());
    }
    
    public function testTwigFunctions()
    {
        $this->assertArrayHasKey("file_open", $this->fileExtension->getFunctions());
    }
    
    public function testOpenFile()
    {
        $this->assertEquals("bar", $this->fileExtension->openFile(vfsStream::url('root/foo')));
    }
}