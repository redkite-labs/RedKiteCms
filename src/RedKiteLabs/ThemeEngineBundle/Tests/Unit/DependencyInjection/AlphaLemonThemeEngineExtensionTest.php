<?php

namespace AlphaLemon\ThemeEngineBundle\Tests\Unit\DependencyInjection;

use AlphaLemon\ThemeEngineBundle\Tests\TestCase;
use AlphaLemon\ThemeEngineBundle\DependencyInjection\AlphaLemonThemeEngineExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * AlphaLemonThemeEngineExtensionTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlphaLemonThemeEngineExtensionTest extends TestCase
{
    private $container;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();        
        $this->container->setParameter('kernel.root_dir', 'root');
    }

    public function testDefaultConfiguration()
    {
        $extension = new AlphaLemonThemeEngineExtension();
        $extension->load(array(array('deploy_bundle' => 'AcmsWebSiteBundle')), $this->container);
        $this->assertEquals('AlphaLemonThemeEngineBundle:Theme:base.html.twig', $this->container->getParameter('alpha_lemon_theme_engine.base_template'));
        $this->assertEquals('AlphaLemonThemeEngineBundle:Themes:index.html.twig', $this->container->getParameter('alpha_lemon_theme_engine.themes_panel.base_theme'));
        $this->assertEquals('AlphaLemonThemeEngineBundle:Themes:theme_panel_sections.html.twig', $this->container->getParameter('alpha_lemon_theme_engine.themes_panel.theme_section'));
        $this->assertEquals('AlphaLemonThemeEngineBundle:Themes:theme_skeleton.html.twig', $this->container->getParameter('alpha_lemon_theme_engine.themes_panel.theme_skeleton'));
        $this->assertEquals('%kernel.root_dir%/Resources/.active_theme', $this->container->getParameter('alpha_lemon_theme_engine.active_theme_file'));
        $this->assertEquals(
            array(
                'title',
                'description',
                'author',
                'license',
                'website',
                'email',
                'version',
            ), $this->container->getParameter('alpha_lemon_theme_engine.info_valid_entries'));
        $this->assertEquals('AlphaLemon\ThemeEngineBundle\Core\Theme\AlActiveTheme', $this->container->getParameter('alpha_lemon_theme_engine.active_theme.class'));
        $this->assertEquals('AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection', $this->container->getParameter('alpha_lemon_theme_engine.themes.class'));
        $this->assertEquals('AlphaLemon\ThemeEngineBundle\Core\Theme\AlTheme', $this->container->getParameter('alpha_lemon_theme_engine.theme.class'));
        $this->assertEquals('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot', $this->container->getParameter('alpha_lemon_theme_engine.slot.class'));
        $this->assertEquals('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate', $this->container->getParameter('alpha_lemon_theme_engine.template.class'));
        $this->assertEquals('AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplateAssets', $this->container->getParameter('alpha_lemon_theme_engine.template_assets.class'));
        $this->assertEquals('AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlots', $this->container->getParameter('alpha_lemon_theme_engine.template_slots.class'));
    }

    public function testBaseTemplate()
    {
        $this->scalarNodeParameter('alpha_lemon_theme_engine.base_template', 'base_template', 'AlphaLemonCmsBundle:Theme:base.html.twig');
    }

    public function testAtiveThemeFile()
    {
        $this->scalarNodeParameter('alpha_lemon_theme_engine.active_theme_file', 'active_theme_file', '%kernel.root_dir%/new/path');
    }
    
    public function testRenderSlotClass()
    {
        $this->scalarNodeParameter('twig.extension.render_slot.class', 'render_slot_class', 'AlphaLemon\AlphaLemonCmsBundle\Twig\SlotRendererExtension');
    }

    public function testThemesPanelBaseTheme()
    {
        $value = 'AlphaLemonCmsBundle:Themes:index.html.twig';
        $extension = new AlphaLemonThemeEngineExtension();
        $extension->load(array(array('deploy_bundle' => 'AcmsWebSiteBundle', 'themes_panel' => array('base_theme' => $value))), $this->container);
        $this->assertEquals($value, $this->container->getParameter('alpha_lemon_theme_engine.themes_panel.base_theme'));
    }
    
    public function testThemesPanelThemeSection()
    {
        $value = 'AlphaLemonCmsBundle:Themes:theme_panel_sections.html.twig';
        $extension = new AlphaLemonThemeEngineExtension();
        $extension->load(array(array('deploy_bundle' => 'AcmsWebSiteBundle', 'themes_panel' => array('theme_section' => $value))), $this->container);
        $this->assertEquals($value, $this->container->getParameter('alpha_lemon_theme_engine.themes_panel.theme_section'));
    }
    
    public function testThemesPanelThemeKeleton()
    {
        $value = 'AlphaLemonCmsBundle:Themes:theme_skeleton.html.twig';
        $extension = new AlphaLemonThemeEngineExtension();
        $extension->load(array(array('deploy_bundle' => 'AcmsWebSiteBundle', 'themes_panel' => array('theme_skeleton' => $value))), $this->container);
        $this->assertEquals($value, $this->container->getParameter('alpha_lemon_theme_engine.themes_panel.theme_skeleton'));
    }
    
    private function scalarNodeParameter($parameter, $configKey, $configValue)
    {
        $extension = new AlphaLemonThemeEngineExtension();
        $extension->load(array(array('deploy_bundle' => 'AcmsWebSiteBundle', $configKey => $configValue)), $this->container);
        $this->assertEquals($configValue, $this->container->getParameter($parameter));
    }
}
