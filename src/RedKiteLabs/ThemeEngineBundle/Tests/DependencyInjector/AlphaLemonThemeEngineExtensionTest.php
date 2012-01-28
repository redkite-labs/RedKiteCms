<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 * 
 */
namespace AlphaLemon\ThemeEngineBundle\Tests\DependencyInjector;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\DependencyInjection\AlphaLemonThemeEngineExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;


class AlphaLemonThemeEngineExtensionTest extends TestCase 
{   
    public function testConfigLoad()
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.debug', false);
        $loader = new AlphaLemonThemeEngineExtension();

        $loader->load(array(array()), $container);
        $this->assertTrue(count($container->getParameter('althemes.stylesheets')) > 0);
        $this->assertEquals('AlphaLemonThemeEngineBundle:Theme:base.html.twig', $container->getParameter('althemes.base_template'));
        $this->assertEquals('AlphaLemonThemeEngineBundle:Themes:index.html.twig', $container->getParameter('althemes.base_theme_manager_template'));
        $this->assertEquals('AlphaLemonThemeEngineBundle:Themes:theme_panel_sections.html.twig', $container->getParameter('althemes.panel_sections_template'));
        $this->assertEquals('AlphaLemonThemeEngineBundle:Themes:theme_skeleton.html.twig', $container->getParameter('althemes.theme_skeleton_template'));
        $this->assertEquals('Themes', $container->getParameter('althemes.base_dir'));
        $this->assertSame(array('title', 'description', 'author', 'license', 'website', 'email', 'version'), $container->getParameter('althemes.info_valid_entries'));        
    }
}