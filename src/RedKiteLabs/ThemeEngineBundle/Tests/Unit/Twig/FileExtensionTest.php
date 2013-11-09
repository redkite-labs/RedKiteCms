<?php

namespace RedKiteLabs\ThemeEngineBundle\Tests\Unit\Twig;

use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;
use RedKiteLabs\ThemeEngineBundle\Twig\FileExtension;
use org\bovigo\vfs\vfsStream;

/**
 * FileExtensionTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
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