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

namespace AlphaLemon\ThemeEngineBundle\Tests\Unit\Core\Generator;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\Core\Generator\AlExtensionGenerator;
use org\bovigo\vfs\vfsStream;

/**
 * AlExtensionGeneratorTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlExtensionGeneratorTest extends Base\AlGeneratorBase
{
    private $extensionGenerator;

    protected function setUp()
    {
        parent::setUp();

        $this->root = vfsStream::setup('root', null, array('DependencyInjection'));
        vfsStream::copyFromFileSystem(__DIR__ . '/../../../../Resources/skeleton', $this->root);

        $this->extensionGenerator = new AlExtensionGenerator(vfsStream::url('root/app-theme'));
    }

    public function testExtensionFileHasBeenGenerated()
    {
        $templates = array('home.html.twig' => array());

        $message = $this->extensionGenerator->generateExtension('my/namespace/', vfsStream::url('root/DependencyInjection'), 'FakeThemeBundle', $templates);

        $expected = '';

        $file = vfsStream::url('root/DependencyInjection/FakeThemeExtension.php');
        $this->assertFileExists($file);
        $extensionContents = file_get_contents($file);
        $pattern = "/'path' =\> __DIR__\.'\/\.\.\/Resources\/config',\n[\s]+'configFiles' =\>\n[\s]+array\(\n[\s]+'fake_theme.xml',\n[\s]+\),/";
        $this->assertRegExp($pattern, $extensionContents);
        $pattern = "/'path' =\> __DIR__\.'\/\.\.\/Resources\/config\/templates',\n[\s]+'configFiles' =\>\n[\s]+array\(\n[\s]+'home.xml',\n[\s]+\),/";
        $this->assertRegExp($pattern, $extensionContents);
        $pattern = "/'path' =\> __DIR__\.'\/\.\.\/Resources\/config\/templates\/slots',\n[\s]+'configFiles' =\>\n[\s]+array\(\n[\s]+'home.xml',\n[\s]+\),/";
        $this->assertRegExp($pattern, $extensionContents);
        $pattern = "/return 'fake_theme';/";
        $this->assertRegExp($pattern, $extensionContents);

        $expected = 'The extension file <info>FakeThemeExtension.php</info> has been generated into <info>vfs://root/DependencyInjection</info>';
        $this->assertEquals($expected, $message);
    }

    public function testBaseFileIsSkippedForTemplates()
    {
        $templates = array('home.html.twig' => array(), 'base.html.twig' => array());

        $message = $this->extensionGenerator->generateExtension('my/namespace/', vfsStream::url('root/DependencyInjection'), 'FakeThemeBundle', $templates);

        $expected = '';

        $file = vfsStream::url('root/DependencyInjection/FakeThemeExtension.php');
        $this->assertFileExists($file);
        $extensionContents = file_get_contents($file);
        $pattern = "/'path' =\> __DIR__\.'\/\.\.\/Resources\/config',\n[\s]+'configFiles' =\>\n[\s]+array\(\n[\s]+'fake_theme.xml',\n[\s]+\),/";
        $this->assertRegExp($pattern, $extensionContents);
        $pattern = "/'path' =\> __DIR__\.'\/\.\.\/Resources\/config\/templates',\n[\s]+'configFiles' =\>\n[\s]+array\(\n[\s]+'home.xml',\n[\s]+\),/";
        $this->assertRegExp($pattern, $extensionContents);
        $pattern = "/'path' =\> __DIR__\.'\/\.\.\/Resources\/config\/templates\/slots',\n[\s]+'configFiles' =\>\n[\s]+array\(\n[\s]+'home.xml',\n[\s]+'base.xml',\n[\s]+\),/";
        $this->assertRegExp($pattern, $extensionContents);

        $expected = 'The extension file <info>FakeThemeExtension.php</info> has been generated into <info>vfs://root/DependencyInjection</info>';
        $this->assertEquals($expected, $message);
    }
}