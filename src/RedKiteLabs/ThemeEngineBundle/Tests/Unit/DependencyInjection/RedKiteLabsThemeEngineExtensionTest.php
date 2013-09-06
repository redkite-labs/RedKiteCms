<?php

namespace RedKiteLabs\ThemeEngineBundle\Tests\Unit\DependencyInjection;

use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;
use RedKiteLabs\ThemeEngineBundle\DependencyInjection\RedKiteLabsThemeEngineExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * RedKiteLabsThemeEngineExtensionTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class RedKiteLabsThemeEngineExtensionTest extends TestCase
{
    private $container;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();        
        $this->container->setParameter('kernel.root_dir', 'root');
    }
    
    public function testGetAlias()
    {
        $extension = new RedKiteLabsThemeEngineExtension();
        $this->assertEquals('red_kite_labs_theme_engine', $extension->getAlias());
    }

    public function testDefaultConfiguration()
    {
        $extension = new RedKiteLabsThemeEngineExtension();
        $extension->load(array(array('deploy_bundle' => 'AcmsWebSiteBundle')), $this->container);
        $this->assertEquals('RedKiteLabsThemeEngineBundle:Frontend:base.html.twig', $this->container->getParameter('red_kite_labs_theme_engine.base_template'));
        $this->assertEquals('RedKiteCms', $this->container->getParameter('red_kite_labs_theme_engine.deploy.templates_folder'));
        $this->assertEquals('%kernel.root_dir%/Resources/.active_theme', $this->container->getParameter('red_kite_labs_theme_engine.active_theme_file'));
        $this->assertEquals(
            array(
                'title',
                'description',
                'author',
                'license',
                'website',
                'email',
                'version',
            ), $this->container->getParameter('red_kite_labs_theme_engine.info_valid_entries'));
        $this->assertEquals('RedKiteLabs\ThemeEngineBundle\Core\Theme\AlActiveTheme', $this->container->getParameter('red_kite_labs_theme_engine.active_theme.class'));
        $this->assertEquals('RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection', $this->container->getParameter('red_kite_labs_theme_engine.themes.class'));
        $this->assertEquals('RedKiteLabs\ThemeEngineBundle\Core\Theme\AlTheme', $this->container->getParameter('red_kite_labs_theme_engine.theme.class'));
        $this->assertEquals('RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlSlot', $this->container->getParameter('red_kite_labs_theme_engine.slot.class'));
        $this->assertEquals('RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate', $this->container->getParameter('red_kite_labs_theme_engine.template.class'));
        $this->assertEquals('RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplateAssets', $this->container->getParameter('red_kite_labs_theme_engine.template_assets.class'));
        $this->assertEquals('RedKiteLabs\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlots', $this->container->getParameter('red_kite_labs_theme_engine.template_slots.class'));
    }

    public function testBaseTemplate()
    {
        $this->scalarNodeParameter('red_kite_labs_theme_engine.base_template', 'base_template', 'RedKiteCmsBundle:Theme:base.html.twig');
    }

    public function testAtiveThemeFile()
    {
        $this->scalarNodeParameter('red_kite_labs_theme_engine.active_theme_file', 'active_theme_file', '%kernel.root_dir%/new/path');
    }
    
    public function testRenderSlotClass()
    {
        $this->scalarNodeParameter('twig.extension.render_slot.class', 'render_slot_class', 'RedKiteLabs\RedKiteCmsBundle\Twig\SlotRendererExtension');
    }
    
    public function testRenderSlotClass1()
    {
        $this->scalarNodeParameter('red_kite_labs_theme_engine.deploy.templates_folder', 'templates_folder', 'AlphaLemon');
    }

    private function scalarNodeParameter($parameter, $configKey, $configValue)
    {
        $extension = new RedKiteLabsThemeEngineExtension();
        $extension->load(array(array('deploy_bundle' => 'AcmsWebSiteBundle', $configKey => $configValue)), $this->container);
        $this->assertEquals($configValue, $this->container->getParameter($parameter));
    }
}
