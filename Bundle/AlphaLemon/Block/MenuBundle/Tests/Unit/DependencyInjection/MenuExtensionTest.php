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

namespace AlphaLemon\Block\MenuBundle\Tests\DependencyInjection;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\Block\MenuBundle\DependencyInjection\MenuExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * MenuExtensionTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class MenuExtensionTest extends TestCase
{
    private $container;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();
    }

    public function testAlias()
    {
        $extension = new MenuExtension();
        $this->assertEquals('menu', $extension->getAlias());
    }
    
    public function testDefaultConfiguration()
    {
        $extension = new MenuExtension();
        $extension->load(array(array()), $this->container);
        $this->assertEquals(array('rich_editor' => 1), $this->container->getParameter('menu.editor_settings'));
    }
}
