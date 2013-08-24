<?php
/**
 * This file is part of the RedKiteLabsRedKiteCmsBundle and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Generator\Base;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\Generator\TemplateParser\AlTemplateParser;
use org\bovigo\vfs\vfsStream;

/**
 * AlTemplateParserTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlGeneratorBase extends TestCase
{
    protected $root;
    protected $parser;

    protected function setUp()
    {
        parent::setUp();

        $this->root = vfsStream::setup('root');

        $this->parser = new AlTemplateParser(vfsStream::url('root'), vfsStream::url('root/app'), 'MyThemeBundle');
    }

    protected function importDefaultTheme()
    {
        $baseThemeDir = __DIR__ . '/../../../../../../../../bootbusiness-theme-bundle/RedKiteCms/Theme/BootbusinessThemeBundle/Resources/views/Theme';
        if ( ! is_dir($baseThemeDir)) { 
            $baseThemeDir = __DIR__ . '/../../../../../../../../../redkite-labs/bootbusiness-theme-bundle/RedKiteCms/Theme/BootbusinessThemeBundle/Resources/views/Theme';
            if ( ! is_dir($baseThemeDir)) {
                $this->markTestSkipped(
                    'BusinessWebsiteThemeBundle is not available.'
                );
            }
        }
        
        vfsStream::copyFromFileSystem($baseThemeDir ,$this->root);
    }
}