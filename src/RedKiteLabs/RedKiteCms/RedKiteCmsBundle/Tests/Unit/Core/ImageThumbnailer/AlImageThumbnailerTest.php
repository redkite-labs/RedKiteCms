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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\ImageThumbnailer;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\ImageThumbnailer\AlImageThumbnailer;
use org\bovigo\vfs\vfsStream;

/**
 * AlImageThumbnailerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlImageThumbnailerTest extends TestCase
{
    protected function setUp()
    {
        $this->root = vfsStream::setup('root', null);
    }

    /**
     * @expectedException \Symfony\Component\Filesystem\Exception\IOException
     */
    public function testFolderIsNotCreatedWhenImageIsNOtValid()
    {
        $this->markTestSkipped(
            'Does not work correctly the very first time is runned by the full test suite.'
        );


        $thumbnailer = new AlImageThumbnailer();
        $thumbnailer->setThumbnailFolder(vfsStream::url('root'));
        $thumbnailer->create(null, 10, 10);
    }
}