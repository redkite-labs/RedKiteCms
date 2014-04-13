<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Controller;

use RedKiteLabs\ThemeEngineBundle\Core\Asset\Asset;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\AssetsPath\AssetsPath;

class DeployController extends Base\BaseController
{
    public function productionAction()
    {
        try {
            $activeTheme = $this->getActiveTheme();

            $deployer = $this->container->get('red_kite_cms.production_deployer');
            $templatesFolder =  $this->container->getParameter('red_kite_labs_theme_engine.deploy.templates_folder');
            $pageTreeCollection = $this->container->get('red_kite_cms.page_tree_collection');
            $deployer->deploy($pageTreeCollection, $activeTheme, $this->getOptions($templatesFolder));
            $response = $this->render('RedKiteCmsBundle:Dialog:dialog.html.twig', array('message' => 'The site has been deployed'));

            $this->clearEnvironment('prod');

            return $response;
        } catch (\Exception $e) {
            return $this->renderDialogMessage($e->getMessage());
        }
    }

    public function stageAction()
    {
        try {
            $activeTheme = $this->getActiveTheme();

            $deployer = $this->container->get('red_kite_cms.stage_deployer');
            $templatesFolder =  $this->container->getParameter('red_kite_labs_theme_engine.deploy.stage_templates_folder');
            $pageTreeCollection = $this->container->get('red_kite_cms.page_tree_collection');
            $options = $this->getOptions($templatesFolder);
            $options["assetsDir"] = $options["assetsDir"] . "/stage";
            $deployer->deploy($pageTreeCollection, $activeTheme, $options);
            $response = $this->render('RedKiteCmsBundle:Dialog:dialog.html.twig', array('message' => 'The staging site has been deployed'));

            $this->clearEnvironment('stage');

            return $response;
        } catch (\Exception $e) {
            return $this->renderDialogMessage($e->getMessage());
        }
    }

    protected function clearEnvironment($environment)
    {
        $symlink = (in_array(strtolower(PHP_OS), array('unix', 'linux'))) ? '--symlink' : '';
        $command = sprintf('assets:install %s %s', $this->container->getParameter('red_kite_cms.web_folder_full_path'), $symlink);
        $commandProcessor = $this->container->get('red_kite_cms.commands_processor');

        $environmentsSwitch = ' --env=' . $environment;
        $commandProcessor->executeCommands(array(
            $command => null,
            'assetic:dump' . $environmentsSwitch  => null,
            'cache:clear' . $environmentsSwitch => null,
        ));
    }

    private function getOptions($templatesFolder)
    {
        $kernel = $this->container->get('kernel');
        $deployBundle = $this->container->getParameter('red_kite_labs_theme_engine.deploy_bundle');
        $deployBundleAsset = new Asset($kernel, $deployBundle, $this->container->getParameter('red_kite_labs_theme_engine.web_path'));
        $deployBundlePath = $deployBundleAsset->getRealPath();
        $viewsDir = $deployBundlePath . '/Resources/views';

        return array(
            "deployBundle" => $deployBundle,
            "configDir" => $deployBundlePath . '/' . $this->container->getParameter('red_kite_cms.deploy_bundle.config_dir'),
            "assetsDir" => $deployBundlePath  . '/' . $this->container->getParameter('red_kite_cms.deploy_bundle.assets_base_dir'),
            "viewsDir" => $viewsDir,
            "templatesDir" => $templatesFolder,
            "deployDir" => $viewsDir . '/' . $templatesFolder,
            "uploadAssetsFullPath" => $this->container->getParameter('red_kite_cms.upload_assets_full_path'),
            "uploadAssetsAbsolutePath" => AssetsPath::getAbsoluteUploadFolder($this->container),
            "deployBundleAssetsPath" => "/" . $deployBundleAsset->getAbsolutePath(),
            "deployController" => $this->container->getParameter('red_kite_cms.deploy_bundle.controller'),
            "webFolderPath" => $this->container->getParameter('red_kite_cms.web_folder_full_path'),
            "websiteUrl" => $this->container->getParameter('red_kite_cms.website_url'),
            "credits" => $this->container->getParameter('red_kite_cms.love'),
        );
    }

    private function getActiveTheme()
    {
        $activeTheme = $this->container->get('red_kite_cms.active_theme');

        return $activeTheme->getActiveTheme();
    }
}
