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
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Generator\TemplateParser\TemplateParser;
use org\bovigo\vfs\vfsStream;

/**
 * TemplateParserTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class GeneratorBase extends TestCase
{
    protected $root;
    protected $parser;

    protected function setUp()
    {
        parent::setUp();

        $this->root = vfsStream::setup('root');

        $templateNameParser = $this->getMock('Symfony\Component\Templating\TemplateNameParserInterface');
        $templateLocator = $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator')
                           ->disableOriginalConstructor()
                            ->getMock();
        
        $this->parser = new TemplateParser($templateLocator, $templateNameParser);
    }

    protected function importDefaultTheme()
    {
        $baseThemeDir = __DIR__ . '/../../../../../../../../../src/RedKiteCms/Theme/BootbusinessThemeBundle/Resources/views/Theme';
        if ( ! is_dir($baseThemeDir)) { 
            $baseThemeDir = __DIR__ . '/../../../../../vendor/redkite-labs/bootbusiness-theme-bundle/RedKiteCms/Theme/BootbusinessThemeBundle/Resources/views/Theme';
            if ( ! is_dir($baseThemeDir)) {
                $this->markTestSkipped(
                    'BusinessWebsiteThemeBundle is not available.'
                );
            }
        }
        
        vfsStream::copyFromFileSystem($baseThemeDir ,$this->root);
    }
}