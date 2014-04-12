<?php
/**
 * This file is part of the RedKiteLabsRedKiteCmsBundle and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Generator\Base;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\TestCase;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\Filesystem\Filesystem;

/**
 * AppGeneratorBase
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AppGeneratorBase extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->fileSystem = new Filesystem();
        $this->root = vfsStream::setup('root', null, array('src', 'bundle' => array()));
        
        $sensioDir = __DIR__ . '/../../../../../../../../../vendor/sensio/generator-bundle/Sensio/Bundle/GeneratorBundle/Resources/skeleton/bundle';
        if ( ! is_dir($sensioDir)) {
            $sensioDir = __DIR__ . '/../../../../../vendor/sensio/generator-bundle/Sensio/Bundle/GeneratorBundle/Resources/skeleton/bundle';
            if ( ! is_dir($sensioDir)) {
                $this->markTestSkipped(
                    'Sension Generator bundle is not available.'
                );
            }
        }
        
        vfsStream::copyFromFileSystem($sensioDir, $this->root->getChild('bundle'));//print_r(vfsStream::inspect(new \org\bovigo\vfs\visitor\vfsStreamStructureVisitor())->getStructure());exit;
    }
}