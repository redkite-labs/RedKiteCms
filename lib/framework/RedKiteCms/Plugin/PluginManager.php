<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\Plugin;

use RedKiteCms\Configuration\ConfigurationHandler;
use Symfony\Component\Finder\Finder;

/**
 * Class PluginManager is the object deputed to handle the installed plugins
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Plugin
 */
class PluginManager
{
    /**
     * @type array
     */
    private $blocks = array();
    /**
     * @type array
     */
    private $themes = array();
    /**
     * @type array
     */
    private $core = array();
    /**
     * @type \RedKiteCms\Configuration\ConfigurationHandler
     */
    private $configurationHandler;

    /**
     * Constructor
     *
     * @param \RedKiteCms\Configuration\ConfigurationHandler $configurationHandler
     */
    public function __construct(ConfigurationHandler $configurationHandler)
    {
        $this->configurationHandler = $configurationHandler;
    }

    /**
     * Returns core and blocks assets
     * @return array
     */
    public function getAssets()
    {
        return array_merge($this->core, $this->blocks);
    }

    /**
     * Returns core plugins
     *
     * @return array
     */
    public function getCorePlugins()
    {
        return $this->core;
    }

    /**
     * Returns blocks plugins
     *
     * @return array
     */
    public function getBlockPlugins()
    {
        return $this->blocks;
    }

    /**
     * Returns block plugin by name
     *
     * @return array
     */
    public function getBlockPlugin($name)
    {
        if (!array_key_exists($name, $this->blocks)) {
            return null;
        }

        return $this->blocks[$name];
    }

    /**
     * Returns theme plugins
     *
     * @return array
     */
    public function getThemePlugins()
    {
        return $this->themes;
    }

    /**
     * Returns theme plugin by name
     *
     * @return array
     */
    public function getThemePlugin($name)
    {
        if (!array_key_exists($name, $this->themes)) {
            return null;
        }

        return $this->themes[$name];
    }

    /**
     * Returns the active theme plugin
     *
     * @return \RedKiteCms\Plugin\Plugin
     */
    public function getActiveTheme()
    {
        $themeName = $this->configurationHandler->theme();

        return $this->themes[$themeName];
    }

    /**
     * Boots the plugin manager
     *
     * @return $this
     */
    public function boot()
    {
        $pluginFolders = $this->configurationHandler->pluginFolders();
        $this->core = $this->findPlugins($this->configurationHandler->corePluginsDir() . "/Core");
        foreach ($pluginFolders as $pluginFolder) {
            $this->blocks += $this->findPlugins($pluginFolder . "/Block");
            $this->themes += $this->findPlugins($pluginFolder . "/Theme");
        }

        return $this;
    }

    /**
     * Installs the assets for all the handled plugins
     */
    public function installAssets()
    {
        $this->doInstallAssets($this->core);
        $this->doInstallAssets($this->blocks);
        $this->doInstallAssets($this->themes);
    }

    private function findPlugins($pluginsDir)
    {
        if (!is_dir($pluginsDir)) {
            return array();
        }

        $plugins = array();
        $finder = new Finder();
        $folders = $finder->directories()->depth(0)->in($pluginsDir);
        foreach ($folders as $folder) {
            $pluginName = basename($folder);
            $plugins[$pluginName] = new Plugin(
                $pluginName,
                $this->configurationHandler->rootDir(),
                basename($pluginsDir)
            );
        }

        return $plugins;
    }

    private function doInstallAssets($plugins)
    {
        foreach ($plugins as $plugin) {
            $plugin->installAssets($this->configurationHandler->webDirname());
        }
    }
}