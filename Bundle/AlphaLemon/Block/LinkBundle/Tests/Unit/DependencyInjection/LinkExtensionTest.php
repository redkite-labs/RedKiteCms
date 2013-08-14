<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace AlphaLemon\Block\LinkBundle\Tests\Unit\DependencyInjection;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use AlphaLemon\Block\LinkBundle\DependencyInjection\LinkExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * LinkExtensionTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class LinkExtensionTest extends TestCase
{
    private $container;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();
    }

    public function testAlias()
    {
        $extension = new LinkExtension();
        $this->assertEquals('link', $extension->getAlias());
    }
    
    public function testDefaultConfiguration()
    {
        $extension = new LinkExtension();
        $extension->load(array(array()), $this->container);
    }
}
