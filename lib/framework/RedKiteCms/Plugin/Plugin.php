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

use RedKiteCms\Exception\General\LogicException;
use RedKiteCms\Tools\FilesystemTools;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class Plugin is the object deputed to handle a generic plugin
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Plugin
 */
class Plugin
{
    /**
     * @type string
     */
    private $name;
    /**
     * @type string
     */
    private $rootDir;
    /**
     * @type string
     */
    private $pluginDir;
    /**
     * @type array
     */
    private $information;

    /**
     * Constructor
     *
     * @param string $pluginName
     * @param string $rootDir
     * @param string $type
     */
    public function __construct($pluginName, $rootDir, $type)
    {
        $this->name = $pluginName;
        $this->rootDir = $rootDir;
        $this->pluginDir = $this->getBaseDir($type, $this->name);
        $this->filesystem = new Filesystem();
        $this->parseConfiguration();
    }

    /**
     * Returns the plugin name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the plugin directory
     *
     * @return string
     */
    public function getPluginDir()
    {
        return $this->pluginDir;
    }

    /**
     * Returns true when the plugin is translated
     *
     * @return bool
     */
    public function isTranslated()
    {
        return is_dir($this->pluginDir . '/Resources/translations');
    }

    /**
     * Returns the plugin information
     *
     * @return array
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * Returns true when the plugin is a core plugin
     *
     * @return bool
     */
    public function isCore()
    {
        return (bool)strpos($this->pluginDir, 'lib/plugins');
    }

    /**
     * Returns true when the plugin has a toolbar
     *
     * @return bool
     */
    public function hasToolbar()
    {
        return file_exists($this->pluginDir . '/Resources/views/Editor/Toolbar/_toolbar.html.twig');
    }

    /**
     * Returns true when the plugin has the screenshot
     *
     * @return bool
     */
    public function hasScreenshot()
    {
        return file_exists($this->pluginDir . '/Resources/public/screenshot.jpg');
    }

    /**
     * Magic method to return single plugin information
     *
     * @param string $name
     * @param $arguments
     *
     * @return null|string
     */
    public function __call($name, $arguments)
    {
        if (!array_key_exists($name, $this->information)) {
            return null;
        }

        return $this->information[$name];
    }

    /**
     * Installs plugin assets
     *
     * @param string $targetFolder
     * @param bool $force
     */
    public function installAssets($targetFolder = "web", $force = false)
    {
        $sourceDir = $this->pluginDir . '/Resources/public';
        $targetDir = $this->rootDir . '/' . $targetFolder . '/plugins/' . strtolower($this->name);
        if (is_dir($targetDir) && !$force) {
            return;
        }

        $this->filesystem->symlink($sourceDir, $targetDir, true);
    }


    private function getBaseDir($type, $name)
    {
        $paths = array(
            sprintf('%s/lib/plugins/RedKiteCms/%s/%s', $this->rootDir, $type, $name),
            sprintf('%s/app/plugins/RedKiteCms/%s/%s', $this->rootDir, $type, $name),
        );

        return FilesystemTools::cascade($paths);
    }

    private function parseConfiguration()
    {
        $informationFile = $this->pluginDir . '/plugin.json';
        if (!file_exists($informationFile)) {
            throw new LogicException(sprintf('You must define a plugin.json file for the %s plugin.', $this->name));
        }

        $information = json_decode(FilesystemTools::readFile($informationFile), true);
        if (null === $information) {
            throw new LogicException(sprintf('The %s plugin is empty.', $this->name));
        }

        $this->information = array();
        foreach ($information as $key => $param) {
            $tokens = explode('-', $key);
            $method = "get";
            foreach ($tokens as $token) {
                $method .= ucfirst($token);
            }
            $this->information[$method] = $param;
        }
    }
}