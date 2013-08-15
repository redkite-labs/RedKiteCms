<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\ThemeEngineBundle\Tests\Unit\Core\Generator\Base;

use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;
use RedKiteLabs\ThemeEngineBundle\Core\Generator\TemplateParser\AlTemplateParser;
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
        $baseThemeDir = __DIR__ . '/../../../../../../../../business-website-theme-bundle/AlphaLemon/Theme/BusinessWebsiteThemeBundle/Resources/views';
        if ( ! is_dir($baseThemeDir)) { 
            $baseThemeDir = __DIR__ . '/../../../../../vendor/alphalemon/business-website-theme-bundle/AlphaLemon/Theme/BusinessWebsiteThemeBundle/Resources/views';
            if ( ! is_dir($baseThemeDir)) {
                $this->markTestSkipped(
                    'BusinessWebsiteThemeBundle is not available.'
                );
            }
        }
        
        vfsStream::copyFromFileSystem($baseThemeDir ,$this->root);
    }
}