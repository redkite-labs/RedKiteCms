<?php
/**
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

namespace AlphaLemon\Block\ScriptBundle\Tests\DependencyInjection;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\Block\ScriptBundle\DependencyInjection\ScriptExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * ScriptExtensionTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class ScriptExtensionTest extends TestCase
{
    private $container;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();
    }

    public function testAlias()
    {
        $extension = new ScriptExtension();
        $this->assertEquals('script', $extension->getAlias());
    }
}
