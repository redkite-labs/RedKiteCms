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
use AlphaLemon\BootstrapBundle\Core\Json\JsonAutoloader;


/**
 * JsonAutoloaderTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class JsonAutoloaderTest extends TestCase
{
    private $root;

    protected function setUp()
    {
        parent::setUp();

        $this->root = vfsStream::setup('root');
    }

    /**
     * @expectedException \AlphaLemon\BootstrapBundle\Core\Exception\InvalidJsonFormatException
     * @expectedExceptionMessage The json file vfs://root/autoload.json is malformed. Please check the file syntax to fix the problem
     */
    public function testAnExceptionIsThrownWhenTheJsonIsMalformed()
    {
        $jsonAutoload = '{' . PHP_EOL;
        $jsonAutoload .= '  "bundels" : {' . PHP_EOL;
        $jsonAutoload .= '    "AlphaLemon\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $jsonAutoload .= '       "environments" : ["all"]' . PHP_EOL;
        $jsonAutoload .= '    }' . PHP_EOL;
        $jsonAutoload .= '  }' . PHP_EOL;
        file_put_contents(vfsStream::url('root/autoload.json'), $jsonAutoload);

        $autoload = new JsonAutoloader('BusinessCarousel', vfsStream::url('root/autoload.json'));
    }

    /**
     * @expectedException \AlphaLemon\BootstrapBundle\Core\Exception\InvalidJsonFormatException
     * @expectedExceptionMessage The json file vfs://root/autoload.json requires the bundles section. Please add that section to fix the problem
     */
    public function testAnExceptionIsThrownWhenTheBundlesSectionDoesNotExists()
    {
        $jsonAutoload = '{' . PHP_EOL;
        $jsonAutoload .= '  "bundels" : {' . PHP_EOL;
        $jsonAutoload .= '    "AlphaLemon\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $jsonAutoload .= '       "environments" : ["all"]' . PHP_EOL;
        $jsonAutoload .= '    }' . PHP_EOL;
        $jsonAutoload .= '  }' . PHP_EOL;
        $jsonAutoload .= '}';
        file_put_contents(vfsStream::url('root/autoload.json'), $jsonAutoload);

        $autoload = new JsonAutoloader('BusinessCarousel', vfsStream::url('root/autoload.json'));
    }

    public function testJsonFileHasBeenParsed()
    {
        $jsonAutoload = '{' . PHP_EOL;
        $jsonAutoload .= '  "bundles" : {' . PHP_EOL;
        $jsonAutoload .= '    "AlphaLemon\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $jsonAutoload .= '       "environments" : ["all"],' . PHP_EOL;
        $jsonAutoload .= '       "overrides" : ["BusinessDropCapBundle"]' . PHP_EOL;
        $jsonAutoload .= '    }' . PHP_EOL;
        $jsonAutoload .= '  }' . PHP_EOL;
        $jsonAutoload .= '}';
        file_put_contents(vfsStream::url('root/autoload.json'), $jsonAutoload);

        $autoload = new JsonAutoloader('BusinessCarousel', vfsStream::url('root/autoload.json'));
        $bundles = $autoload->getBundles();
        $this->assertEquals(1, count($bundles));
        $this->assertArrayHasKey('all', $bundles);
        $this->assertEquals(1, count($bundles['all']));

        $bundle = $bundles['all'][0];
        $this->assertEquals('BusinessCarouselFakeBundle', $bundle->getId());
        $this->assertEquals('AlphaLemon\\Block\\BusinessCarouselFakeBundle\\BusinessCarouselFakeBundle', $bundle->getClass());
        $this->assertEquals(array('BusinessDropCapBundle'), $bundle->getOverrides());
    }

    public function testWhenAnyEnvironmentIsSpecifiedBundleIsEnabledForAllEnvironments()
    {
        $jsonAutoload = '{' . PHP_EOL;
        $jsonAutoload .= '  "bundles" : {' . PHP_EOL;
        $jsonAutoload .= '    "AlphaLemon\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : ""' . PHP_EOL;
        $jsonAutoload .= '  }' . PHP_EOL;
        $jsonAutoload .= '}';
        file_put_contents(vfsStream::url('root/autoload.json'), $jsonAutoload);

        $autoload = new JsonAutoloader('BusinessCarousel', vfsStream::url('root/autoload.json'));
        $bundles = $autoload->getBundles();
        $this->assertEquals(1, count($bundles));
        $this->assertArrayHasKey('all', $bundles);
        $this->assertEquals(1, count($bundles['all']));
    }

    public function testJsonFileWithActionManager()
    {
        $jsonAutoload = '{' . PHP_EOL;
        $jsonAutoload .= '  "bundles" : {' . PHP_EOL;
        $jsonAutoload .= '    "AlphaLemon\\\\Block\\\\BusinessCarouselFakeBundle\\\\BusinessCarouselFakeBundle" : {' . PHP_EOL;
        $jsonAutoload .= '       "environments" : ["prod", "dev"],' . PHP_EOL;
        $jsonAutoload .= '       "overrides" : ["BusinessDropCapBundle"]' . PHP_EOL;
        $jsonAutoload .= '    }' . PHP_EOL;
        $jsonAutoload .= '  },' . PHP_EOL;
        $jsonAutoload .= '  "actionManager" : "\\\\AlphaLemon\\\\Block\\\\BusinessCarouselFakeBundle\\\\Core\\\\ActionManager\\\\ActionManagerBusinessCarousel"' . PHP_EOL;
        $jsonAutoload .= '}';
        file_put_contents(vfsStream::url('root/autoload.json'), $jsonAutoload);

        $autoload = new JsonAutoloader('BusinessCarousel', vfsStream::url('root/autoload.json'));
        $bundles = $autoload->getBundles();
        $this->assertEquals(2, count($bundles));
        $this->assertArrayHasKey('prod', $bundles);
        $this->assertArrayHasKey('dev', $bundles);
        $this->assertEquals(1, count($bundles['prod']));
        $this->assertEquals(1, count($bundles['dev']));

        $bundle = $bundles['prod'][0];
        $this->assertEquals('BusinessCarouselFakeBundle', $bundle->getId());
        $this->assertEquals('AlphaLemon\\Block\\BusinessCarouselFakeBundle\\BusinessCarouselFakeBundle', $bundle->getClass());
        $this->assertEquals(array('BusinessDropCapBundle'), $bundle->getOverrides());
        $bundle = $bundles['dev'][0];
        $this->assertEquals('BusinessCarouselFakeBundle', $bundle->getId());
        $this->assertEquals('AlphaLemon\\Block\\BusinessCarouselFakeBundle\\BusinessCarouselFakeBundle', $bundle->getClass());
        $this->assertEquals(array('BusinessDropCapBundle'), $bundle->getOverrides());
        $this->assertEquals('\\AlphaLemon\\Block\\BusinessCarouselFakeBundle\\Core\\ActionManager\\ActionManagerBusinessCarousel', $autoload->getActionManagerClass());

    }
}