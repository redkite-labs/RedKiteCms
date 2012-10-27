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

namespace AlphaLemon\Block\NavigationMenuBundle\Tests\DependencyInjection;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\Block\NavigationMenuBundle\DependencyInjection\NavigationMenuExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * NavigationMenuExtensionTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class NavigationMenuExtensionTest extends TestCase
{
    private $container;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();
    }

    public function testAlias()
    {
        $extension = new NavigationMenuExtension();
        $this->assertEquals('navigation_menu', $extension->getAlias());
    }

    public function testDefaultConfiguration()
    {
        $extension = new NavigationMenuExtension();
        $extension->load(array(array()), $this->container);
    }
}
