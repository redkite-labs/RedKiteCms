<?php
/*
 * This file is part of the AlphaLemonBootstrapBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 *
 * @license    MIT License
 */

namespace AlphaLemon\BootstrapBundle\Tests\Unit\Json;

use org\bovigo\vfs\vfsStream;
use AlphaLemon\BootstrapBundle\Tests\TestCase;
use AlphaLemon\BootstrapBundle\Core\Json\JsonToolkit;


/**
 * JsonAutoloaderTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class JsonToolkitTest extends TestCase
{
    private $root;
    private $jsonToolkit;

    protected function setUp()
    {
        parent::setUp();

        $this->jsonAutoload = '{' . PHP_EOL;
        $this->jsonAutoload .= '  "bundels" : {' . PHP_EOL;
        $this->jsonAutoload .= '    "AlphaLemon\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $this->jsonAutoload .= '       "environments" : ["all"]' . PHP_EOL;
        $this->jsonAutoload .= '    }' . PHP_EOL;
        $this->jsonAutoload .= '  }' . PHP_EOL;
        $this->jsonAutoload .= '}';
        
        $this->root = vfsStream::setup('root');
        $this->jsonToolkit = new JsonToolkit();
    }
    
    public function testFileGetContentsReturnsAnEmptyStringWhenTheFileDoesNotExist()
    {
        $this->assertEquals("", $this->jsonToolkit->getFileContents(vfsStream::url('root/autoload.json')));
    }
    
    public function testAnExceptionIsThrownWhenTheBundlesSectionDoesNotExists()
    {
        file_put_contents(vfsStream::url('root/autoload.json'), $this->jsonAutoload);
        
        $this->assertEquals($this->jsonAutoload, $this->jsonToolkit->getFileContents(vfsStream::url('root/autoload.json')));
    }
    
    public function testDecodeJsonFileReturnsAnArray()
    {
        file_put_contents(vfsStream::url('root/autoload.json'), $this->jsonAutoload);
        
        $this->assertEquals(1, count($this->jsonToolkit->decode(vfsStream::url('root/autoload.json'))));
    }
}