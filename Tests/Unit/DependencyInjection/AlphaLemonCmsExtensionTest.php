<?php

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\DependencyInjection;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\DependencyInjection\AlphaLemonCmsExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * AlphaLemonCmsExtensionTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlphaLemonCmsExtensionTest extends TestCase
{
    private $container;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();
    }

    public function testDefaultConfiguration()
    {
        $extension = new AlphaLemonCmsExtension();
        $extension->load(array(array()), $this->container);
        $this->assertEquals('Propel', $this->container->getParameter('alpha_lemon_cms.orm'));
        $this->assertEquals('alphaLemon', $this->container->getParameter('alpha_lemon_cms.skin'));
        $this->assertEquals('web', $this->container->getParameter('alpha_lemon_cms.web_folder'));
        $this->assertEquals(array('en' => 'English'), $this->container->getParameter('alpha_lemon_cms.available_languages'));
        $this->assertEquals('uploads/assets', $this->container->getParameter('alpha_lemon_cms.upload_assets_dir'));
        $this->assertEquals('Resources', $this->container->getParameter('alpha_lemon_cms.deploy_bundle.resources_dir'));
        $this->assertEquals('%alpha_lemon_cms.deploy_bundle.resources_dir%/config', $this->container->getParameter('alpha_lemon_cms.deploy_bundle.config_dir'));
        $this->assertEquals('%alpha_lemon_cms.deploy_bundle.resources_dir%/views', $this->container->getParameter('alpha_lemon_cms.deploy_bundle.views_dir'));
        $this->assertEquals('media', $this->container->getParameter('alpha_lemon_cms.deploy_bundle.media_dir'));
        $this->assertEquals('js', $this->container->getParameter('alpha_lemon_cms.deploy_bundle.js_dir'));
        $this->assertEquals('css', $this->container->getParameter('alpha_lemon_cms.deploy_bundle.css_dir'));
    }

    public function testOrm()
    {
        $this->scalarNodeParameter('alpha_lemon_cms.orm', 'orm', 'Doctine');
    }

    public function testSkin()
    {
        $this->scalarNodeParameter('alpha_lemon_cms.skin', 'skin', 'fancySkin');
    }

    public function testWebFolder()
    {
        $this->scalarNodeParameter('alpha_lemon_cms.web_folder', 'web_folder_dir', 'content');
    }

    public function testUploadAssetsDir()
    {
        $this->scalarNodeParameter('alpha_lemon_cms.upload_assets_dir', 'upload_assets_dir', 'new/upload/path');
    }

    public function testDeployResourcesDir()
    {
        $value = 'Assets';
        $extension = new AlphaLemonCmsExtension();
        $extension->load(array(array('deploy_bundle' => array('resources_dir' => $value))), $this->container);
        $this->assertEquals($value, $this->container->getParameter('alpha_lemon_cms.deploy_bundle.resources_dir'));
    }

    public function testDeployConfigDir()
    {
        $value = 'Assets/conf';
        $extension = new AlphaLemonCmsExtension();
        $extension->load(array(array('deploy_bundle' => array('config_dir' => $value))), $this->container);
        $this->assertEquals($value, $this->container->getParameter('alpha_lemon_cms.deploy_bundle.config_dir'));
    }

    public function testDeployViewDir()
    {
        $value = 'MyRes/templates';
        $extension = new AlphaLemonCmsExtension();
        $extension->load(array(array('deploy_bundle' => array('views_dir' => $value))), $this->container);
        $this->assertEquals($value, $this->container->getParameter('alpha_lemon_cms.deploy_bundle.views_dir'));
    }

    public function testDeployMediaDir()
    {
        $value = 'MyRes/images';
        $extension = new AlphaLemonCmsExtension();
        $extension->load(array(array('deploy_bundle' => array('media_dir' => $value))), $this->container);
        $this->assertEquals($value, $this->container->getParameter('alpha_lemon_cms.deploy_bundle.media_dir'));
    }

    public function testDeployJsDir()
    {
        $value = 'MyRes/javascripts';
        $extension = new AlphaLemonCmsExtension();
        $extension->load(array(array('deploy_bundle' => array('js_dir' => $value))), $this->container);
        $this->assertEquals($value, $this->container->getParameter('alpha_lemon_cms.deploy_bundle.js_dir'));
    }

    public function testDeployCssDir()
    {
        $value = 'MyRes/stylesheets';
        $extension = new AlphaLemonCmsExtension();
        $extension->load(array(array('deploy_bundle' => array('css_dir' => $value))), $this->container);
        $this->assertEquals($value, $this->container->getParameter('alpha_lemon_cms.deploy_bundle.css_dir'));
    }

    private function scalarNodeParameter($parameter, $configKey, $configValue)
    {
        $extension = new AlphaLemonCmsExtension();
        $extension->load(array(array($configKey => $configValue)), $this->container);
        $this->assertEquals($configValue, $this->container->getParameter($parameter));
    }
}
