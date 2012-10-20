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

namespace AlphaLemon\ThemeEngineBundle\Tests\Unit\Core\Generator\Base;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\Filesystem\Filesystem;

/**
 * AlAppGeneratorBase
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlAppGeneratorBase extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->fileSystem = new Filesystem();
        $this->root = vfsStream::setup('root', null, array('src'));
        vfsStream::copyFromFileSystem(__DIR__ . '/../../../../../../../../../sensio/generator-bundle/Sensio/Bundle/GeneratorBundle/Resources/skeleton/bundle', $this->root);
        //use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;print_r(vfsStream::inspect(new vfsStreamStructureVisitor())->getStructure());exit;
    }
}